<?php

declare(strict_types=1);

namespace CtiDigital\InventoryImport\Logger\Handler;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger as MonologLogger;

/**
 * Custom InventoryImportLogger
 */
class InventoryImportLogger extends Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = MonologLogger::DEBUG;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = '/var/log/inventory.log';
}
