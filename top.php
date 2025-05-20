<?php
include_once 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LifeSecure</title>
    
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: rgb(20, 91, 223);
            margin: 0;
        }

        .form-container {
            width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #007BFF;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .description {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .input-group {
    display: flex;
    align-items: center;
}
input {
    flex: 1;
    margin-right: 10px;
}
.toggle-btn {
    width: 20px;
    height: 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
    padding: 0;
}
 

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        .error {
            color: red;
            font-size: 12px;
            display: block;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="form-container">
        <h2>Register for LifeSecure</h2>
        <p class="description">Your trusted life insurance management system</p>
        <form id="registrationForm" method="POST" action="">
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" placeholder="Enter your first name" required>
            <span class="error" id="fname_error"></span>

            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" placeholder="Enter your last name" required>
            <span class="error" id="lname_error"></span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <span class="error" id="email_error"></span>
            
            <label for="mobile">Mobile:</label>
            <input type="number" id="mobile" name="mobile" placeholder="Enter your phone number" required>
            <span class="error" id="mobile_error"></span>
                
            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Enter your address" required>
            <span class="error" id="address_error"></span>

            <label for="password">Password:</label>
<div class="input-group">
    <input type="password" id="password" name="password" placeholder="Enter your password" required>
    <button type="button" id="togglePassword" class="toggle-btn"><i class="fas fa-eye"></i></button>
</div>
<span class="error" id="password_error"></span>

<label for="confirm_password">Confirm Password:</label>
<div class="input-group">
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
    <button type="button" id="toggleConfirmPassword" class="toggle-btn"><i class="fas fa-eye"></i></button>
</div>
<span class="error" id="confirm_password_error"></span>
            <ul>
                <li>
                    <label class="radio_wrap">
                        <input type="radio" name="gender" value="male" class="input_radio" checked>
                        <span>Male</span>
                    </label>
                </li>
                <li>
                    <label class="radio_wrap">
                        <input type="radio" name="gender" value="female" class="input_radio">
                        <span>Female</span>
                    </label>
                </li>
            </ul>
            <div class="input_wrap">
                <input type="submit" name="submit" value="Register Now" class="submit_btn">
            </div>

            <script>
               // Toggle functionality for password
document.getElementById('togglePassword').addEventListener('click', function() {
    var passwordInput = document.getElementById('password');
    var icon = this.querySelector('i');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Toggle functionality for confirm password
document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    var confirmPasswordInput = document.getElementById('confirm_password');
    var icon = this.querySelector('i');
    if (confirmPasswordInput.type === 'password') {
        confirmPasswordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        confirmPasswordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

                document.getElementById('registrationForm').addEventListener('submit', function(event) {
                    var nameRegex = /^[a-zA-Z\s-]+$/;
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    var mobileRegex = /^\d{10}$/;
                    var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;

                    var isValid = true;

                    // Reset error messages
                    document.querySelectorAll('.error').forEach(function(el) {
                        el.innerText = '';
                    });

                    // Validate first name
                    var fname = document.getElementById('fname').value;
                    if (!nameRegex.test(fname)) {
                        document.getElementById('fname_error').innerText = 'First name can only contain letters, spaces, or hyphens';
                        isValid = false;
                    }

                    // Validate last name
                    var lname = document.getElementById('lname').value;
                    if (!nameRegex.test(lname)) {
                        document.getElementById('lname_error').innerText = 'Last name can only contain letters, spaces, or hyphens';
                        isValid = false;
                    }

                    // Validate email
                    var email = document.getElementById('email').value;
                    if (!emailRegex.test(email)) {
                        document.getElementById('email_error').innerText = 'Invalid email format';
                        isValid = false;
                    }

                    // Validate mobile
                    var mobile = document.getElementById('mobile').value;
                    if (!mobileRegex.test(mobile)) {
                        document.getElementById('mobile_error').innerText = 'Mobile number must be 10 digits';
                        isValid = false;
                    }

                    // Validate address
                    var address = document.getElementById('address').value;
                    if (address.trim() === '') {
                        document.getElementById('address_error').innerText = 'Address is required';
                        isValid = false;
                    }

                    // Validate password
                    var password = document.getElementById('password').value;
                    if (!passwordRegex.test(password)) {
                        document.getElementById('password_error').innerText = 'Password must be at least 8 characters, with one letter, one number, and one special character';
                        isValid = false;
                    }

                    // Validate confirm password
                    var confirmPassword = document.getElementById('confirm_password').value;
                    if (password !== confirmPassword) {
                        document.getElementById('confirm_password_error').innerText = 'Passwords do not match';
                        isValid = false;
                    }

                    if (!isValid) {
                        event.preventDefault();
                    }
                });
            </script>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>

<?php
if (isset($_POST["submit"])) {
    $firstName = $_POST['fname'];
    $lastName = $_POST['lname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE user_email = ?";
    $stmt = mysqli_prepare($db, $checkEmailQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already in use. Please use a different email.');</script>";
    } else {
        // Insert the new user with prepared statement
        $Quary = "INSERT INTO users (user_firstName, user_lastName, user_email, user_mobile, user_address, user_password, user_gender) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $Quary);
        mysqli_stmt_bind_param($stmt, "sssssss", $firstName, $lastName, $email, $mobile, $address, $password, $gender);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                alert('Registered successfully! Will redirect to login page shortly');
                window.location.href='login.php';
            </script>";
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
}
// Closing DB connection
mysqli_close($db);
?>