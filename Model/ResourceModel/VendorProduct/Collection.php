<?php
/**
 * Vendor Product Collection
 */
namespace Vendor\MultiVendor\Model\ResourceModel\VendorProduct;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\MultiVendor\Model\VendorProduct;
use Vendor\MultiVendor\Model\ResourceModel\VendorProduct as VendorProductResourceModel;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            VendorProduct::class,
            VendorProductResourceModel::class
        );
    }
} 