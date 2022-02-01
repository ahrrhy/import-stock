<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Command\ImportLog;

use CtiDigital\InventoryImport\Api\Commands\ImportLog\GetByIdCommandInterface;
use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;
use CtiDigital\InventoryImport\Api\Data\ImportLogInterfaceFactory;
use CtiDigital\InventoryImport\Model\ImportLogModel;
use CtiDigital\InventoryImport\Model\ImportLogModelFactory;
use CtiDigital\InventoryImport\Model\ResourceModel\ImportLogResource;
use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Get ImportLog Command.
 */
class GetByIdCommand implements GetByIdCommandInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ImportLogModelFactory
     */
    private $modelFactory;

    /**
     * @var ImportLogInterfaceFactory
     */
    private $dtoFactory;

    /**
     * @var ImportLogResource
     */
    private $resource;

    /**
     * @param LoggerInterface $logger
     * @param ImportLogInterfaceFactory $dtoFactory
     * @param ImportLogModelFactory $modelFactory
     * @param ImportLogResource $resource
     */
    public function __construct(
        LoggerInterface $logger,
        ImportLogInterfaceFactory $dtoFactory,
        ImportLogModelFactory $modelFactory,
        ImportLogResource $resource
    ) {
        $this->logger = $logger;
        $this->dtoFactory = $dtoFactory;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Get ImportLog or throw NoSuchEntityException
     * @param int $importLogId
     * @return ImportLogInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $importLogId): ImportLogInterface
    {
        $model = $this->modelFactory->create();
        $this->resource->load($model, $importLogId);

        if (!$model->getData(ImportLogInterface::LOG_ID)) {
            $this->logger->error(
                __('Could not get ImportLog. Original message: {message}'),
                [
                    'message' => sprintf('No such entity with id %b', $importLogId)
                ]
            );

            throw new NoSuchEntityException(__('No such entity with id %1', $importLogId));
        }

        $dtoModel = $this->dtoFactory->create();
        $dtoModel->setData($model->getData());

        return $dtoModel;
    }
}
