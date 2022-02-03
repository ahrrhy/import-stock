<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Model\Config;

use CtiDigital\InventoryImport\Api\Config\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Retrieve module configurations
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ConfigProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function isModuleEnabled(
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $storeId = null
    ): bool {
        return $this->scopeConfig->isSetFlag(
            self::MODULE_ENABLED_PATH,
            $scope,
            $storeId
        );
    }

    /**
     * @inheritdoc
     */
    public function getFileDirectoryPath(
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $storeId = null
    ): string {
        return $this->scopeConfig->getValue(
            self::FILE_DIRECTORY_PATH,
            $scope,
            $storeId
        );
    }

    /**
     * @inheritdoc
     */
    public function getProcessedFileDirectoryPath(
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $storeId = null
    ): string {
        return $this->scopeConfig->getValue(
            self::PROCESSED_FILE_DIRECTORY_PATH,
            $scope,
            $storeId
        );
    }

    /**
     * @inheritdoc
     */
    public function getImportFileName(
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $storeId = null
    ): string {
        return $this->scopeConfig->getValue(
            self::FILE_NAME_PATH,
            $scope,
            $storeId
        );
    }

    /**
     * @inheritdoc
     */
    public function getImportBatchSize(
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $storeId = null
    ): int {
        return (int)$this->scopeConfig->getValue(
            self::IMPORT_BATCH_SIZE_PATH,
            $scope,
            $storeId
        );
    }
}
