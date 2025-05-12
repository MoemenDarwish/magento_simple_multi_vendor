<?php
namespace Vendor\MultiVendor\Controller\Vendor;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Vendor\MultiVendor\Model\VendorFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;

class Save implements HttpPostActionInterface
{
    /**
     * @var FormKeyValidator
     */
    protected $formKeyValidator;
    
    /**
     * @var RequestInterface
     */
    protected $request;
    
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    
    /**
     * @var VendorFactory
     */
    protected $vendorFactory;
    
    /**
     * @var ManagerInterface
     */
    protected $messageManager;
    
    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;
    
    /**
     * Constructor
     *
     * @param Context $context
     * @param FormKeyValidator $formKeyValidator
     * @param RequestInterface $request
     * @param CustomerSession $customerSession
     * @param VendorFactory $vendorFactory
     * @param ManagerInterface $messageManager
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        Context $context,
        FormKeyValidator $formKeyValidator,
        RequestInterface $request,
        CustomerSession $customerSession,
        VendorFactory $vendorFactory,
        ManagerInterface $messageManager,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->formKeyValidator = $formKeyValidator;
        $this->request = $request;
        $this->customerSession = $customerSession;
        $this->vendorFactory = $vendorFactory;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }
    
    /**
     * Save vendor registration
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if (!$this->customerSession->isLoggedIn()) {
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        
        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addErrorMessage(__('Invalid form key. Please try again.'));
            $resultRedirect->setPath('multivendor/vendor/register');
            return $resultRedirect;
        }
        
        $data = $this->request->getPostValue();
        
        try {
            $customerId = $this->customerSession->getCustomerId();
            
            // Check if vendor already exists
            $vendor = $this->vendorFactory->create()
                ->getCollection()
                ->addFieldToFilter('customer_id', $customerId)
                ->getFirstItem();
                
            if ($vendor->getId()) {
                $this->messageManager->addErrorMessage(__('You are already registered as a vendor.'));
                $resultRedirect->setPath('multivendor/vendor/dashboard');
                return $resultRedirect;
            }
            
            // Create new vendor
            $vendor = $this->vendorFactory->create();
            $vendor->setData([
                'name' => $data['name'] ?? '',
                'email' => $data['email'] ?? '',
                'shop_name' => $data['shop_name'] ?? '',
                'shop_url' => $data['shop_url'] ?? '',
                'description' => $data['description'] ?? '',
                'customer_id' => $customerId,
                'status' => 'pending'
            ]);
            $vendor->save();
            
            $this->messageManager->addSuccessMessage(__('Your vendor application has been submitted successfully and is pending approval.'));
            $resultRedirect->setPath('customer/account');
            
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect->setPath('multivendor/vendor/register');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while saving your vendor information. Please try again.'));
            $resultRedirect->setPath('multivendor/vendor/register');
        }
        
        return $resultRedirect;
    }
} 