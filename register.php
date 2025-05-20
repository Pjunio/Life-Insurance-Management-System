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
            background-color:rgb(20, 91, 223);
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

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #007BFF;
            outline: none;
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
</head>
<body>
    <div class="form-container">
        <h2>Register for LifeSecure</h2>
        <p class="description">Your trusted life insurance management system</p>
        <form method="POST" action="">
            <label for="name">First Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your  first name" required>

            <label for="name">Last Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your last name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            
  			<label for="mobile">Mobile:</label>
  			<input type="number" id="mobile" name="mobile" placeholder="Enter your phone number" required>
  				
  			
  			<label for="adress">Address</label>
  			<input type="text" id="address" name="address" placeholder="Enter your address" required>
  			

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
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
            <!--<button type="submit">Register</button>-->
      <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                event.preventDefault();
            }
        });
        
    </script>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

  

      <div class="footer-icons">

        <a href="https://www.facebook.com"><i class="fa fa-facebook"></i></a>
        <a href="https://twitter.com"><i class="fa fa-twitter"></i></a>
        <a href="https://www.linkedin.com"><i class="fa fa-linkedin"></i></a>
        <a href="https://github.com"><i class="fa fa-github"></i></a>

      </div>

    </div>

  </footer>
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

      $Quary = "INSERT INTO users (user_firstName, user_lastName, user_email, user_mobile, user_address, user_password, user_gender) VALUES ('$firstName', '$lastName', '$email','$mobile','$address','$password','$gender')";
      
      if(mysqli_query($db,$Quary)){
        echo "<script>
          alert('Registered successfully!, Will redirect to login page shortly');
          window.location.href='login.php';
          </script>";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
  }
  //Closing DB connection
  mysqli_close($db);
?>