<?php include 'masterpage/header.php'; ?>
<?php include 'masterpage/slider.php'; ?>
<section class="about-us py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold mb-4">About Our Gym</h2>
                <p class="lead mb-4">Welcome to FitLife Gym, where fitness meets lifestyle. We're dedicated to helping you achieve your health and fitness goals in a supportive and motivating environment.</p>
                <p class="mb-4">With state-of-the-art equipment, expert trainers, and a wide range of classes, we provide everything you need to transform your life. Our facility spans over 20,000 square feet of premium fitness space designed to inspire and energize.</p>
                <div class="d-flex gap-4 mb-4">
                    <div class="text-center">
                        <h3 class="fw-bold text-primary">10+</h3>
                        <p class="text-muted">Expert Trainers</p>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold text-primary">50+</h3>
                        <p class="text-muted">Fitness Classes</p>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold text-primary">1000+</h3>
                        <p class="text-muted">Happy Members</p>
                    </div>
                </div>
                <a href="membership.php" class="btn btn-primary btn-lg">Join Us Today</a>
            </div>
            <div class="col-md-6">
                <div class="position-relative">
                    <img src="image/about/about.jpg" alt="Gym Interior" class="img-fluid rounded shadow-lg " oncontextmenu="return false;" draggable="false" style="pointer-events: none;">
                    
                </div>
            </div>
        </div>

    </div>
</section>

<style>
.about-us {
    background-color: #f8f9fa;
}

.about-us h2 {
    color: #333;
    position: relative;
}

.about-us h2:after {
    content: '';
    display: block;
    width: 50px;
    height: 3px;
    background: #0d6efd;
    margin-top: 10px;
}

.about-us p {
    color: #6c757d;
    line-height: 1.8;
}

.about-us .btn-primary {
    padding: 12px 30px;
    border-radius: 30px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 1px;
}

.about-us img {
    transition: transform 0.3s ease;
}

.about-us img:hover {
    transform: scale(1.02);
}

@media (max-width: 768px) {
    .about-us .position-absolute {
        position: relative !important;
        width: 100%;
        border-radius: 0 !important;
    }
}
</style>


<section class="services py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="fw-bold">Our Services</h2>
                <p class="text-muted">Discover our premium fitness services designed for your success</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="service-card h-100 p-4 text-center">
                    <div class="service-icon mb-4">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h4 class="mb-3">Personal Training</h4>
                    <p class="text-muted">Customized workout plans and one-on-one guidance from expert trainers</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card h-100 p-4 text-center">
                    <div class="service-icon mb-4">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4 class="mb-3">Group Classes</h4>
                    <p class="text-muted">Energetic group sessions including yoga, HIIT, and spinning classes</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card h-100 p-4 text-center">
                    <div class="service-icon mb-4">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h4 class="mb-3">Product selling</h4>
                    <p class="text-muted">Expert dietary guidance and customized meal plans for optimal results</p>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
.services {
    background-color: #ffffff;
}

.services h2 {
    color: #333;
    position: relative;
    margin-bottom: 1rem;
}

.services h2:after {
    content: '';
    display: block;
    width: 50px;
    height: 3px;
    background: #0d6efd;
    margin: 15px auto;
}

.service-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.service-icon {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    transition: all 0.3s ease;
}

.service-icon i {
    font-size: 2rem;
    color: #0d6efd;
}

.service-card:hover .service-icon {
    background: #0d6efd;
}

.service-card:hover .service-icon i {
    color: #fff;
}

.service-card h4 {
    color: #333;
    font-weight: 600;
}

.service-card p {
    font-size: 0.95rem;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .service-card {
        margin-bottom: 1rem;
    }
}
</style>


