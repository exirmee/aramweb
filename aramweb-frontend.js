jQuery(document).ready(function($) {
    if (typeof woo_quantity_control_params !== 'undefined') {
        var minQuantity = parseInt(woo_quantity_control_params.min);
        var maxQuantity = parseInt(woo_quantity_control_params.max);
        var stepQuantity = parseInt(woo_quantity_control_params.step);

        if (!isNaN(minQuantity) && !isNaN(maxQuantity) && !isNaN(stepQuantity)) {
            var quantityInput = $('form.cart .quantity input.qty');

            quantityInput.attr('min', minQuantity);
            quantityInput.attr('max', maxQuantity);
            quantityInput.attr('step', stepQuantity);

            quantityInput.on('change', function() {
                var value = parseInt($(this).val());

                if (value < minQuantity) {
                    $(this).val(minQuantity);
                } else if (value > maxQuantity) {
                    $(this).val(maxQuantity);
                } else if ((value - minQuantity) % stepQuantity !== 0) {
                    var adjustedValue = Math.round((value - minQuantity) / stepQuantity) * stepQuantity + minQuantity;
                    $(this).val(adjustedValue);
                }
            });
        }
    }
});
