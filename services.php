<?php include 'masterpage/header.php'; ?>
<?php include 'masterpage/slider.php'; ?>
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
            <div class="col-md-6 col-lg-4">
                <div class="service-card h-100 p-4 text-center">
                    <div class="service-icon mb-4">
                        <i class="fas fa-running"></i>
                    </div>
                    <h4 class="mb-3">Sports Training</h4>
                    <p class="text-muted">Specialized training programs for athletes and sports enthusiasts</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card h-100 p-4 text-center">
                    <div class="service-icon mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="mb-3">Team Building</h4>
                    <p class="text-muted">Corporate fitness programs and team-building activities</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card h-100 p-4 text-center">
                    <div class="service-icon mb-4">
                        <i class="fas fa-child"></i>
                    </div>
                    <h4 class="mb-3">Kids Fitness</h4>
                    <p class="text-muted">Fun and engaging fitness programs designed specifically for children</p>
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
<?php include 'masterpage/footer.php'; ?>