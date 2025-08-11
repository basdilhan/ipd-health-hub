
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPD Health Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
     
        body {
            padding-top: 70px;
            background-color:lightcyan;
             background-image: url('background1.jpg')"url('background2.jpg')", "url('background3.jpg')";
           
    background-size: cover; 
    background-position: center;
    background-repeat: no-repeat; 
        }
        .navbar {
            background-color: #008b8b;
        }
        .navbar-brand, .navbar-light .navbar-nav .nav-link {
            color: #fff;
        }
        .nav__logo img {
            width: 50px;
            height: 50px;
            vertical-align: middle;
            border-radius: 50%;
            object-fit: cover;
        }
        
      
          .banner {
            position: relative;
            text-align: center;
            padding: 60px 0;
            color: white;
            background-size: cover;
            background-position: center;
            transition: background-image 1.5s ease-in-out;
        }
        .overlay {
            background-color: rgba(0, 139, 139, 0.7);
            padding: 60px 0;
        }
        .animated-title {
            font-size: 48px;
            font-weight: bold;
            animation: fadeIn 1.5s ease-in-out;
        }
        .animated-subtitle {
            font-size: 24px;
            animation: fadeIn 2s ease-in-out;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

    
        .modern-icon {
            background-color: white;
            border: none;
            border-radius: 20px;
            margin: 60px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .modern-icon:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .modern-icon img {
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
            border-radius: 20%;
            transition: transform 0.3s ease;
        }
        .modern-icon:hover img {
            transform: scale(1.1);
        }
        .icon-text {
            font-size: 20px;
            font-weight: 600;
            color: #008b8b;
            margin-top: 15px;
            transition: color 0.3s ease;
        }
        .modern-icon:hover .icon-text {
            color: #00bcd4;
        }

        /* Card Section Styling */
        .card-container {
            margin-top: 30px;
        }
        .card {
            width: 400px;
            height: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 3px solid #ddd;
            border-color: lavender;
            border-radius: 20px;
        }
        .card-img-top {
            width: 200px;
            height: 200px;
            display: block;
            margin: 0 auto;
        }

        /* Footer Styling */
        .footer {
            background-color: #008b8b;
            color: black;
            padding: 20px 0;
            text-align: center;
        }
        .footer a {
            color: black;
            margin: 0 15px;
        }
        #cookieBanner {
    font-size: 14px;
    z-index: 1000;
}
#cookieBanner button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
}

    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="nav__header">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <a class="navbar-brand" href="#">IPD Health Hub <span class="nav__logo"><img src="mm.ICO" alt="logo" /></span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="#">My Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="Contact.html">Contact Us</a></li>
                    <li class="nav-item"><a class="btn btn-primary" id="notifyBtn" href="Notification.html">View Notifications</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle" style="font-size: 1.5em;"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Profile</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Log Out</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Banner -->
    <div class="banner">
        <div class="overlay">
            <h2 class="animated-title">Welcome to IPD Health Hub</h2>
            <p class="animated-subtitle">Get access to our health services and more!</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container text-center section-1">
        <div class="row justify-content-center">
            <div class="col-md-2 modern-icon">
                <a href="DocChannel.php">
                    <img src="icon.PNG" alt="Doctor Channeling">
                    <div class="icon-text">Doctor Channeling</div>
                </a>
            </div>
            <div class="col-md-2 modern-icon">
                <a href="medicine.php">
                    <img src="Medicine.PNG" alt="Medicine Ordering">
                    <div class="icon-text">Medicine To Your Doorstep</div>
                </a>
            </div>
            <div class="col-md-2 modern-icon">
                <a href="bedreserving.php">
                    <img src="bed.png" alt="Bed Reserving">
                    <div class="icon-text">Bed Reserving</div>
                </a>
            </div>
        </div>
    </div>

    <!-- Card Section -->
    <div class="container card-container">
        <h2>Healthcare Blogs</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <img src="health1.jpg" class="card-img-top" alt="Healthcare Blog 1">
                    <div class="card-body">
                        <h5 class="card-title">Understanding Your Health</h5>
                        <p class="card-text">Learn about the basics of maintaining a healthy lifestyle...</p>
                        <a href="#" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <img src="health2.jpg" class="card-img-top" alt="Healthcare Blog 2">
                    <div class="card-body">
                        <h5 class="card-title">Tips for a Balanced Diet</h5>
                        <p class="card-text">Discover how to create a balanced diet with essential nutrients...</p>
                        <a href="#" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <img src="health3.jpg" class="card-img-top" alt="Healthcare Blog 3">
                    <div class="card-body">
                        <h5 class="card-title">Exercise and Fitness</h5>
                        <p class="card-text">Explore different exercises to maintain your fitness...</p>
                        <a href="#" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <img src="health4.jpg" class="card-img-top" alt="Healthcare Blog 4">
                    <div class="card-body">
                        <h5 class="card-title">Mental Health Awareness</h5>
                        <p class="card-text">Understand the importance of mental health and well-being...</p>
                        <a href="#" name="btnlog4" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div id="cookieBanner" style="display: none; position: fixed; bottom: 0; width: 100%; background: #000; color: #fff; padding: 10px; text-align: center;">
    This website uses cookies to ensure you get the best experience. <button onclick="acceptCookies()" class="btn btn-primary">Accept</button>
</div>

</div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <p class="mt-3">&copy; 2024 IPD Health Hub. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

   
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const images = ["url('background1.jpg')", "url('background2.jpg')", "url('background3.jpg')"];
        let currentIndex = 0;

        function changeBackground() {
            const banner = document.querySelector('.banner');
            banner.style.backgroundImage = images[currentIndex];
            currentIndex = (currentIndex + 1) % images.length;
        }

        setInterval(changeBackground, 3000);
 

function showCookieBanner() {
    const banner = document.getElementById('cookieBanner');
    if (!getCookie('cookiesAccepted')) {
        banner.style.display = 'block';
    }
}

// Function to set a cookie
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = `${name}=${value}; expires=${date.toUTCString()}; path=/`;
}

// Function to get a cookie by name
function getCookie(name) {
    const cookieArr = document.cookie.split(';');
    for (let i = 0; i < cookieArr.length; i++) {
        const cookiePair = cookieArr[i].split('=');
        if (name === cookiePair[0].trim()) {
            return decodeURIComponent(cookiePair[1]);
        }
    }
    return null;
}

// Function to delete a cookie by name
function deleteCookie(name) {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
}

// Function to accept cookies
function acceptCookies() {
    setCookie('cookiesAccepted', 'true', 365); // Store acceptance for 1 year
    document.getElementById('cookieBanner').style.display = 'none';
}

// Show the cookie banner on page load
window.onload = showCookieBanner;


// Show the cookie banner on page load
window.onload = showCookieBanner;
   </script>
</body>
</html>
