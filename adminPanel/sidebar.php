<?php
session_start();

// if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
//     // Not logged in as admin
//     header("Location: ../login.php"); // adjust path if needed
//     exit();
// }

// 🚫 Prevent browser from caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>

<?php include_once 'db_connection.php'; ?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->


<nav class="col-md-3 d-none d-md-block bg-dark sidebar min-vh-100">
    <div class="sidebar-sticky p-4">
        <div class="text-center mb-4">
            <h5 class="text-white">GYM ADMIN PANEL</h5>
            <div class="border-top border-secondary my-3"></div>
        </div>
        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center p-3 rounded" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center p-3 rounded" href="user.php">
                    <i class="fas fa-user me-3"></i>
                    <span>User</span>
                </a>
            </li>

           
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center p-3 rounded" href="trainers.php">
                    <i class="fas fa-user-tie me-3"></i>
                    <span>Trainers</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link text-white d-flex align-items-center p-3 rounded" href="#" id="equipmentDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-dumbbell me-3"></i>
                    <span>Products</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="equipmentDropdown">
                    <li>
                        <a class="dropdown-item text-white" href="protein.php">
                            <i class="fas fa-prescription-bottle me-2"></i>
                            Protein
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-white" href="equipment.php">
                            <i class="fas fa-dumbbell me-2"></i>
                            Equipment
                        </a>
                    </li>
                </ul>
            </li>
        
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center p-3 rounded" href="membership.php">
                    <i class="fas fa-id-card me-3"></i>
                    <span>Memberships</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center p-3 rounded" href="payments.php">
                    <i class="fas fa-credit-card me-3"></i>
                    <span>Payments</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center p-3 rounded" href="order-history.php">
                    <i class="fas fa-history me-3"></i>
                    <span>Order History</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center p-3 rounded" href="settings.php">
                    <i class="fas fa-cog me-3"></i>
                    <span>Settings</span>
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link text-white d-flex align-items-center p-3 rounded bg-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt me-3"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<style>
    body
    {
        display: flex;
    }
.sidebar {
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.nav-link {
    transition: all 0.3s;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    margin-bottom: 0.25rem;
}

.nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

.nav-link.active {
    background-color: rgba(255,255,255,0.2);
}

@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        z-index: 1000;
        transition: left 0.3s;
    }
    
    .sidebar.show {
        left: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add active class to current page link
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        if(link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });
});
</script>