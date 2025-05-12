<?php
/**
 * Vendor Product Model
 */
namespace Vendor\MultiVendor\Model;

use Magento\Framework\Model\AbstractModel;
use Vendor\MultiVendor\Model\ResourceModel\VendorProduct as VendorProductResourceModel;

class VendorProduct extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(VendorProductResourceModel::class);
    }
} 