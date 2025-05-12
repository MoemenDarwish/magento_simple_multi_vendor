<?php
namespace Vendor\MultiVendor\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Vendor\MultiVendor\Model\ResourceModel\VendorProduct\CollectionFactory as VendorProductCollectionFactory;
use Magento\Catalog\Model\Product;

class VendorSelection extends Template
{
    protected $vendorProductCollectionFactory;
    protected $product;

    public function __construct(
        Context $context,
        VendorProductCollectionFactory $vendorProductCollectionFactory,
        array $data = []
    ) {
        $this->vendorProductCollectionFactory = $vendorProductCollectionFactory;
        parent::__construct($context, $data);
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    public function getSimilarProducts()
    {
        if (!$this->product) {
            return [];
        }

        $collection = $this->vendorProductCollectionFactory->create();
        $collection->addFieldToFilter('product_id', $this->product->getId())
                  ->addFieldToSelect(['vendor_id', 'price'])
                  ->join(
                      ['vendor' => 'vendor_profile'],
                      'main_table.vendor_id = vendor.vendor_id',
                      ['name', 'shop_name', 'status']
                  )
                  ->addFieldToFilter('vendor.status', 'approved');

        return $collection;
    }
} 