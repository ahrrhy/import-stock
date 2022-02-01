<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api;

use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface ImportLogRepositoryInterface
{
    /**
     * Get ImportLog by it's id
     *
     * @param int $importLogId
     * @return ImportLogInterface
     */
    public function get(int $importLogId): ImportLogInterface;

    /**
     * Save ImportLog data
     *
     * @param ImportLogInterface $importLogData
     * @return int
     */
    public function save(ImportLogInterface $importLogData): int;

    /**
     * Delete ImportLog by it's id
     *
     * @param int $importLogId
     */
    public function deleteById(int $importLogId): void;

    /**
     * Delete ImportLog
     *
     * @param ImportLogInterface $importLog
     */
    public function delete(ImportLogInterface $importLog): void;
}
