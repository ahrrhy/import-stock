<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Model\Import;

use CtiDigital\InventoryImport\Api\Import\ProductQtyProcessorInterface;
use Magento\Framework\App\ResourceConnection;

class ProductQtyProcessor implements ProductQtyProcessorInterface
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * @ingeritdoc
     */
    public function processQty(array $importedData): int
    {
        $connection = $this->resource->getConnection();
        $productIds = $this->separateArrays($importedData, 'product_id');
        $productsQty = $this->separateArrays(
            $importedData,
            'qty',
            true
        );

        return $connection->update(
            $this->resource->getTableName('cataloginventory_stock_item'),
            $productsQty,
            [
                'product_id' . ' in (?)' => $productIds,
                'website_id = ?' => 0,
            ]
        );
    }

    /**
     * @param array $incomingArray
     * @param string $arrayColumn
     * @param bool $asAssoc
     * @return array
     */
    private function separateArrays(
        array $incomingArray,
        string $arrayColumn,
        bool $asAssoc = false
    ): array {
        $newArray = [];

        foreach ($incomingArray as $child) {
            if (isset($child[$arrayColumn])) {
                if ($asAssoc) {
                    $newArray[][$arrayColumn] = $child[$arrayColumn];
                } else {
                    $newArray[] = $child[$arrayColumn];
                }
            }
        }

        return $newArray;
    }
}
