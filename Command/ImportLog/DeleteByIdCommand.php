<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Command\ImportLog;

use CtiDigital\InventoryImport\Api\Commands\ImportLog\DeleteByIdCommandInterface;
use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;
use CtiDigital\InventoryImport\Model\ImportLogModel;
use CtiDigital\InventoryImport\Model\ImportLogModelFactory;
use CtiDigital\InventoryImport\Model\ResourceModel\ImportLogResource;
use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Delete ImportLog by id Command.
 */
class DeleteByIdCommand implements DeleteByIdCommandInterface
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
     * @var ImportLogResource
     */
    private $resource;

    /**
     * @param LoggerInterface $logger
     * @param ImportLogModelFactory $modelFactory
     * @param ImportLogResource $resource
     */
    public function __construct(
        LoggerInterface $logger,
        ImportLogModelFactory $modelFactory,
        ImportLogResource $resource
    ) {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * Delete ImportLog.
     *
     * @param int $entityId
     *
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $entityId): void
    {
        try {
            /** @var ImportLogModel $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, ImportLogInterface::LOG_ID);

            if (!$model->getData(ImportLogInterface::LOG_ID)) {
                throw new NoSuchEntityException(
                    __('Could not find ImportLog with id: `%id`', ['id' => $entityId])
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete ImportLog. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete ImportLog.'));
        }
    }
}
