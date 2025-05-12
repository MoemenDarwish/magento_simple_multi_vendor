<?php
namespace Vendor\MultiVendor\Block\Vendor\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Vendor\MultiVendor\Model\VendorFactory;
use Vendor\MultiVendor\Model\ResourceModel\VendorProduct\CollectionFactory as VendorProductCollectionFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Helper\Image as ImageHelper;

class ListProduct extends Template
{
    /**
     * @var VendorFactory
     */
    protected $vendorFactory;
    
    /**
     * @var VendorProductCollectionFactory
     */
    protected $vendorProductCollectionFactory;
    
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    
    /**
     * @var ProductFactory
     */
    protected $productFactory;
    
    /**
     * @var ImageHelper
     */
    protected $imageHelper;
    
    /**
     * Constructor
     *
     * @param Context $context
     * @param VendorFactory $vendorFactory
     * @param VendorProductCollectionFactory $vendorProductCollectionFactory
     * @param CustomerSession $customerSession
     * @param ProductFactory $productFactory
     * @param ImageHelper $imageHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        VendorFactory $vendorFactory,
        VendorProductCollectionFactory $vendorProductCollectionFactory,
        CustomerSession $customerSession,
        ProductFactory $productFactory,
        ImageHelper $imageHelper,
        array $data = []
    ) {
        $this->vendorFactory = $vendorFactory;
        $this->vendorProductCollectionFactory = $vendorProductCollectionFactory;
        $this->customerSession = $customerSession;
        $this->productFactory = $productFactory;
        $this->imageHelper = $imageHelper;
        parent::__construct($context, $data);
    }
    
    /**
     * Get current vendor
     *
     * @return \Vendor\MultiVendor\Model\Vendor|false
     */
    public function getVendor()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return false;
        }
        
        $customerId = $this->customerSession->getCustomerId();
        $vendor = $this->vendorFactory->create()
            ->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('status', 'approved')
            ->getFirstItem();
            
        return $vendor->getId() ? $vendor : false;
    }
    
    /**
     * Get vendor products
     *
     * @return array
     */
    public function getVendorProducts()
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return [];
        }
        
        $products = [];
        $vendorProductCollection = $this->vendorProductCollectionFactory->create()
            ->addFieldToFilter('vendor_id', $vendor->getId());
            
        foreach ($vendorProductCollection as $vendorProduct) {
            $product = $this->productFactory->create()->load($vendorProduct->getProductId());
            if ($product->getId()) {
                $products[] = $product;
            }
        }
        
        return $products;
    }
    
    /**
     * Get product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getProductImage($product)
    {
        return $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
    }
} 