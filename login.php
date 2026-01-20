    <?php
    session_start();
    
    $conn = new mysqli("localhost", "root", "", "krish");

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $passwordInput = $_POST['password'];

        if ($email === 'admin@gmail.com' && $passwordInput === 'admin@123') {
            $_SESSION['admin'] = 'admin';
            echo "<script>window.location.href = 'adminPanel/dashboard.php';</script>";
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND status='active'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($passwordInput, $row['password'])) {
                $_SESSION['user'] = $row['fullname'];
                $_SESSION['user_id'] = $row['id'];           // better for querying
                $_SESSION['user_fullname'] = $row['fullname']; // optional, for display
                
                echo "<script>window.location.href = 'user/services.php';</script>";
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Account inactive or not found.";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    <body>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow" style="z-index: 9999;" role="alert">
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="login-container py-5" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('image/loginregistio/l1.jpg'); background-size: cover; background-position: center;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <div class="login-form bg-white p-5 rounded-lg shadow-lg">
                            <div class="text-center mb-5">
                                <h1 class="fw-bold text-primary">Welcome Back</h1>
                                <p class="text-muted">Login to your account</p>
                            </div>

                            <form method="POST" class="needs-validation" novalidate>
                                <div class="form-floating mb-4">
                                    <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                    <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Please enter a valid email address
                                    </div>
                                </div>

                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                                    <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Please enter your password
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember-me">
                                        <label class="form-check-label text-muted" for="remember-me">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="forgot.php" class="text-primary text-decoration-none">Forgot Password?</a>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>

                                <div class="text-center">
                                    <p class="text-muted">Don't have an account? <a href="register.php" class="text-primary text-decoration-none">Sign Up</a></p>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function() {
                'use strict';
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms).forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();
        </script>

        <style>
            .login-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
            }

            .form-floating>label {
                padding-left: 1rem;
            }

            .form-control:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }

            .btn-primary:focus {
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.5);
            }

            .form-check-input:checked {
                background-color: #0d6efd;
                border-color: #0d6efd;
            }

            .form-check-input:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }
        </style>

    </body>

    </html>