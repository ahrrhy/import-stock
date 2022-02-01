<?php

namespace CtiDigital\InventoryImport\Controller\Adminhtml\ImportLog;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * ImportLog backend index (list) controller.
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    const ADMIN_RESOURCE = 'CtiDigital_InventoryImport::inventory_import_management';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('CtiDigital_InventoryImport::inventory_import_management');
        $resultPage->addBreadcrumb(__('ImportLog'), __('ImportLog'));
        $resultPage->addBreadcrumb(__('Manage ImportLogs'), __('Manage ImportLogs'));
        $resultPage->getConfig()->getTitle()->prepend(__('ImportLog List'));

        return $resultPage;
    }
}
