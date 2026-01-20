<?php 
include 'masterpage/header.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "krish");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch membership plans from database
$plans_query = "SELECT * FROM membership_plans ORDER BY price ASC";
$plans_result = $conn->query($plans_query);
?>

<section class="membership py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="fw-bold">Membership Plans</h2>
                <p class="text-muted">Choose the perfect membership plan for your fitness journey</p>
            </div>
        </div>

        <div class="row g-4">
            <?php if ($plans_result && $plans_result->num_rows > 0): ?>
                <?php while($plan = $plans_result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="membership-card h-100 p-4">
                            <div class="text-center">
                                <h3 class="plan-name"><?php echo htmlspecialchars($plan['name']); ?></h3>
                                <div class="price">
                                    <span class="currency">₹</span>
                                    <span class="amount"><?php echo number_format($plan['price']); ?></span>
                                    <span class="period">/month</span>
                                </div>
                                <ul class="features-list">
                                    <?php 
                                    $features = explode(',', $plan['features']);
                                    foreach($features as $feature):
                                        $included = strpos($feature, '!') !== 0;
                                        $feature_text = $included ? $feature : substr($feature, 1);
                                    ?>
                                        <li>
                                            <i class="fas fa-<?php echo $included ? 'check' : 'times'; ?>"></i>
                                            <?php echo htmlspecialchars($feature_text); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="checkout.php">
                                    <button class="btn btn-primary btn-lg mt-4 w-100">Select Plan</button>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No membership plans available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.membership {
    background-color: #f8f9fa;
}

.membership h2 {
    color: #333;
    position: relative;
    margin-bottom: 1rem;
}

.membership h2:after {
    content: '';
    display: block;
    width: 50px;
    height: 3px;
    background: #0d6efd;
    margin: 15px auto;
}

.membership-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    position: relative;
    overflow: hidden;
}

.membership-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.plan-name {
    color: #333;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.8rem;
}

.price {
    margin: 1.5rem 0;
    background: linear-gradient(45deg, #f8f9fa, #ffffff);
    padding: 1rem;
    border-radius: 10px;
}

.price .currency {
    font-size: 1.5rem;
    vertical-align: top;
    margin-right: 5px;
    color: #0d6efd;
}

.price .amount {
    font-size: 3.5rem;
    font-weight: 700;
    color: #0d6efd;
    text-shadow: 2px 2px 4px rgba(13, 110, 253, 0.1);
}

.price .period {
    font-size: 1rem;
    color: #6c757d;
}

.features-list {
    list-style: none;
    padding: 0;
    margin: 2rem 0;
    text-align: left;
}

.features-list li {
    margin: 1rem 0;
    color: #666;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.features-list li:hover {
    background: #f8f9fa;
}

.features-list i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.features-list .fa-check {
    color: #28a745;
}

.features-list .fa-times {
    color: #dc3545;
}

.btn-lg {
    padding: 0.8rem 2rem;
    font-weight: 500;
    border-radius: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
}

@media (max-width: 768px) {
    .membership-card {
        margin-bottom: 2rem;
    }
}
</style>

<?php include 'masterpage/footer.php'; ?>
