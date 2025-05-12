<?php
namespace Vendor\MultiVendor\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Vendor\MultiVendor\Model\VendorFactory;
use Vendor\MultiVendor\Model\ResourceModel\VendorProduct\CollectionFactory as VendorProductCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Customer\Model\Session as CustomerSession;

class Data extends AbstractHelper
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
     * @var OrderCollectionFactory
     */
    protected $orderCollectionFactory;
    
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    
    /**
     * Constructor
     *
     * @param Context $context
     * @param VendorFactory $vendorFactory
     * @param VendorProductCollectionFactory $vendorProductCollectionFactory
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Context $context,
        VendorFactory $vendorFactory,
        VendorProductCollectionFactory $vendorProductCollectionFactory,
        OrderCollectionFactory $orderCollectionFactory,
        CustomerSession $customerSession
    ) {
        $this->vendorFactory = $vendorFactory;
        $this->vendorProductCollectionFactory = $vendorProductCollectionFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }
    
    /**
     * Check if user is a vendor
     *
     * @param int|null $customerId
     * @return bool
     */
    public function isVendor($customerId = null)
    {
        if ($customerId === null) {
            if (!$this->customerSession->isLoggedIn()) {
                return false;
            }
            $customerId = $this->customerSession->getCustomerId();
        }
        
        $vendor = $this->vendorFactory->create()
            ->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('status', 'approved')
            ->getFirstItem();
            
        return (bool)$vendor->getId();
    }
    
    /**
     * Get vendor by customer ID
     *
     * @param int $customerId
     * @return \Vendor\MultiVendor\Model\Vendor
     */
    public function getVendorByCustomerId($customerId)
    {
        $vendor = $this->vendorFactory->create()
            ->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->getFirstItem();
            
        return $vendor;
    }
    
    /**
     * Get products for vendor
     *
     * @param int $vendorId
     * @return \Vendor\MultiVendor\Model\ResourceModel\VendorProduct\Collection
     */
    public function getVendorProducts($vendorId)
    {
        return $this->vendorProductCollectionFactory->create()
            ->addFieldToFilter('vendor_id', $vendorId);
    }
} 