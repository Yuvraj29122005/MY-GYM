<?php include 'masterpage/header.php'; ?>

<div class="checkout-container py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-5">Checkout</h1>
            </div>

            <div class="col-lg-8">
                <div class="checkout-form bg-white p-4 rounded-lg shadow mb-4">
                    <h3 class="mb-4">Billing Details</h3>

                    <form id="checkout-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName">
                                <div class="invalid-feedback">Please enter your first name</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName">
                                <div class="invalid-feedback">Please enter your last name</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email">
                                <div class="invalid-feedback">Please enter a valid email address</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" id="address">
                                <div class="invalid-feedback">Please enter your address</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" id="city">
                                <div class="invalid-feedback">Please enter your city</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="postalCode">
                                <div class="invalid-feedback">Please enter your postal code</div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
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

                    <div class="total-amount border-top pt-3 mb-4">
                        <div class="d-flex justify-content-between">
                            <h4>Total</h4>
                            <h4 class="text-primary">₹4,648</h4>
                        </div>
                    </div>

                    <button id="rzp-button" class="btn btn-primary btn-lg w-100">Pay Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-container {
    background-color: #f8f9fa;
    min-height: calc(100vh - 100px);
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15);
}

.form-control.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
}

.form-control.is-invalid ~ .invalid-feedback {
    display: block;
}

.btn-primary {
    padding: 12px 24px;
    font-weight: 500;
    transition: all 0.3s ease;
    background-color: #2d88ff;
    border-color: #2d88ff;
}

.btn-primary:hover {
    background-color: #1a73e8;
    border-color: #1a73e8;
    transform: translateY(-1px);
}

.sticky-top {
    top: 20px;
}

.rounded-lg {
    border-radius: 0.5rem;
}

#validation-alert {
    border-left: 4px solid #dc3545;
    background-color: #f8d7da;
    color: #842029;
    padding: 1rem;
    margin-bottom: 1.5rem;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}
</style>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function validateField(field, message) {
    if (!field.value.trim()) {
        field.classList.add('is-invalid');
        return false;
    }
    field.classList.remove('is-invalid');
    return true;
}

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value.trim())) {
        email.classList.add('is-invalid');
        return false;
    }
    email.classList.remove('is-invalid');
    return true;
}

document.getElementById('rzp-button').addEventListener('click', function(e) {
    e.preventDefault();
    
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const email = document.getElementById('email');
    const address = document.getElementById('address');
    const city = document.getElementById('city');
    const postalCode = document.getElementById('postalCode');

    let isValid = true;

    // Validate each field
    if (!validateField(firstName)) isValid = false;
    if (!validateField(lastName)) isValid = false;
    if (!validateField(address)) isValid = false;
    if (!validateField(city)) isValid = false;
    if (!validateField(postalCode)) isValid = false;
    if (!validateEmail(email)) isValid = false;

    if (!isValid) {
        // Scroll to first invalid field
        const firstInvalid = document.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return;
    }
    
    var options = {
        "key": "rzp_test_GDMFMRAC3bnneR",
        "amount": "464800",
        "currency": "INR",
        "name": "Fitness Store",
        "description": "Purchase from Fitness Store",
        "handler": function (response) {
            const alertDiv = document.getElementById('validation-alert');
            alertDiv.classList.remove('d-none', 'alert-danger');
            alertDiv.classList.add('alert-success');
            alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Payment successful! Payment ID: ' + response.razorpay_payment_id;
        },
        "prefill": {
            "name": firstName.value.trim() + ' ' + lastName.value.trim(),
            "email": email.value.trim()
        },
        "theme": {
            "color": "#2d88ff"
        }
    };
    
    var rzp = new Razorpay(options);
    rzp.open();
});
</script>
