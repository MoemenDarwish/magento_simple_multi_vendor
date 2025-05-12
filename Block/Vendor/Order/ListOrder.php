<?php
namespace Vendor\MultiVendor\Block\Vendor\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Vendor\MultiVendor\Model\VendorFactory;
use Vendor\MultiVendor\Model\ResourceModel\VendorProduct\CollectionFactory as VendorProductCollectionFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Model\Order\Item as OrderItem;

class ListOrder extends Template
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
     * @var OrderCollectionFactory
     */
    protected $orderCollectionFactory;
    
    /**
     * Constructor
     *
     * @param Context $context
     * @param VendorFactory $vendorFactory
     * @param VendorProductCollectionFactory $vendorProductCollectionFactory
     * @param CustomerSession $customerSession
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        VendorFactory $vendorFactory,
        VendorProductCollectionFactory $vendorProductCollectionFactory,
        CustomerSession $customerSession,
        OrderCollectionFactory $orderCollectionFactory,
        array $data = []
    ) {
        $this->vendorFactory = $vendorFactory;
        $this->vendorProductCollectionFactory = $vendorProductCollectionFactory;
        $this->customerSession = $customerSession;
        $this->orderCollectionFactory = $orderCollectionFactory;
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
     * Get vendor orders
     *
     * @return array
     */
    public function getVendorOrders()
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return [];
        }
        
        // Get all vendor product IDs
        $vendorProductIds = [];
        $vendorProductCollection = $this->vendorProductCollectionFactory->create()
            ->addFieldToFilter('vendor_id', $vendor->getId());
            
        foreach ($vendorProductCollection as $vendorProduct) {
            $vendorProductIds[] = $vendorProduct->getProductId();
        }
        
        if (empty($vendorProductIds)) {
            return [];
        }
        
        // Get orders containing vendor products
        $orders = [];
        $orderCollection = $this->orderCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', ['in' => ['complete', 'processing']]);
            
        foreach ($orderCollection as $order) {
            foreach ($order->getAllItems() as $item) {
                if (in_array($item->getProductId(), $vendorProductIds)) {
                    $orders[$order->getId()] = $order;
                    break;
                }
            }
        }
        
        return $orders;
    }
    
    /**
     * Get vendor items in an order
     *
     * @param \Magento\Sales\Model\Order $order
     * @return array
     */
    public function getVendorItemsInOrder($order)
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return [];
        }
        
        $vendorProductIds = [];
        $vendorProductCollection = $this->vendorProductCollectionFactory->create()
            ->addFieldToFilter('vendor_id', $vendor->getId());
            
        foreach ($vendorProductCollection as $vendorProduct) {
            $vendorProductIds[] = $vendorProduct->getProductId();
        }
        
        $vendorItems = [];
        foreach ($order->getAllItems() as $item) {
            if (in_array($item->getProductId(), $vendorProductIds)) {
                $vendorItems[] = $item;
            }
        }
        
        return $vendorItems;
    }
} 