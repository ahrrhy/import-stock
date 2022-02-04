<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Model\Import;

use CtiDigital\InventoryImport\Api\Config\ConfigProviderInterface;
use CtiDigital\InventoryImport\Api\Import\ProductQtyProcessorInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;

class ProductQtyProcessor implements ProductQtyProcessorInterface
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * ProductQtyProcessor constructor.
     * @param ResourceConnection $resource
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        ResourceConnection $resource,
        ConfigProviderInterface $configProvider
    ) {
        $this->resource = $resource;
        $this->configProvider = $configProvider;
    }

    /**
     * @param array $importedData
     * @return int
     * @throws LocalizedException
     */
    public function processQty(array $importedData): int
    {
        $connection = $this->resource->getConnection();
        $batchSize = $this->configProvider->getImportBatchSize();
        $updatedQty = 0;
        $iterationCount = (int)ceil(count($importedData) / $batchSize);

        try {
            for ($i = 0; $i < $iterationCount; $i++) {
                $connection->beginTransaction();
                $bulkData = array_slice(
                    $importedData,
                    $i * $batchSize,
                    $batchSize,
                    true
                );

                foreach ($bulkData as $row) {
                    $updatedQty += $connection->update(
                        $this->resource->getTableName('cataloginventory_stock_item'),
                        ['qty' => $row['qty']],
                        [
                            'product_id' . ' in (?)' => $row['product_id'],
                            'website_id = ?' => 0,
                            'qty <> ?' => $row['qty']
                        ]
                    );
                }

                $connection->commit();
            }
        } catch (\Exception $exception) {
            $connection->rollBack();

            throw new LocalizedException(__('Could not update qty: %1', $exception->getMessage()));
        }

        return $updatedQty;
    }
}
