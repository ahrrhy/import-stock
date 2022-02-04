<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Import;

/**
 * Change product qty according to imported data
 */
interface ProductQtyProcessorInterface
{
    /**
     * Get data from temporary storage and updates Product stock qty
     *
     * @param array $importedData
     * @return int rows modified
     */
    public function processQty(array $importedData): int;
}
