<?php

namespace CtiDigital\InventoryImport\Command\ImportLog;

use CtiDigital\InventoryImport\Api\Commands\ImportLog\SaveCommandInterface;
use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;
use CtiDigital\InventoryImport\Model\ImportLogModel;
use CtiDigital\InventoryImport\Model\ImportLogModelFactory;
use CtiDigital\InventoryImport\Model\ResourceModel\ImportLogResource;
use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;

/**
 * Save ImportLog Command.
 */
class SaveCommand implements SaveCommandInterface
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
     * Save ImportLog.
     *
     * @param ImportLogInterface $importLog
     *
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(ImportLogInterface $importLog): int
    {
        try {
            /** @var ImportLogModel $model */
            $model = $this->modelFactory->create();
            $model->addData($importLog->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(ImportLogInterface::LOG_ID)) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save ImportLog. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save ImportLog.'));
        }

        return (int)$model->getData(ImportLogInterface::LOG_ID);
    }
}
