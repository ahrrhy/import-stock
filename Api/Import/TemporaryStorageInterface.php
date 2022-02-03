<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Import;

/**
 * Temporary table storage for import data items
 */
interface TemporaryStorageInterface
{
    /**
     * Temporary table data
     */
    public const TEMPORARY_TABLE_NAME = 'cti_digital_inventory_import_temporary';

    /**
     * Create Temporary Table and write import data
     *
     * @param string $tableName
     * @param array $importData
     * @return int
     */
    public function writeToTemporaryTable(string $tableName, array $importData): int;

    /**
     * Drop Temporary Table after import data processed
     *
     * @param string $tableName
     * @return mixed
     */
    public function dropTemporaryTable(string $tableName);

    /**
     * Get data from temporary table
     *
     * @param string $tableName
     * @return array
     */
    public function selectFromTemporaryTable(string $tableName): array;
}
