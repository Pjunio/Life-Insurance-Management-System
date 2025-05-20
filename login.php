<?php
include_once 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #4203a9, #90bafc);
            display: flex;
            justify-content: center;
            align-items: center;
        }
.wrapper{
	min-height: 100vh;
	display: flex;
	justify-content: center;
	align-items: center;
}
.title{
    padding:25px;
}
.registration_form{
	background: white;
	padding: 25px;
	border-radius: 5px;
	width: 400px;
}
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #555;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4203a9;
            box-shadow: 0 0 5px rgba(66, 3, 169, 0.2);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #4203a9;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-btn:hover {
            background: #35028c;
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password a {
            color: #4203a9;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="images/webicon.png">
    <link rel="stylesheet" href="">
</head>

<body>
   <!-- <button class="backbtn"><a href="index.html">Go back to home</a></button>-->

   <div class="wrapper">
   <div class="registration_form">
    <div>
        <div class="title">
        <p><b>TRUESECURE INSURANCE</b></p><br/>
    </div>
        <form  method="POST" action="">
        <div class="form-group">
            <label for="Username">Username</label>
            <input type="text" id="username" name="username" placeholder="Your email.." required>
    </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Your password.." required>
    </div>
            <input  type="submit" name="submit" value="Login" class="login-btn">
            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div><br/>
            <center>
        <p>Are you new user? <a href="register.php">Register now</a></p>
    </center>
        </form>
    </div>
    </div>   
</div>
    
    
   
</body>

</html>
<?php
    if (isset($_POST["submit"])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $logQuary = "SELECT * FROM users WHERE user_email = '$username'";
        $logResult = mysqli_query($db, $logQuary);

        if (mysqli_num_rows($logResult) == 1) {
            $row = mysqli_fetch_assoc($logResult);
            //check password
            if ($password == $row['user_password']) {
                session_start();
                $_SESSION["id"] = $row['user_id'];
                $_SESSION["firstName"] = $row['user_firstName'];
                $_SESSION["lastName"] = $row['user_lastName'];
                header("Location:home.php");
            } else {
                echo "<script>alert('Invalid password! Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Invalid username! Please try again.');</script>";
        }
    }
    //Closing DB connection
    mysqli_close($db);
?>


