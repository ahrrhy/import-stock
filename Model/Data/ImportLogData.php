<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Model\Data;

use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;
use Magento\Framework\DataObject;

/**
 * ImportLog Data Transfer Object
 */
class ImportLogData extends DataObject implements ImportLogInterface
{
    /**
     * @inheritDoc
     */
    public function getLogId(): ?int
    {
        return $this->getData(self::LOG_ID) === null ? null
            : (int)$this->getData(self::LOG_ID);
    }

    /**
     * @inheritDoc
     */
    public function setLogId(?int $logId): ImportLogInterface
    {
        return $this->setData(self::LOG_ID, $logId);
    }

    /**
     * @inheritDoc
     */
    public function getImportStatus(): string
    {
        return $this->getData(self::IMPORT_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setImportStatus(string $status): ImportLogInterface
    {
        return $this->setData(self::IMPORT_STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getImportData(): string
    {
        return $this->getData(self::IMPORT_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setImportData(?string $importData = null): ImportLogInterface
    {
        return $this->setData(self::IMPORT_DATA, $importData);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(?string $createdAt = null): ImportLogInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
