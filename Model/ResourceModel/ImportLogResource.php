<?php

namespace CtiDigital\InventoryImport\Model\ResourceModel;

use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ImportLogResource extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'cti_digital_inventory_import_log_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('cti_digital_inventory_import_log', ImportLogInterface::LOG_ID);
        $this->_useIsObjectNew = true;
    }
}
