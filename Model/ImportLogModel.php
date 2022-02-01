<?php

namespace CtiDigital\InventoryImport\Model;

use CtiDigital\InventoryImport\Model\ResourceModel\ImportLogResource;
use Magento\Framework\Model\AbstractModel;

class ImportLogModel extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'cti_digital_inventory_import_log_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ImportLogResource::class);
    }
}
