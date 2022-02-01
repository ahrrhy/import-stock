<?php

namespace CtiDigital\InventoryImport\Model\ResourceModel\ImportLogModel;

use CtiDigital\InventoryImport\Model\ImportLogModel;
use CtiDigital\InventoryImport\Model\ResourceModel\ImportLogResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class ImportLogCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'cti_digital_inventory_import_log_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ImportLogModel::class, ImportLogResource::class);
    }
}
