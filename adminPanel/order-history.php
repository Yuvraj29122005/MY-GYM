<?php include 'sidebar.php'; ?>
<?php include_once 'db_connection.php'; ?>


<div class="container-fluid px-0">
    <div class="row mx-0">
        <!-- Main Content -->
        <main class="col-md-12 px-0">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Order History</h1>
            </div>
            <div class="row mb-5">
            <div class="col-12">
                <form action="" method="get" class="d-flex justify-content-center">
                    <div class="input-group w-50">
                        <input type="text" class="form-control" placeholder="Search for equipment..." name="search">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>

            <div class="row mx-0">
                <!-- Orders Table Section -->
                <div class="col-12 px-0">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped w-100">
                                    <thead class="table-primary">
                                        <tr>
                                            <th><i class="fas fa-hashtag me-2"></i>Order ID</th>
                                            <th><i class="fas fa-calendar me-2"></i>Date</th>
                                            <th><i class="fas fa-user me-2"></i>Customer</th>
                                            <th><i class="fas fa-box me-2"></i>Products</th>
                                            <th><i class="fas fa-dollar-sign me-2"></i>Total Amount</th>
                                            <th><i class="fas fa-info-circle me-2"></i>Status</th>
                                            <th><i class="fas fa-cog me-2"></i>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">#ORD-001</td>
                                            <td>2023-08-15</td>
                                            <td>John Doe</td>
                                            <td>Gold Standard Whey, Resistance Band</td>
                                            <td class="fw-bold text-success">$89.99</td>
                                            <td><span class="badge bg-success rounded-pill">Completed</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">#ORD-002</td>
                                            <td>2023-08-14</td>
                                            <td>Jane Smith</td>
                                            <td>Dumbbells Set</td>
                                            <td class="fw-bold text-success">$149.99</td>
                                            <td><span class="badge bg-warning rounded-pill">Pending</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" title="Approve Order">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Reject Order">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>


<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Order Details - #ORD-001</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold"><i class="fas fa-user me-2"></i>Customer Information</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">
                                    <i class="fas fa-user-circle me-2"></i>John Doe<br>
                                    <i class="fas fa-envelope me-2"></i>john@example.com<br>
                                    <i class="fas fa-phone me-2"></i>(555) 123-4567
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold"><i class="fas fa-shopping-cart me-2"></i>Order Information</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">
                                    <i class="fas fa-calendar me-2"></i>Order Date: 2023-08-15<br>
                                    <i class="fas fa-check-circle me-2 text-success"></i>Status: Completed<br>
                                    <i class="fas fa-credit-card me-2"></i>Payment Method: Credit Card
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Gold Standard Whey</td>
                                <td>1</td>
                                <td>$59.99</td>
                                <td>$59.99</td>
                            </tr>
                            <tr>
                                <td>Resistance Band</td>
                                <td>1</td>
                                <td>$30.00</td>
                                <td>$30.00</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                <td class="fw-bold">$89.99</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Tax:</td>
                                <td class="fw-bold">$5.40</td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold">$95.39</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <h6 class="fw-bold"><i class="fas fa-sticky-note me-2"></i>Order Notes</h6>
                    <textarea class="form-control" rows="3" placeholder="Add notes about this order..."></textarea>
                </div>
                <div class="mt-3">
                    <h6 class="fw-bold"><i class="fas fa-history me-2"></i>Order Timeline</h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-transparent">
                                    <i class="fas fa-clock me-2 text-warning"></i>
                                    Order Placed - 2023-08-15 10:30 AM
                                </li>
                                <li class="list-group-item bg-transparent">
                                    <i class="fas fa-check me-2 text-success"></i>
                                    Payment Confirmed - 2023-08-15 10:35 AM
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-success me-2">
                    <i class="fas fa-check me-2"></i>Approve Order
                </button>
                <button type="button" class="btn btn-danger me-2">
                    <i class="fas fa-ban me-2"></i>Reject Order
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>Print Invoice
                </button>
            </div>
        </div>
    </div>
</div>
