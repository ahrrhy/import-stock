<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Console\Command;

use CtiDigital\InventoryImport\Api\Config\ConfigProviderInterface;
use CtiDigital\InventoryImport\Api\Import\ImportProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Inventory Import Command
 */
class InventoryImportCommand extends Command
{
    public const COMMAND_NAME = 'cti-digital:inventory:import';

    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * @var ImportProcessorInterface
     */
    private $importProcessor;

    /**
     * InventoryImportCommand constructor.
     * @param ConfigProviderInterface $configProvider
     * @param ImportProcessorInterface $importProcessor
     * @param string|null $name
     */
    public function __construct(
        ConfigProviderInterface $configProvider,
        ImportProcessorInterface $importProcessor,
        string $name = null
    ) {
        $this->configProvider = $configProvider;
        $this->importProcessor = $importProcessor;
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Process Inventory Import instead of cron');
        parent::configure();
    }

    /**
     * Run inventory import from cli command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        if ($this->configProvider->isModuleEnabled()) {
            $this->importProcessor->execute();
        }
    }
}
