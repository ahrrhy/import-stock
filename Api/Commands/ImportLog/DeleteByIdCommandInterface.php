<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Commands\ImportLog;

interface DeleteByIdCommandInterface
{
    /**
     * Deleting ImportLogId
     *
     * @param int $importLogId
     */
    public function execute(int $importLogId): void;
}
