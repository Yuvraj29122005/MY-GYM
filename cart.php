<?php include 'masterpage/header.php'; ?>

<div class="cart-container py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-5">Your Shopping Cart</h1>
            </div>
            
            <div class="col-lg-9">
                <div class="cart-items-container bg-white p-4 rounded-lg shadow mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="m-0">Cart Items</h3>
                        <span class="text-muted">1 Item(s)</span>
                    </div>

                    <div class="cart-item d-flex align-items-center p-4 border rounded mb-3">
                        <div class="cart-item-image me-4">
                            <img src="image/products/protein1.jpg" alt="Whey Protein" class="img-fluid rounded">
                        </div>
                        <div class="cart-item-details flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h4 class="mb-0">Whey Protein Isolate</h4>
                                <h4 class="text-primary mb-0">₹4,149</h4>
                            </div>
                            <p class="text-success mb-2"><i class="fas fa-check-circle me-2"></i>In Stock</p>
                            <div class="product-meta mb-3">
                                <span class="text-muted me-3">Size: 5 lbs</span>
                                <span class="text-muted">SKU: WPI-5LB</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="quantity-selector me-4 d-flex align-items-center">
                                    <button class="btn btn-outline-secondary btn-sm me-2" onclick="decrementQuantity()">-</button>
                                    <input type="number" class="form-control form-control-sm text-center" style="width: 60px;" value="1" min="1" max="5" id="quantity">
                                    <button class="btn btn-outline-secondary btn-sm ms-2" onclick="incrementQuantity()">+</button>
                                </div>
                                <button class="btn btn-link text-danger p-0 me-3">Remove</button>
                                
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>

            <div class="col-lg-3">
                <div class="order-summary bg-white p-4 rounded-lg shadow sticky-top">
                    <h3 class="border-bottom pb-3 mb-3">Order Summary</h3>
                    
                    <div class="summary-item d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>₹4,149</span>
                    </div>
                    <div class="summary-item d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span>₹499</span>
                    </div>
                    <div class="summary-item d-flex justify-content-between mb-3">
                        <span class="text-muted">Tax</span>
                        <span>₹0.00</span>
                    </div>

                    <div class="promo-code mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Promo Code">
                            <button class="btn btn-outline-primary">Apply</button>
                        </div>
                    </div>

                    <div class="total-amount border-top pt-3 mb-4">
                        <div class="d-flex justify-content-between">
                            <h4>Total</h4>
                            <h4 class="text-primary">₹4,648</h4>
                        </div>
                    </div>

                    <a href="checkout.php"><button class="btn btn-primary btn-lg w-100 mb-3">Proceed to Checkout</button></a>
                    <button class="btn btn-outline-secondary w-100">Continue Shopping</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cart-container {
    background-color: #f8f9fa;
    min-height: calc(100vh - 100px);
}

.cart-item-image {
    width: 150px;
    min-width: 150px;
}

.cart-item-image img {
    max-width: 100%;
    height: auto;
}

.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.form-select:focus,
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15);
}

.btn-primary {
    padding: 12px 24px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    transform: translateY(-1px);
}

.sticky-top {
    top: 20px;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.btn-link {
    text-decoration: none;
}

.btn-link:hover {
    text-decoration: underline;
}

/* Added styles for quantity controls */
.quantity-selector input[type="number"]::-webkit-inner-spin-button,
.quantity-selector input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-selector input[type="number"] {
    -moz-appearance: textfield;
}
</style>

<script>
function incrementQuantity() {
    var input = document.getElementById('quantity');
    var value = parseInt(input.value, 10);
    if (value < parseInt(input.max)) {
        input.value = value + 1;
    }
}

function decrementQuantity() {
    var input = document.getElementById('quantity');
    var value = parseInt(input.value, 10);
    if (value > parseInt(input.min)) {
        input.value = value - 1;
    }
}
</script>

<?php include 'masterpage/footer.php'; ?>
