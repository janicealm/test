$(document).ready(function() {
    // Add to cart
    $('.add-to-cart').click(function() {
        let product = $(this).closest('.product');
        let productId = product.data('id');
        let productName = product.data('name');
        let productPrice = product.data('price');

        // AJAX request to add product to cart
        $.ajax({
            url: 'cart.php',
            type: 'POST',
            data: {
                action: 'add',
                id: productId,
                name: productName,
                price: productPrice
            },
            success: function(response) {
                updateCart(response);
            }
        });
    });

    // Remove from cart
    $(document).on('click', '.remove-from-cart', function() {
        let productId = $(this).data('id');

        // AJAX request to remove product from cart
        $.ajax({
            url: 'cart.php',
            type: 'POST',
            data: {
                action: 'remove',
                id: productId
            },
            success: function(response) {
                updateCart(response);
            }
        });
    });

    // Update cart UI dynamically
    function updateCart(cartData) {
        $('#cart-items').empty(); // Clear previous items
        let totalPrice = 0;

        cartData.forEach(function(item) {
            $('#cart-items').append(`
                <li class="cart-item">
                    ${item.name} - $${item.price}
                    <button class="remove-from-cart" data-id="${item.id}">Remove</button>
                </li>
            `);
            totalPrice += parseFloat(item.price);
        });

        $('#total-price').text(totalPrice);
    }
});
