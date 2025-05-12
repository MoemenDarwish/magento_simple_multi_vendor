<?php
namespace Vendor\MultiVendor\Block\Vendor;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Vendor\MultiVendor\Model\VendorFactory;
use Magento\Customer\Model\Session as CustomerSession;

class Dashboard extends Template
{
    /**
     * @var VendorFactory
     */
    protected $vendorFactory;
    
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    
    /**
     * Constructor
     *
     * @param Context $context
     * @param VendorFactory $vendorFactory
     * @param CustomerSession $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        VendorFactory $vendorFactory,
        CustomerSession $customerSession,
        array $data = []
    ) {
        $this->vendorFactory = $vendorFactory;
        $this->customerSession = $customerSession;
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
} 