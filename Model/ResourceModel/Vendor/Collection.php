<?php
/**
 * Vendor Collection
 */
namespace Vendor\MultiVendor\Model\ResourceModel\Vendor;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\MultiVendor\Model\Vendor;
use Vendor\MultiVendor\Model\ResourceModel\Vendor as VendorResourceModel;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            Vendor::class,
            VendorResourceModel::class
        );
    }
} 