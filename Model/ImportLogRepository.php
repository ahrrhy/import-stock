<?php
/**
 * Copyright © 2021 Helen of Troy. (https://www.helenoftroy.com/)
 */
declare(strict_types=1);

namespace CtiDigital\InventoryImport\Model;

use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;
use CtiDigital\InventoryImport\Api\Commands\ImportLog\GetByIdCommandInterface;
use CtiDigital\InventoryImport\Api\Commands\ImportLog\DeleteByIdCommandInterface;
use CtiDigital\InventoryImport\Api\Commands\ImportLog\SaveCommandInterface;

class ImportLogRepository implements \CtiDigital\InventoryImport\Api\ImportLogRepositoryInterface
{
    /**
     * @var GetByIdCommandInterface
     */
    private $getByIdCommand;

    /**
     * @var SaveCommandInterface
     */
    private $saveCommand;

    /**
     * @var DeleteByIdCommandInterface
     */
    private $deleteByIdCommand;

    /**
     * @var ImportLogInterface[]
     */
    private $importLogDto;

    /**
     * ImportLogRepository constructor.
     * @param DeleteByIdCommandInterface $deleteByIdCommand
     * @param GetByIdCommandInterface $getByIdCommand
     * @param SaveCommandInterface $saveCommand
     */
    public function __construct(
        DeleteByIdCommandInterface $deleteByIdCommand,
        GetByIdCommandInterface $getByIdCommand,
        SaveCommandInterface $saveCommand
    ) {
        $this->deleteByIdCommand = $deleteByIdCommand;
        $this->getByIdCommand = $getByIdCommand;
        $this->saveCommand = $saveCommand;
    }

    /**
     * @inheritDoc
     */
    public function get(int $importLogId): ImportLogInterface
    {
        if (!isset($this->importLogDto[$importLogId])) {
            $this->importLogDto[$importLogId] = $this->getByIdCommand->execute($importLogId);
        }

        return $this->importLogDto[$importLogId];
    }

    /**
     * @inheritDoc
     */
    public function save(ImportLogInterface $importLogData): int
    {
        return $this->saveCommand->execute($importLogData);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $importLogId): void
    {
        $this->deleteByIdCommand->execute($importLogId);
    }

    /**
     * @inheritDoc
     */
    public function delete(ImportLogInterface $importLog): void
    {
        $this->deleteById($importLog->getLogId());
    }
}
