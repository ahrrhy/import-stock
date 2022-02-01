<?php

namespace CtiDigital\InventoryImport\Mapper;

use CtiDigital\InventoryImport\Api\Data\ImportLogInterface;
use CtiDigital\InventoryImport\Api\Data\ImportLogInterfaceFactory;
use CtiDigital\InventoryImport\Model\ImportLogModel;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Converts a collection of ImportLog entities to an array of data transfer objects.
 */
class ImportLogDataMapper
{
    /**
     * @var ImportLogInterfaceFactory
     */
    private $entityDtoFactory;

    /**
     * @param ImportLogInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        ImportLogInterfaceFactory $entityDtoFactory
    ) {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|ImportLogInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var ImportLogModel $item */
        foreach ($collection->getItems() as $item) {
            /** @var ImportLogInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
