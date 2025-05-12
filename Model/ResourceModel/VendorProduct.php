<?php
/**
 * Vendor Product Resource Model
 */
namespace Vendor\MultiVendor\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class VendorProduct extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('vendor_product', 'id');
    }
} 