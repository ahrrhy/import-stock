<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Api\Import;

interface ImportProcessorInterface
{
    public function execute(): void;
}
