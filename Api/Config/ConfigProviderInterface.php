<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

interface ConfigProviderInterface
{
    public const MODULE_ENABLED_PATH = 'cti_digital/import/enable';
    public const FILE_DIRECTORY_PATH = 'cti_digital/import/directory';
    public const PROCESSED_FILE_DIRECTORY_PATH = 'cti_digital/import/processed_directory';
    public const FILE_NAME_PATH = 'cti_digital/import/file_name';
    public const IMPORT_BATCH_SIZE_PATH = 'cti_digital/import/batch_size';

    /**
     * Check is module enabled in admin
     *
     * @param string $scope
     * @param null $storeId
     * @return bool
     */
    public function isModuleEnabled(
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $storeId = null
    ): bool;

    /**
     * Get import file directory path
     *
     * @param string $scope
     * @param null $storeId
     * @return string
     */
    public function getFileDirectoryPath(
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $storeId = null
    ): string;

    /**
     * Get processed file directory
     *
     * @param string $scope
     * @param null $storeId
     * @return string
     */
    public function getProcessedFileDirectoryPath(
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $storeId = null
    ): string;

    /**
     * Get import file name from configuration
     *
     * @param string $scope
     * @param null $storeId
     * @return string
     */
    public function getImportFileName(
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $storeId = null
    ): string;

    /**
     * Get Import Batch Size
     *
     * @param string $scope
     * @param null $storeId
     * @return int
     */
    public function getImportBatchSize(
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $storeId = null
    ): int;
}
