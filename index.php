<?php
	session_start();

	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'])
	{
		$host = "localhost";
		$user = "root";
		$password = "";
		$db = "thequest_db";
    

		$mysqli = new mysqli($host, $user, $password, $db);
		if($mysqli->connect_errno)
		{
			echo $mysqli->connect_error;
			exit();
		}

		$statement = $mysqli->prepare("SELECT * FROM solves
		JOIN users ON solves.user_id = users.user_id
		JOIN puzzles ON solves.puzzle_id = puzzles.puzzle_id
		WHERE users.username = ?;");
		$statement->bind_param("s", $_SESSION['username']);

		$executed = $statement->execute();
		if(!$executed)
		{
			echo $mysqli->error;
		}

		$results = $statement->get_result();
		if ($results == false)
		{
			echo $mysqli->error;
			exit();
		}

		// store puzzles that users has solved in this array
		$solves[1] = 0;
		$solves[2] = 0;
		while($row = $results->fetch_assoc())
		{
			$solves[$row['puzzle_id']] = 1;
		}

		$mysqli->close();
		$statement->close();
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
	<style>
		#nav-home
		{
			color: #00aeff !important;
		}
	</style>
    <title>The Quest</title>
</head>
<body>
	<?php include 'nav.php'; ?>

    <div class="jumbotron">
		<h1 class="display-4">The Quest</h1>
    </div>
    
    <div class="container">
		<div class="row">
			<div class="col col-12 col-md-8">
				<div class="row">
					<div class="col col-12 mb-4">
						<div class="h1">Instructions:</div>
						<div>The Quest is a collection of puzzles that will test your observation, critical thinking, and spatial reasoning. You may play them in any order you wish. Log in if you'd like to save your progress.</div>
					</div>
					<div class="col col-12 mb-4">
						<div class="h2">Puzzle 1: Slider</div>
						<div>Our sources have discovered a mysterious image, but it's been broken into 15 pieces! You must reassemble it and decipher its meaning.</div>
						<br>
						<a href="./puzzle1.php" class="btn btn-color btn-info" role="button">Play Puzzle 1</a>
					</div>
					<div class="col col-12 mb-4">
						<div class="h2">Puzzle 2: Wordplay</div>
						<div>We've found a cryptic riddle and an even more cryptic submission box. Find the answer and figure out how to input it to solve the puzzle!</div>
						<br>
						<a href="./puzzle2.php" class="btn btn-color btn-info" role="button">Play Puzzle 2</a>
					</div>
				</div>
			</div>
			<div class="col col-12 col-md-4 mb-3">
				<div id="progress">
					<div class="h1">Puzzle Progress:</div>
					<div id="puzzle1-progress"><h5 class="puzzle-progress">Puzzle 1:</h5>
						<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) : ?>
							<?php if($solves[1]) : ?>
								<?php echo "Solved";?>
							<?php else : ?>
								<?php echo "Not solved yet";?>
							<?php endif;?>
						<?php else: ?>
							Log in to track your puzzle progress!
						<?php endif;?>
					</div>
					<div id="puzzle2-progress"><h5 class="puzzle-progress">Puzzle 2:</h5>
						<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) : ?>
							<?php if($solves[2]) : ?>
								<?php echo "Solved";?>
							<?php else : ?>
								<?php echo "Not solved yet";?>
							<?php endif;?>
						<?php else: ?>
							Log in to track your puzzle progress!
						<?php endif;?>
					</div>
				</div>
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