<?php
session_start();
if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"])
{
	if(isset($_POST['username']) && isset($_POST['password']))
	{
		if(empty($_POST['username']) || empty($_POST['password']))
		{
			$error = "Please enter your username and password.";
		}
		else
		{
			$host = "303.itpwebdev.com";
			$user = "bglascoc_db_user";
			$password = "mydbpassword";
			$db = "bglascoc_puzzle_db";

			$mysqli = new mysqli($host, $user, $password, $db);
			if($mysqli->connect_errno)
			{
				echo $mysqli->connect_error;
				exit();
			}

			$passwordHash = hash("sha256", $_POST["password"]);

			$statement = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?;");
			$statement->bind_param("ss", $_POST['username'], $passwordHash);

			$executed = $statement->execute();
			if(!$executed)
			{
				echo $mysqli->error;
			}
			
			$result = $statement->get_result();
			if($result->num_rows > 0)
			{
				$_SESSION["username"] = $_POST["username"];
				$_SESSION["user_id"] = $result->fetch_assoc()["user_id"];
				$_SESSION["logged_in"] = true;

				header("Location: .");
			}
			else
			{
				$error = "Invalid username or password.";
			}
			
			$statement->close();
			$mysqli->close();
		}
	}
}
else
{
	header("Location: .");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="quest.css">
    <link href="https://fonts.googleapis.com/css2?family=Goldman&display=swap" rel="stylesheet">
    <title>Login</title>
	<style>
		#nav-login
		{
			color: #00aeff !important;
		}
	</style>
</head>
<body>
	<?php include 'nav.php'; ?>
    <div class="container">
		<div class="row">
			<div class="col-12">
				<div class="h1 mt-5 mb-5">Log in</div>
			</div>
		</div>
		<form action="login.php" method="POST" class="row mb-4">
			<div class="col-12 text-danger mb-3">
				<?php
					if(isset($error) && !empty($error))
					{
						echo $error;
					}
				?>
			</div>
			<div class="form-group col-12">
				<div class="row mb-3">
					<label for="username" class="col col-12 col-md-2 col-lg-1 mb-2">Username:</label>
					<div class="col col-12 col-md-4 col-lg-2">
						<input type="text" class="form-control" id="username" name="username" required>
					</div>
				</div>
				<div class="row">
					<label for="password" class="col col-12 col-md-2 col-lg-1 mb-2">Password:</label>
					<div class="col col-12 col-md-4 col-lg-2">
						<input type="password" class="form-control" id="password" name="password" required>
					</div>
				</div>
			</div>
			<div class="col-12 mb-2">
				<button type="submit" class="btn btn-color btn-primary">Log in</button>
			</div>
			<div class="col-12">
				<a href = "." class="btn btn-secondary">Go back</a>
			</div>
		</form>
		<div class="row">
			<div class="col-12">
				<a href = "./signup.php">Don't have an account? Click here to sign up!</a>
			</div>
		</div>
		<div class="row">
			<div id="footer">
				<div class="col col-12">
					<hr class="solid" style="border-color:white">
					<div>© 2020 Brendan Glascock</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>