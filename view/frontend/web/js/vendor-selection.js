define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'mage/url'
], function ($, customerData, urlBuilder) {
    'use strict';

    return function (config) {
        $('.select-vendor').on('click', function (e) {
            e.preventDefault();
            
            var $button = $(this);
            var vendorId = $button.data('vendor-id');
            var productId = $button.data('product-id');
            
            // Add to cart with selected vendor
            $.ajax({
                url: urlBuilder.build('multivendor/cart/add'),
                data: {
                    product_id: productId,
                    vendor_id: vendorId,
                    qty: 1
                },
                type: 'POST',
                dataType: 'json',
                beforeSend: function () {
                    $button.prop('disabled', true);
                },
                success: function (response) {
                    if (response.success) {
                        // Update mini cart
                        customerData.reload(['cart'], true);
                        // Show success message
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert('An error occurred while adding the product to cart.');
                },
                complete: function () {
                    $button.prop('disabled', false);
                }
            });
        });
    };
}); 