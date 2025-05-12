<?php
namespace Vendor\MultiVendor\Controller\Vendor;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Vendor\MultiVendor\Helper\Data as VendorHelper;
use Magento\Framework\Controller\Result\RedirectFactory;

class Orders implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    
    /**
     * @var VendorHelper
     */
    protected $vendorHelper;
    
    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;
    
    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param CustomerSession $customerSession
     * @param VendorHelper $vendorHelper
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CustomerSession $customerSession,
        VendorHelper $vendorHelper,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->vendorHelper = $vendorHelper;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }
    
    /**
     * Vendor orders page
     *
     * @return \Magento\Framework\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        
        if (!$this->vendorHelper->isVendor()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('multivendor/vendor/register');
            return $resultRedirect;
        }
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Orders'));
        
        return $resultPage;
    }
} 