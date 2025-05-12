<?php
/**
 * Vendor Resource Model
 */
namespace Vendor\MultiVendor\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Vendor extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('vendor_profile', 'vendor_id');
    }
} 