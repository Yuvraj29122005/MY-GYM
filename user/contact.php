<?php 
include 'masterpage/header.php';
include 'masterpage/slider.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "krish");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create contact_inquiries table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS contact_inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    inquiry_type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($create_table_sql)) {
    die("Error creating table: " . $conn->error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName']; 
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $inquiryType = $_POST['inquiryType'];
    $message = $_POST['message'];

    $sql = "INSERT INTO contact_inquiries (first_name, last_name, email, phone, inquiry_type, message) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $firstName, $lastName, $email, $phone, $inquiryType, $message);
    
    if ($stmt->execute()) {
        echo "<script>
            alert('Your message has been sent successfully!');
            window.location.href = window.location.href; // Refresh page
        </script>";
    } else {
        echo "<script>alert('Error sending message. Please try again.');</script>";
    }
    $stmt->close();
}
?>

<section class="contact py-5">
    <div class="container">
        <!-- Hero Section -->
        <div class="hero-section text-center mb-5">
            <h1 class="display-4 fw-bold">Get In Touch</h1>
            <p class="lead text-muted">We're here to help and answer any questions you might have</p>
            <div class="divider mx-auto"></div>
        </div>

        <!-- Contact Info Cards -->
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="info-card text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4>Visit Us</h4>
                    <p>123 Fitness Street<br>Exercise City, FT 12345</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4>Call Us</h4>
                    <p>Phone: (555) 123-4567<br>Fax: (555) 123-4568</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Email Us</h4>
                    <p>info@fitnessgym.com<br>support@fitnessgym.com</p>
                </div>
            </div>
        </div>

        <!-- Contact Form & Business Hours -->
        <div class="row">
            <div class="col-lg-8">
                <div class="contact-form-card p-4">
                    <h3 class="mb-4">Send Us a Message</h3>
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
                                    <label for="firstName">First Name*</label>
                                    <div class="invalid-feedback">
                                        Please enter your first name
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
                                    <label for="lastName">Last Name*</label>
                                    <div class="invalid-feedback">
                                        Please enter your last name
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                    <label for="email">Email Address*</label>
                                    <div class="invalid-feedback">
                                        Please enter a valid email address
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" pattern="[0-9]{10}" minlength="10" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                    <label for="phone">Phone Number (10 digits)*</label>
                                    <div class="invalid-feedback">
                                        Please enter a valid 10-digit phone number
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <select class="form-select form-select-lg" name="inquiryType" required>
                                <option value="" selected disabled>Select Inquiry Type*</option>
                                <option value="membership">Membership</option>
                                <option value="training">Personal Training</option>
                                <option value="classes">Group Classes</option>
                                <option value="other">Other</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select an inquiry type
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <textarea class="form-control" id="message" name="message" placeholder="Your Message" rows="5" required></textarea>
                                <label for="message">Your Message*</label>
                                <div class="invalid-feedback">
                                    Please enter your message
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="hours-card p-4">
                    <h3 class="mb-4">Business Hours</h3>
                    <ul class="list-unstyled hours-list">
                        <li>
                            <span class="day">Monday - Friday</span>
                            <span class="time">6:00 AM - 10:00 PM</span>
                        </li>
                        <li>
                            <span class="day">Saturday</span>
                            <span class="time">8:00 AM - 8:00 PM</span>
                        </li>
                        <li>
                            <span class="day">Sunday</span>
                            <span class="time">8:00 AM - 8:00 PM</span>
                        </li>
                    </ul>
                    <div class="social-links mt-4">
                        <h5>Follow Us</h5>
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inquiries Table -->
        
    </div>
</section>

<style>
.contact {
    background-color: #f8f9fa;
}

.hero-section h1 {
    color: #1a1a1a;
}

.divider {
    width: 60px;
    height: 3px;
    background: #0d6efd;
    margin-top: 25px;
}

.info-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
    height: 100%;
}

.info-card:hover {
    transform: translateY(-5px);
}

.icon-wrapper {
    width: 70px;
    height: 70px;
    background: #e7f1ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.icon-wrapper i {
    font-size: 1.5rem;
    color: #0d6efd;
}

.contact-form-card, .hours-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.form-control, .form-select {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 5px;
}

.form-control:focus, .form-select:focus {
    box-shadow: none;
    border-color: #0d6efd;
}

.form-control.is-invalid,
.form-select.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-control.is-valid,
.form-select.is-valid {
    border-color: #198754;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.hours-list li {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.hours-list li:last-child {
    border-bottom: none;
}

.social-links {
    text-align: center;
}

.social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    margin: 0 5px;
    color: #0d6efd;
    transition: all 0.3s ease;
}

.social-icon:hover {
    background: #0d6efd;
    color: #fff;
    text-decoration: none;
}

.map-container {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

@media (max-width: 768px) {
    .info-card {
        margin-bottom: 1rem;
    }
    
    .hours-card {
        margin-top: 2rem;
    }
}
</style>

<script>
// Form validation
(function () {
    'use strict'
    
    var forms = document.querySelectorAll('.needs-validation')
    
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>

<?php include 'masterpage/footer.php'; ?>
