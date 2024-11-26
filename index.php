<?php
session_start();

//FOR SIGHNING OUT
if (isset($_POST['signOut'])) {
    session_destroy();
    // Redirect 
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['logged_in'] = true;
        $_SESSION['email'] = $email;
        header("Location: index.php");
    } else {
        echo "Invalid email or password.";
    }
}

// REGISTER USER
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Email already taken.";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$pass')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();

//PHP END
?>


<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yun Oh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="style.css" rel="stylesheet">
     <style>
       .modal-content {
            background: linear-gradient(135deg, #3b7a57, #8b5e34);
            color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .modal-header {
            border-bottom: 2px solid #fff;
        }
        .modal-header .modal-title {
            font-weight: bold;
        }
        .btn-close {
            background-color: #fff;
            border-radius: 50%;
        }
        .btn-close:hover {
            background-color: #ccc;
        }
        .modal-body {
            padding: 20px;
        }
        .modal-body .form-label {
            color: #f0f0f0;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid #fff;
            border-radius: 5px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }
        .btn-secondary{
            background-color: #e6cb04;
            border: none;
            border-radius: 5px;
        }
        .btn-primary{
            background-color: #e6cb04;
            border: none;
            border-radius: 5px;
        }
        .btn-secondary:hover {
            background-color: #436123;
        }
        .btn-primary:hover {
            background-color: #436123;
        }
    </style>
    </style>
</head>
<body>

    <!-- NAV -->
    <div class="navbar">
        <a href="#hero">Home</a>
        <a href="#content1">Features</a>
        <a href="#content2">Contact</a>
        <a href="#footer">Log In</a>
        <a href="#footer">Register</a>
    </div>

   <!-- Hero Section -->
    <section id="hero">
        <h1>Welcome To SemBreak!!!!!!!!!</h1>
        <!-- PUT GAME LINK INSIDE ON ONCLICK -->
        <button type="button"  onclick="window.location.href='';" class="playNowButton" >Play Now!</button>

    </section>

    <!-- Content Section 1 -->
    <section id="content1">
        <h1 class="headingLight" style="margin: 50px;">Characters</h1>
        <button id="loadFeatures">Load More Features</button>
        <div id="extraFeatures" class="hidden-content">Honkshu mimimi....</div>

        

        <!-- THE COLUMNS AND POP-UPS -->      
        <div class="container">

            <!-- Box 1 -->
            <div class="box">
                <img src="https://images.squarespace-cdn.com/content/v1/5fd56e513c1f6275809ed7d1/1633502681708-2N00GGCCYRTPVONA8KBF/Cranky+cat.PNG" alt="Image 1" class="column-image">
                <div class="content">
                    <p>SURPRISEEEE</p>
                </div>
            </div>

            <!-- Box 2 -->
            <div class="box">
                <img src="https://uploads.dailydot.com/2018/10/olli-the-polite-cat.jpg?auto=compress&fm=pjpg" alt="Image 2" class="column-image">
                <div class="content">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
            </div>

            <!-- Box 3 -->
            <div class="box">
                <img src="https://imgflip.com/s/meme/Scared-Cat.jpg" alt="Image 3" class="column-image">
                <div class="content">
                    <p>SURPRISEEEE</p>
                </div>
            </div>
        </div>



    </section>

   <!-- Content Section 2 -->
   <section id="content2" class="content2">
        <input type="radio" name="position" checked />
        <input type="radio" name="position" />
        <input type="radio" name="position" />
        <input type="radio" name="position" />
        <input type="radio" name="position" />
        <main id="carousel">
                <!-- numba 1 -->
            <div class="piktur">
                <img src="https://imgflip.com/s/meme/Scared-Cat.jpg" alt="Image 3" class="column-piktur">
            </div>
            <!-- numba 2 -->
            <div class="piktur">
                <img src="https://imgflip.com/s/meme/Scared-Cat.jpg" alt="Image 3" class="column-piktur">
            </div>
            <!-- numba 3 -->
            <div class="piktur">
                <img src="https://imgflip.com/s/meme/Scared-Cat.jpg" alt="Image 3" class="column-piktur">
            </div>
            <!-- numba 4 -->
            <div class="piktur">
                <img src="https://imgflip.com/s/meme/Scared-Cat.jpg" alt="Image 3" class="column-piktur">
            </div>
            <!-- numba 5 -->
            <div class="piktur">
                <img src="https://imgflip.com/s/meme/Scared-Cat.jpg" alt="Image 3" class="column-piktur">
            </div>

        </main>
    </section>




    <!-- Footer Section -->
    <section id="footer">
        <h2 style="margin-top: 100px;">Contact Us</h2>
        
        <div class="footer-buttons">
        <?php if ($isLoggedIn): ?>
            <!-- If the user is logged in, it shows the Dashboard and Sign Out buttons -->
            <button onclick="window.location.href='dashboard.php'" class="btn btn-success">Dashboard</button>
            <form method="post" action="index.php" style="display: inline;">
                <button type="submit" name="signOut" class="btn btn-danger">Sign Out</button>
            </form>
        <?php else: ?>
            <!-- If the user is not logged in, it shows the Log In and Register buttons -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Log In</button>
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
        <?php endif; ?>
    </div>
</section>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Log In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Log In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php">
                        <div class="mb-3">
                            <label for="registerName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="registerName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="registerEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="registerPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-secondary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="script.js"></script>

</body>
</html>
