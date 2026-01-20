<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="image/logo/logo.jpg" alt="Gym Logo" class="logo-img">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">Services</a>
                </li>
                
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Product
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="protein.php">Protein</a></li>
                        <!-- <li><a class="dropdown-item" href="equipment.php">Equipment</a></li> -->
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="membership.php">Membership</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link register-btn" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    body {
        padding-top: 76px;
        margin: 0;
    }

    .navbar {
        background-color: #1a1a1a;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .logo-img {
        height: 90px;
        width: 90px;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
        transition: transform 0.3s ease;
    }

    .logo-img:hover {
        transform: scale(1.05);
    }

    .nav-link {
        color: #fff !important;
        font-weight: 500;
        padding: 0.5rem 1rem !important;
        margin: 0 0.2rem;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        color: #0d6efd !important;
    }

    .dropdown-menu {
        background-color: #222;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .dropdown-item {
        color: #fff;
        padding: 0.8rem 1.5rem;
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background-color: rgba(13,110,253,0.1);
        color: #0d6efd;
    }

    .register-btn {
        background-color: #0d6efd;
        border-radius: 5px;
        padding: 0.5rem 1.2rem !important;
        transition: all 0.3s ease;
    }

    .register-btn:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
    }

    @media screen and (max-width: 991px) {
        .navbar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.5rem 0;
        }

        .register-btn {
            display: inline-block;
            margin-top: 0.5rem;
        }
    }
</style>
