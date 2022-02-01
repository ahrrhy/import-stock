<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Commands\ImportLog;

use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;

interface SaveCommandInterface
{
    /**
     * Save ImportLog model
     *
     * @param ImportLogInterface $importLog
     * @return int
     */
    public function execute(ImportLogInterface $importLog): int;
}
