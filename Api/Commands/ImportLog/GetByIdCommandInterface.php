<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Commands\ImportLog;

use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;

interface GetByIdCommandInterface
{
    /**
     * Get ImportLog data by id
     *
     * @param int $importLogId
     * @return ImportLogInterface
     */
    public function execute(int $importLogId): ImportLogInterface;
}
