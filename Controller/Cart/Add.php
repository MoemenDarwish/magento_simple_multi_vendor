<?php
namespace Vendor\MultiVendor\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;

class Add extends Action
{
    protected $cart;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        Cart $cart,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->cart = $cart;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        
        try {
            $productId = $this->getRequest()->getParam('product_id');
            $vendorId = $this->getRequest()->getParam('vendor_id');
            $qty = $this->getRequest()->getParam('qty', 1);

            if (!$productId || !$vendorId) {
                throw new LocalizedException(__('Invalid product or vendor information.'));
            }

            // Add product to cart with vendor information
            $params = [
                'product' => $productId,
                'qty' => $qty,
                'vendor_id' => $vendorId
            ];

            $this->cart->addProduct($productId, $params);
            $this->cart->save();

            return $resultJson->setData([
                'success' => true,
                'message' => __('Product has been added to your cart.')
            ]);
        } catch (LocalizedException $e) {
            return $resultJson->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return $resultJson->setData([
                'success' => false,
                'message' => __('An error occurred while adding the product to cart.')
            ]);
        }
    }
} 