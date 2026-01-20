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
                    <img src="image/about/about.jpg" alt="Gym Interior" class="img-fluid rounded shadow-lg">
                    
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



<?php include 'masterpage/footer.php'; ?>
