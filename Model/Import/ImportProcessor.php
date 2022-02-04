<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Model\Import;

use CtiDigital\InventoryImport\Api\Config\ConfigProviderInterface;
use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;
use CtiDigital\InventoryImport\Api\Data\ImportLogInterfaceFactory;
use CtiDigital\InventoryImport\Api\Import\FileProcessorInterface;
use CtiDigital\InventoryImport\Api\Import\ImportProcessorInterface;
use CtiDigital\InventoryImport\Api\Import\ProductQtyProcessorInterface;
use CtiDigital\InventoryImport\Api\Import\TemporaryStorageInterface;
use CtiDigital\InventoryImport\Api\ImportLogRepositoryInterface;
use Magento\Framework\Exception\NotFoundException;
use Psr\Log\LoggerInterface;

class ImportProcessor implements ImportProcessorInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FileProcessorInterface
     */
    private $fileProcessor;

    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * @var TemporaryStorageInterface
     */
    private $temporaryStorage;

    /**
     * @var ImportLogInterfaceFactory
     */
    private $importLogFactory;

    /**
     * @var ImportLogRepositoryInterface
     */
    private $importLogRepository;

    /**
     * @var ProductQtyProcessorInterface
     */
    private $productQtyProcessor;

    public function __construct(
        LoggerInterface $logger,
        FileProcessorInterface $fileProcessor,
        ConfigProviderInterface $configProvider,
        TemporaryStorageInterface $temporaryStorage,
        ImportLogInterfaceFactory $importLogFactory,
        ImportLogRepositoryInterface $importLogRepository,
        ProductQtyProcessorInterface $productQtyProcessor
    ) {
        $this->logger = $logger;
        $this->fileProcessor = $fileProcessor;
        $this->configProvider = $configProvider;
        $this->temporaryStorage = $temporaryStorage;
        $this->importLogFactory = $importLogFactory;
        $this->importLogRepository = $importLogRepository;
        $this->productQtyProcessor = $productQtyProcessor;
    }

    public function execute(): void
    {
        $importDirectory = $this->configProvider->getFileDirectoryPath();
        $importFile = $this->configProvider->getImportFileName();

        if (!$this->fileProcessor->isImportFileExists($importDirectory, $importFile)) {
            $this->logger->debug('Empty entry');
            $this->logToTable(ImportLogInterface::STATUS_EMPTY);

            return;
        }

        try {
            $savedData = $this->temporaryStorage->writeToTemporaryTable(
                TemporaryStorageInterface::TEMPORARY_TABLE_NAME,
                $this->fileProcessor->readData()
            );
            $this->logger->debug(__('Saved to temporary storage: %1', $savedData));

            if ($savedData) {
                $processedQty = $this->productQtyProcessor->processQty(
                    $this->temporaryStorage->selectFromTemporaryTable(
                        TemporaryStorageInterface::TEMPORARY_TABLE_NAME
                    )
                );
                $this->logger->debug(__('Updated products qty: %1', $processedQty));
                $this->temporaryStorage->dropTemporaryTable(
                    TemporaryStorageInterface::TEMPORARY_TABLE_NAME
                );
                $this->logToTable(
                    ImportLogInterface::STATUS_PROCESSED,
                    sprintf('Processed %d products', $processedQty)
                );

                $this->fileProcessor->processFile();
            }
        } catch (NotFoundException $exception) {
            $this->logToTable(ImportLogInterface::STATUS_FAILED);
            $this->logger->debug($exception->getMessage());
        }
    }

    /**
     * Log import process to DB
     *
     * @param string $status
     * @param string $data
     */
    private function logToTable(
        string $status,
        string $data = ''
    ): void {
        $importLog = $this->importLogFactory->create();
        $importLog->setImportStatus($status);
        $importLog->setImportData($data);
        $this->importLogRepository->save($importLog);
    }
}
