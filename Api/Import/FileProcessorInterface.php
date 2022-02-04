<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Import;

use Magento\Framework\Exception\NotFoundException;

/**
 * Get import data from file and process file to archive
 */
interface FileProcessorInterface
{
    public const FILE_QTY_COLUMN = 'stock';

    /**
     * Get data from imported file
     * @throws NotFoundException
     *
     * @return array
     */
    public function readData(): array;

    /**
     * Check and create import/archive directory
     *
     * @param string $directoryName
     * @return bool
     */
    public function checkDirectory(string $directoryName): bool;

    /**
     * Mark file as processed
     *
     * @return bool
     */
    public function processFile(): bool;

    /**
     * Check if file is present
     *
     * @param string $directoryName
     * @param string $fileName
     * @return bool
     */
    public function isImportFileExists(string $directoryName, string $fileName): bool;
}
