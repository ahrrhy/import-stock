<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Cron;

use CtiDigital\InventoryImport\Api\Config\ConfigProviderInterface;
use CtiDigital\InventoryImport\Api\Data\ImportLogInterfaceFactory;
use CtiDigital\InventoryImport\Api\Import\ImportProcessorInterface;

/**
 * Inventory Import Cron
 */
class CleanTableCronjob
{
    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * @var ImportProcessorInterface
     */
    private $importProcessor;

    /**
     * CleanTableCronjob constructor.
     * @param ConfigProviderInterface $configProvider
     * @param ImportProcessorInterface $importProcessor
     */
    private function __construct(
        ConfigProviderInterface $configProvider,
        ImportProcessorInterface $importProcessor
    ) {
        $this->configProvider = $configProvider;
        $this->importProcessor = $importProcessor;
    }

    /**
     * Make import from file
     *
     * @return void
     */
    public function execute(): void
    {
        if ($this->configProvider->isModuleEnabled()) {
            $this->importProcessor->execute();
        }
    }
}
