<?php
namespace Vendor\MultiVendor\Controller\Vendor;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\RedirectFactory;

class Register implements HttpGetActionInterface
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
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;
    
    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param CustomerSession $customerSession
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CustomerSession $customerSession,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }
    
    /**
     * Vendor registration page
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
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Become a Vendor'));
        
        return $resultPage;
    }
} 