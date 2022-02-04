<?php
/**
 * Copyright © 2021 Helen of Troy. (https://www.helenoftroy.com/)
 */
declare(strict_types=1);

namespace CtiDigital\InventoryImport\Model\Import;

use CtiDigital\InventoryImport\Api\Config\ConfigProviderInterface;
use CtiDigital\InventoryImport\Api\Import\FileProcessorInterface;
use CtiDigital\InventoryImport\Api\Import\TemporaryStorageInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\File\Csv;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Psr\Log\LoggerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Filesystem\DriverInterface;

class FileProcessor implements FileProcessorInterface
{
    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * @var Csv
     */
    private $csv;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var File
     */
    private $ioFile;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array|null
     */
    private $filePaths;

    /**
     * @var TimezoneInterface
     */
    private $timeZone;

    /**
     * FileProcessor constructor.
     * @param Csv $csv
     * @param File $ioFile
     * @param LoggerInterface $logger
     * @param TimezoneInterface $timeZone
     * @param DirectoryList $directoryList
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        Csv $csv,
        File $ioFile,
        LoggerInterface $logger,
        TimezoneInterface $timeZone,
        DirectoryList $directoryList,
        ConfigProviderInterface $configProvider
    ) {
        $this->csv = $csv;
        $this->ioFile = $ioFile;
        $this->logger = $logger;
        $this->timeZone = $timeZone;
        $this->directoryList = $directoryList;
        $this->configProvider = $configProvider;
    }

    /**
     * @param string $directoryName
     * @return bool
     * @throws NotFoundException
     */
    public function checkDirectory(string $directoryName): bool
    {
        try {
            return $this->ioFile->checkAndCreateFolder(
                $this->directoryList->getPath(DirectoryList::VAR_DIR)
                . DIRECTORY_SEPARATOR . $directoryName
            );
        } catch (LocalizedException $exception) {
            $this->logger->debug(__('Could not get imports directory: %1', $exception->getMessage()));

            throw new NotFoundException(__('Could not get imports directory: %1', $exception->getMessage()));
        }
    }

    /**
     * @return array
     * @throws NotFoundException
     */
    public function readData(): array
    {
        $importFileName = $this->configProvider->getImportFileName();
        $importDirName = $this->configProvider->getFileDirectoryPath();
        $data = [];

        try {
            if ($this->isImportFileExists($importDirName, $importFileName)) {
                $data = $this->csv->getData(
                    $this->getFilePath($importDirName, $importFileName)
                );
                unset($data[0]);

                return $this->mapCsvColumn(
                    $data,
                    TemporaryStorageInterface::SKU_COLUMN,
                    TemporaryStorageInterface::QTY_COLUMN
                );
            }
        } catch (NotFoundException $exception) {
            $this->logger->debug($exception->getMessage());

            throw new NotFoundException(__($exception->getMessage()));
        } catch (FileSystemException $exception) {
            $this->logger->debug(__('Could not get imports directory: %1', $exception->getMessage()));

            throw new NotFoundException(__('Could not get imports directory: %1', $exception->getMessage()));
        }

        return $data;
    }

    /**
     * @return bool
     * @throws NotFoundException
     */
    public function processFile(): bool
    {
        $importFileName = $this->configProvider->getImportFileName();
        $importDirName = $this->configProvider->getFileDirectoryPath();
        $archiveDirName = $this->configProvider->getProcessedFileDirectoryPath();
        $archiveFileName = 'import_' . $this->timeZone->scopeTimeStamp() . '.csv';

        try {
            $srcFilePath = $this->getFilePath($importDirName, $importFileName);
            $archivedFilePath = $this->getFilePath($archiveDirName, $archiveFileName);
            $this->checkDirectory($archiveDirName);

            return $this->ioFile->mv($srcFilePath, $archivedFilePath);
        } catch (FileSystemException $exception) {
            $this->logger->debug(__('Could not get imports directory: %1', $exception->getMessage()));

            throw new NotFoundException(__('Could not get imports directory: %1', $exception->getMessage()));
        }
    }

    /**
     * Check if imported file existed
     *
     * @param string $directoryName
     * @param string $fileName
     * @return bool
     * @throws NotFoundException
     */
    public function isImportFileExists(string $directoryName, string $fileName): bool
    {
        try {
            $this->checkDirectory($directoryName);
            $filePath = $this->getFilePath($directoryName, $fileName);

            return $this->ioFile->fileExists($filePath, true);
        } catch (FileSystemException $exception) {
            $this->logger->debug(__('Could not get imports directory: %1', $exception->getMessage()));

            throw new NotFoundException(__('Could not get imports directory: %1', $exception->getMessage()));
        }
    }

    /**
     * @param string $directoryName
     * @param string $fileName
     * @return string
     * @throws FileSystemException
     */
    private function getFilePath(string $directoryName, string $fileName): string
    {
        if (!isset($this->filePaths[$directoryName])) {
            $this->filePaths[$directoryName] = $this->directoryList->getPath(DirectoryList::VAR_DIR)
                . DIRECTORY_SEPARATOR . $directoryName . DIRECTORY_SEPARATOR . $fileName;
        }

        return $this->filePaths[$directoryName];
    }

    /**
     * Change column names from file
     *
     * @param array $csvData
     * @param string $firstColumn
     * @param string $secondColumn
     * @return array
     */
    private function mapCsvColumn(
        array $csvData,
        string $firstColumn,
        string $secondColumn
    ): array {
        $newArray = [];

        foreach ($csvData as $row) {
            if (isset($row[0], $row[1])) {
                $newRow[$firstColumn] = $row[0];
                $newRow[$secondColumn] = $row[1];
                $newArray[] = $newRow;
            }
        }

        return $newArray;
    }
}
