<?php include 'sidebar.php'; ?>
<?php include_once 'db_connection.php'; ?>

<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ADD PAYMENT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    $stmt = $conn->prepare("INSERT INTO payments (member_name, membership_plan, amount, payment_method, payment_date, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssss", $_POST['member_name'], $_POST['membership_plan'], $_POST['amount'], $_POST['payment_method'], $_POST['payment_date'], $_POST['notes'], $status);
    $status = "Completed";
    $stmt->execute();
?>
<script>
    Window.location.href='payment.php';
</script>
<?php
    // header("Location: ".$_SERVER['PHP_SELF']); exit();
}

// EDIT PAYMENT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_payment'])) {
    $stmt = $conn->prepare("UPDATE payments SET member_name = ?, membership_plan = ?, amount = ?, payment_method = ?, payment_date = ?, notes = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssdssssi", $_POST['member_name'], $_POST['membership_plan'], $_POST['amount'], $_POST['payment_method'], $_POST['payment_date'], $_POST['notes'], $_POST['status'], $_POST['id']);
    $stmt->execute();
    ?>
<script>
    Window.location.href='payment.php';
</script>
<?php
}

// DELETE PAYMENT
if (isset($_GET['delete_id'])) {
    $stmt = $conn->prepare("DELETE FROM payments WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete_id']);
    $stmt->execute();
    ?>
    <script>
        Window.location.href='payment.php';
    </script>
    <?php
}

// FETCH PAYMENT DATA
$search = isset($_GET['search']) ? "%".$_GET['search']."%" : "%";
$stmt = $conn->prepare("SELECT * FROM payments WHERE member_name LIKE ? OR membership_plan LIKE ? OR payment_method LIKE ? ORDER BY created_at DESC");
$stmt->bind_param("sss", $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <main class="col-12 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Payment Membership Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i class="fas fa-plus me-2"></i>Record Payment
                    </button>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-12">
                    <form action="" method="get" class="d-flex justify-content-center">
                        <div class="input-group w-50">
                            <input type="text" class="form-control" placeholder="Search payments..." name="search" value="<?= htmlspecialchars($search) ?>">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Table -->
            <div class="card border-0 shadow">
                <div class="card-header bg-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Recent Payments</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Member Name</th>
                                    <th>Membership Plan</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['member_name']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($row['membership_plan'])) ?></td>
                                    <td><?= $row['payment_date'] ?></td>
                                    <td>₹<?= number_format($row['amount'], 2) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $row['status'] === 'Completed' ? 'success' : 'warning' ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                                    <td>
                                        <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this payment?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="" id="paymentForm" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Record New Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Member Name</label>
                        <input type="text" class="form-control" name="member_name" id="memberName" required>
                        <div class="invalid-feedback" id="memberError">Please enter member name</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Membership Plan</label>
                        <select class="form-select" name="membership_plan" id="membershipPlan" required>
                            <option value="">Select Membership Plan</option>
                            <option value="basic">Basic</option>
                            <option value="premium">Premium</option>
                            <option value="vip">VIP</option>
                        </select>
                        <div class="invalid-feedback" id="membershipPlanError">Please select a membership plan</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" name="amount" id="amount" step="0.01" required min="1">
                        </div>
                        <div class="invalid-feedback" id="amountError">Please enter a valid amount</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method" id="paymentMethod" required>
                            <option value="">Select Payment Method</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash">Cash</option>
                        </select>
                        <div class="invalid-feedback" id="paymentMethodError">Please select a payment method</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" class="form-control" name="payment_date" id="paymentDate" required>
                        <div class="invalid-feedback" id="paymentDateError">Please select a payment date</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="add_payment" id="submitBtn">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.invalid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.is-invalid:focus {
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    
    // Real-time validation on input change
    form.querySelectorAll('input, select').forEach(element => {
        element.addEventListener('input', function() {
            validateField(this);
        });
    });
    
    // Handle form submit
    form.addEventListener('submit', function(e) {
        let isValid = true;

        form.querySelectorAll('[required]').forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault(); // block submission if invalid
        }
    });
    
    // Field validation function
    function validateField(field) {
        const errorElement = field.nextElementSibling;

        if (field.type === 'select-one' && field.value === '') {
            field.classList.add('is-invalid');
            errorElement.style.display = 'block';
            return false;
        }

        if (field.required && !field.value.trim()) {
            field.classList.add('is-invalid');
            errorElement.style.display = 'block';
            return false;
        }

        if (field.id === 'amount' && (field.value <= 0 || isNaN(field.value))) {
            field.classList.add('is-invalid');
            errorElement.textContent = 'Please enter a valid amount';
            errorElement.style.display = 'block';
            return false;
        }

        if (field.id === 'paymentDate' && new Date(field.value) > new Date()) {
            field.classList.add('is-invalid');
            errorElement.textContent = 'Payment date cannot be in the future';
            errorElement.style.display = 'block';
            return false;
        }

        field.classList.remove('is-invalid');
        errorElement.style.display = 'none';
        return true;
    }
});
</script>