<div class="container-fluid mt-5" style="background-color:rgb(214, 235, 255);">
    <div class="row p-5">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">BMI Calculator</h3>
                </div>
                <div class="card-body">
                    <form id="bmiForm" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select" id="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    <label for="gender">Gender*</label>
                                    <div class="invalid-feedback">
                                        Please select your gender
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="weight" required step="0.1" min="20" max="300" placeholder="Weight">
                                    <label for="weight">Weight (kg)*</label>
                                    <div class="invalid-feedback">
                                        Please enter a valid weight between 20-300 kg
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="heightFeet" required min="4" max="8" placeholder="Height in Feet">
                                    <label for="heightFeet">Height (Feet)*</label>
                                    <div class="invalid-feedback">
                                        Please enter a valid height between 4-8 feet
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="heightInches" required min="0" max="11" placeholder="Height in Inches">
                                    <label for="heightInches">Height (Inches)*</label>
                                    <div class="invalid-feedback">
                                        Please enter valid inches between 0-11
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Calculate BMI</button>
                    </form>
                    
                    <div id="result" class="mt-4" style="display: none;">
                        <div class="alert alert-info">
                            <h4 class="alert-heading">Your BMI Result</h4>
                            <p class="mb-0">Your BMI: <span id="bmiValue"></span></p>
                            <p class="mb-0">Category: <span id="bmiCategory"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">BMI Categories</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>BMI Range</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Below 18.5</td>
                                <td>Underweight</td>
                            </tr>
                            <tr>
                                <td>18.5 - 24.9</td>
                                <td>Normal weight</td>
                            </tr>
                            <tr>
                                <td>25.0 - 29.9</td>
                                <td>Overweight</td>
                            </tr>
                            <tr>
                                <td>30.0 and above</td>
                                <td>Obese</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
  'use strict'

  const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      } else {
        event.preventDefault()
        
        const gender = document.getElementById('gender').value
        const weight = parseFloat(document.getElementById('weight').value)
        const heightFeet = parseInt(document.getElementById('heightFeet').value)
        const heightInches = parseInt(document.getElementById('heightInches').value)
        
        const totalInches = (heightFeet * 12) + heightInches
        const heightInMeters = totalInches * 0.0254
        
        const bmi = weight / (heightInMeters * heightInMeters)
        const bmiRounded = bmi.toFixed(1)
        
        let category
        if (gender === 'male') {
            if (bmi < 18.5) category = 'Underweight'
            else if (bmi < 25) category = 'Normal weight'
            else if (bmi < 30) category = 'Overweight'
            else category = 'Obese'
        } else {
            if (bmi < 18.5) category = 'Underweight'
            else if (bmi < 24) category = 'Normal weight'
            else if (bmi < 29) category = 'Overweight'
            else category = 'Obese'
        }
        
        document.getElementById('bmiValue').textContent = bmiRounded
        document.getElementById('bmiCategory').textContent = category
        document.getElementById('result').style.display = 'block'
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
</script>

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    padding: 10px;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
}

#result {
    transition: all 0.3s ease;
}

.alert-info {
    background-color: #f8f9fa;
    border-color: #007bff;
    color: #0c5460;
}

.table {
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
}

.table td, .table th {
    vertical-align: middle;
}

