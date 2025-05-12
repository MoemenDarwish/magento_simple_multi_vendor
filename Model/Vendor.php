<?php
/**
 * Vendor Model
 */
namespace Vendor\MultiVendor\Model;

use Magento\Framework\Model\AbstractModel;
use Vendor\MultiVendor\Model\ResourceModel\Vendor as VendorResourceModel;

class Vendor extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(VendorResourceModel::class);
    }
} 