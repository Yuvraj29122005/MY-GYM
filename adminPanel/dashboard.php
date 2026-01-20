<?php include 'sidebar.php'; ?>
<?php include_once 'db_connection.php'; ?>

<?php
// Fetch total users count
$users_query = "SELECT COUNT(*) as total_users FROM users";
$users_result = $conn->query($users_query);
$users_count = $users_result->fetch_assoc()['total_users'];

// Fetch total trainers count
$trainers_query = "SELECT COUNT(*) as total_trainers FROM trainers";
$trainers_result = $conn->query($trainers_query);
$trainers_count = $trainers_result->fetch_assoc()['total_trainers'];

// Fetch total products count (protein + equipment)
$products_query = "SELECT (SELECT COUNT(*) FROM products) + (SELECT COUNT(*) FROM equipment) as total_products";
$products_result = $conn->query($products_query);
$products_count = $products_result->fetch_assoc()['total_products'];
?>

<div class="container-fluid">
    <div class="d-flex justify-content-center flex-wrap gap-4 mt-4">

        <!-- Users Card -->
        <div class="card border-0 shadow-lg text-center p-4" style="width: 320px;">
            <div class="card-body">
                <div class="stat-icon bg-info bg-opacity-10 rounded-circle p-4 mx-auto">
                    <i class="fas fa-user text-info fa-3x"></i>
                </div>
                <h5 class="text-muted mt-3">Total Users</h5>
                <h2 class="fw-bold"><?php echo $users_count; ?></h2>
            </div>
        </div>

        <!-- Products Card -->
        <div class="card border-0 shadow-lg text-center p-4" style="width: 320px;">
            <div class="card-body">
                <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-4 mx-auto">
                    <i class="fas fa-box text-success fa-3x"></i>
                </div>
                <h5 class="text-muted mt-3">Total Products</h5>
                <h2 class="fw-bold"><?php echo $products_count; ?></h2>
            </div>
        </div>

        <!-- Trainers Card -->
        <div class="card border-0 shadow-lg text-center p-4" style="width: 320px;">
            <div class="card-body">
                <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-4 mx-auto">
                    <i class="fas fa-chalkboard-teacher text-warning fa-3x"></i>
                </div>
                <h5 class="text-muted mt-3">Total Trainers</h5>
                <h2 class="fw-bold"><?php echo $trainers_count; ?></h2>
            </div>
        </div>

    </div>
</div>

<!-- Custom CSS for Styling -->
<style>
    .stat-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card {
        transition: transform 0.3s ease-in-out;
        border-radius: 15px;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    @media (max-width: 768px) {
        .d-flex {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
