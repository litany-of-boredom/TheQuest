<?php
    if(!isset($_POST['comment']) || !isset($_POST['user_id']) || !isset($_POST['puzzle_id']))
        header("Location: .");
    if(empty($_POST['comment']))
        echo "Comment cannot be blank";

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "thequest_db";

    $mysqli = new mysqli($host, $user, $password, $db);
    if($mysqli->errno)
    {
        echo $mysqli->error;
        exit();
    }

    $statement = $mysqli->prepare("INSERT INTO comments (comment, user_id, puzzle_id) VALUES (?, ?, ?);");
    $statement->bind_param("sii", $_POST['comment'], $_POST['user_id'], $_POST['puzzle_id']);

    $executed = $statement->execute();
    if(!$executed)
    {
        echo $mysqli->error;
    }

    if($statement->affected_rows != 1)
	{
		echo "Error adding comment to database";
    }
    
    $statement->close();
    $mysqli->close();
?>