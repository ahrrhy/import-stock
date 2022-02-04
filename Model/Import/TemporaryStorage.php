<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Model\Import;

use CtiDigital\InventoryImport\Api\Config\ConfigProviderInterface;
use CtiDigital\InventoryImport\Api\Import\TemporaryStorageInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\TableNotFoundException;
use Magento\Framework\DB\Ddl\Table;
use Psr\Log\LoggerInterface;
use Zend_Db_Exception;

class TemporaryStorage implements TemporaryStorageInterface
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * TemporaryStorage constructor.
     * @param LoggerInterface $logger
     * @param ResourceConnection $resource
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        LoggerInterface $logger,
        ResourceConnection $resource,
        ConfigProviderInterface $configProvider
    ) {
        $this->logger = $logger;
        $this->resource = $resource;
        $this->configProvider = $configProvider;
    }

    /**
     * @inheritDoc
     */
    public function writeToTemporaryTable(string $tableName, array $importData): int
    {
        $columns = array_keys($importData[0]);
        $temporaryTable = $this->createTemporaryTable($tableName);
        $connection = $this->resource->getConnection();
        $batchSize = $this->configProvider->getImportBatchSize();
        $insertedRows = 0;
        $iterationCount = (int)ceil(count($importData) / $batchSize);

        try {
            for ($i = 0; $i < $iterationCount; $i++) {
                $connection->beginTransaction();
                $bulkData = array_slice(
                    $importData,
                    $i * $batchSize,
                    $batchSize,
                    true
                );
                $insertedRows = $this->resource->getConnection()->insertArray(
                    $temporaryTable->getName(),
                    $columns,
                    $bulkData
                );
                $connection->commit();
            }
        } catch (Zend_Db_Exception $exception) {
            $this->logger->critical($exception->getMessage());
            $connection->rollBack();
        }

        return $insertedRows;
    }

    /**
     * @inheritDoc
     */
    public function dropTemporaryTable(string $tableName)
    {
        $resourceTableName = $this->resource->getTableName($tableName);
        $connection = $this->resource->getConnection();

        if ($connection->isTableExists($tableName)) {
            $connection->dropTable($resourceTableName);
        }
    }

    /**
     * Return data as ['product_id', 'sku', 'qty'] array
     *
     * @inheritDoc
     */
    public function selectFromTemporaryTable(string $tableName): array
    {
        $data = [];
        $connection = $this->resource->getConnection();

        try {
            $data = $connection->select()->from(
                $this->resource->getTableName($tableName),
                ['sku', 'qty']
            )->joinInner(
                ['products' => $this->resource->getTableName('catalog_product_entity')],
                'products.sku = main_table.sku',
                ['entity_id' => 'product_id']
            )->query()->fetch(\PDO::FETCH_ASSOC);
        } catch (Zend_Db_Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }

        return $data;
    }

    /**
     * @param string $tableName
     * @return Table
     * @throws TableNotFoundException
     */
    private function createTemporaryTable(
        string $tableName
    ): Table {
        $connection = $this->resource->getConnection();
        $nemTableName = $this->resource->getTableName($tableName);

        if ($connection->isTableExists($nemTableName)) {
            $connection->dropTable($nemTableName);
        }

        try {
            $table = $connection->newTable($nemTableName);
            $table->addColumn(
                'input_id',
                Table::TYPE_INTEGER,
                10,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Import input id'
            );
            $table->addColumn(
                self::SKU_COLUMN,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Product Sku'
            );
            $table->addColumn(
                self::QTY_COLUMN,
                Table::TYPE_INTEGER,
                10,
                ['unsigned' => true, 'nullable' => false],
                'Product Qty'
            );
            $table->setOption('type', 'memory');
            $connection->createTemporaryTable($table);

            return $table;
        } catch (Zend_Db_Exception $exception) {
            $this->logger->critical($exception->getMessage());

            throw new TableNotFoundException($exception->getMessage());
        }
    }
}
