<footer class="footer bg-dark text-light py-5">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h5 class="text-uppercase mb-4 font-weight-bold">About FitLife Gym</h5>
                <p class="mb-4">Transform your life with our state-of-the-art facilities, expert trainers, and supportive community. Your fitness journey starts here.</p>
                <div class="social-links">
                    <a href="#" class="me-3 text-light"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="me-3 text-light"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="me-3 text-light"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-uppercase mb-4 font-weight-bold">Quick Links</h5>
                <ul class="list-unstyled">
                    
                    <li class="mb-2"><a href="classes.php" class="text-light text-decoration-none">Classes</a></li>
                    <li class="mb-2"><a href="protein.php" class="text-light text-decoration-none">Protein</a></li>
                    <li class="mb-2"><a href="equipment.php" class="text-light text-decoration-none">Equipment</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="text-uppercase mb-4 font-weight-bold">Services</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="services.php" class="text-light text-decoration-none">Personal Training</a></li>
                    <li class="mb-2"><a href="services.php" class="text-light text-decoration-none">Group Classes</a></li>
                    <li class="mb-2"><a href="services.php" class="text-light text-decoration-none">Nutrition Plans</a></li>
                    <li class="mb-2"><a href="services.php" class="text-light text-decoration-none">Weight Loss</a></li>
                    <li class="mb-2"><a href="services.php" class="text-light text-decoration-none">Yoga Classes</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4 col-md-6">
                <h5 class="text-uppercase mb-4 font-weight-bold">Contact Us</h5>
                <p class="mb-3">
                    <i class="fas fa-location-dot me-3"></i>
                    123 Fitness Street, Gym City, GC 12345
                </p>
                <p class="mb-3">
                    <i class="fas fa-envelope me-3"></i>
                    krishhalai83@gmail.com
                </p>
                <p class="mb-3">
                    <i class="fas fa-phone me-3"></i>
                    +91 9712102682
                </p>
                <p>
                    <i class="fas fa-clock me-3"></i>
                    Mon-Sat: 6:00 AM - 10:00 PM<br>
                    Sunday: 8:00 AM - 8:00 PM
                </p>
            </div>
        </div>
    </div>

    <!-- Copyright -->
  
</footer>

<style>
    .footer {
        position: relative;
        bottom: 0;
        width: 100%;
    }

    .footer h5 {
        position: relative;
        padding-bottom: 12px;
    }

    .footer h5::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 2px;
        background: #007bff;
    }

    .footer ul li a {
        transition: all 0.3s ease;
        position: relative;
        padding-left: 15px;
    }

    .footer ul li a:before {
        content: '›';
        position: absolute;
        left: 0;
        color: #007bff;
    }

    .footer ul li a:hover {
        color: #007bff !important;
        padding-left: 20px;
    }

    .social-links a {
        display: inline-block;
        width: 35px;
        height: 35px;
        background: rgba(255,255,255,0.1);
        text-align: center;
        line-height: 35px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .social-links a:hover {
        background: #007bff;
        transform: translateY(-3px);
    }

    .fas, .fab {
        transition: all 0.3s ease;
    }

    .footer p:hover .fas,
    .footer p:hover .fab {
        color: #007bff;
    }
</style>
