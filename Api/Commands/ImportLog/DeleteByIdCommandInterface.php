<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Commands\ImportLog;

interface DeleteByIdCommandInterface
{
    /**
     * Deleting ImportLogId
     *
     * @param $importLogId
     */
    public function execute($importLogId): void;
}