.form-control.is-invalid,
.was-validated .form-control:invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-control.is-valid,
.was-validated .form-control:valid {
    border-color: #198754;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-select.is-invalid,
.was-validated .form-select:invalid {
    border-color: #dc3545;
    padding-right: 4.125rem;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"), url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-position: right 0.75rem center, center right 2.25rem;
    background-size: 16px 12px, calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-select.is-valid,
.was-validated .form-select:valid {
    border-color: #198754;
    padding-right: 4.125rem;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"), url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-position: right 0.75rem center, center right 2.25rem;
    background-size: 16px 12px, calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
</style>

<section class="trainers-section py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title">Our Expert Trainers</h2>
                <p class="section-subtitle text-muted">Meet our professional fitness experts who will guide you through your fitness journey</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="trainer-card">
                    <div class="trainer-img-wrapper">
                        <img src="image/trainers/t11.jpg" alt="Trainer" class="trainer-img">
                        <div class="trainer-overlay">
                            <div class="trainer-social">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="trainer-info p-4">
                        <h4 class="trainer-name">John Doe</h4>
                        <p class="trainer-specialty">Strength & Conditioning</p>
                        <p class="trainer-description">Certified personal trainer with 5+ years of experience in strength training and muscle building.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="trainer-card">
                    <div class="trainer-img-wrapper">
                        <img src="image/trainers/t22.jpg" alt="Trainer" class="trainer-img">
                        <div class="trainer-overlay">
                            <div class="trainer-social">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="trainer-info p-4">
                        <h4 class="trainer-name">Sarah Johnson</h4>
                        <p class="trainer-specialty">Yoga & Flexibility</p>
                        <p class="trainer-description">Experienced yoga instructor specializing in flexibility and mindfulness training.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="trainer-card">
                    <div class="trainer-img-wrapper">
                        <img src="image/trainers/t33.jpg" alt="Trainer" class="trainer-img">
                        <div class="trainer-overlay">
                            <div class="trainer-social">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="trainer-info p-4">
                        <h4 class="trainer-name">Mike Wilson</h4>
                        <p class="trainer-specialty">Cardio & HIIT</p>
                        <p class="trainer-description">Specialized in high-intensity interval training and cardiovascular fitness programs.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .trainers-section {
        background-color: #f8f9fa;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #333;
    }

    .section-subtitle {
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    .trainer-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .trainer-card:hover {
        transform: translateY(-5px);
    }

    .trainer-img-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 10px 10px 0 0;
    }

    .trainer-img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .trainer-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,123,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .trainer-img-wrapper:hover .trainer-overlay {
        opacity: 1;
    }

    .trainer-social a {
        color: white;
        margin: 0 10px;
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }

    .trainer-social a:hover {
        transform: scale(1.2);
    }

    .trainer-info {
        text-align: center;
    }

    .trainer-name {
        color: #333;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .trainer-specialty {
        color: #007bff;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .trainer-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.6;
    }
</style>


<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "krish");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch protein products (using product_name as name)
$protein_products = $conn->query("SELECT id, product_name as name,image FROM products ");

// Fetch equipment products
$equipment_products = $conn->query("SELECT id, name,  image FROM equipment ");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #gymProductCarousel .carousel-item {
            padding: 2rem;
        }
        
        .gym-product-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            margin: 1rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .gym-product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .product-image {
            position: relative;
            overflow: hidden;
            height: 250px;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .gym-product-card:hover .product-image img {
            transform: scale(1.1);
        }
        
        .product-details {
            padding: 1.5rem;
            text-align: center;
        }
        
        .product-details h5 {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .product-details p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        .gym-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .gym-btn:hover {
            background: linear-gradient(45deg, #0056b3, #004085);
            color: #fff;
            transform: translateY(-2px);
        }
        
        #gymProductCarousel .carousel-control-prev,
        #gymProductCarousel .carousel-control-next {
            width: 40px;
            height: 40px;
            background: rgba(0,0,0,0.3);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            margin: 0 1rem;
        }
        
        #gymProductCarousel .carousel-control-prev:hover,
        #gymProductCarousel .carousel-control-next:hover {
            background: rgba(0,0,0,0.5);
        }
        
        .no-products {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container-fluid mt-5">
    <h3 class="text-center">Products</h3>
    <div class="row">
        <div class="col-12">
            <div id="gymProductCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <!-- First Slide (Protein Products) -->
                    <div class="carousel-item active">
                        <div class="row justify-content-center">
                            <?php if ($protein_products && $protein_products->num_rows > 0): ?>
                                <?php while($protein = $protein_products->fetch_assoc()): ?>
                                <div class="col-lg-3 col-md-6">
                                    <div class="gym-product-card">
                                        <div class="product-image">
                                            <?php if (!empty($protein['image'])): ?>
                                                <img src="image/products/<?= htmlspecialchars($protein['image']) ?>" alt="<?= htmlspecialchars($protein['name']) ?>">
                                            <?php else: ?>
                                                <img src="image/products/default.jpg" alt="Default product image">
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-details">
                                            <h5><?= htmlspecialchars($protein['name']) ?></h5>
                                            <a href="protein.php?id=<?= $protein['id'] ?>" class="gym-btn">View Details</a>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12 no-products">
                                    <p>No protein products found</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Second Slide (Equipment Products) -->
                    <div class="carousel-item">
                        <div class="row justify-content-center">
                            <?php if ($equipment_products && $equipment_products->num_rows > 0): ?>
                                <?php while($equipment = $equipment_products->fetch_assoc()): ?>
                                <div class="col-lg-3 col-md-6">
                                    <div class="gym-product-card">
                                        <div class="product-image">
                                            <?php if (!empty($equipment['image'])): ?>
                                                <img src="../image/products/<?= htmlspecialchars($equipment['image']) ?>" alt="<?= htmlspecialchars($equipment['name']) ?>">
                                            <?php else: ?>
                                                <img src="../image/products/default.jpg" alt="Default equipment image">
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-details">
                                            <h5><?= htmlspecialchars($equipment['name']) ?></h5>
                                            <a href="equipment.php?id=<?= $equipment['id'] ?>" class="gym-btn">View Details</a>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12 no-products">
                                    <p>No equipment found</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#gymProductCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#gymProductCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize carousel with autoplay
        var carousel = new bootstrap.Carousel(document.querySelector('#gymProductCarousel'), {
            interval: 3000,
            wrap: true
        });
        
        // Pause on hover
        $('#gymProductCarousel').hover(
            function() {
                carousel.pause();
            },
            function() {
                carousel.cycle();
            }
        );
    });
</script>
</body>
</html>
<?php include 'masterpage/footer.php'; ?>

<script>
// Disable right-click on the entire page
document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
});
</script>


