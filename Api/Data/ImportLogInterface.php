<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * ImportLog DTO Interface
 */
interface ImportLogInterface extends ExtensibleDataInterface
{
    /**
     * String constants for property names
     */
    const LOG_ID = "log_id";
    const IMPORT_STATUS = 'import_status';
    const IMPORT_DATA = 'import_data';
    const CREATED_AT = 'created_at';

    /**
     * Getter for LogId.
     *
     * @return int|null
     */
    public function getLogId(): ?int;

    /**
     * Setter for LogId.
     *
     * @param int|null $logId
     *
     * @return ImportLogInterface
     */
    public function setLogId(?int $logId): ImportLogInterface;

    /**
     * Getter for ImportStatus.
     *
     * @return string
     */
    public function getImportStatus(): string;

    /**
     * Setter for ImportStatus.
     *
     * @param string $status
     *
     * @return ImportLogInterface
     */
    public function setImportStatus(string $status): ImportLogInterface;

    /**
     * Getter for ImportData.
     *
     * @return string|null
     */
    public function getImportData(): ?string;

    /**
     * Setter for ImportData.
     *
     * @param string|null $importData
     *
     * @return ImportLogInterface
     */
    public function setImportData(?string $importData = null): ImportLogInterface;

    /**
     * Getter for CreatedAt.
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return ImportLogInterface
     */
    public function setCreatedAt(?string $createdAt = null): ImportLogInterface;
}
