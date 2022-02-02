<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Import;

/**
 * Get import data from file and process file to archive
 */
interface FileProcessorInterface
{
    /**
     * New directory permission
     */
    public const DIRECTORY_PERMISSION = '0775';

    /**
     * Get data from imported file
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
}
