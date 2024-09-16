<?php
    if(!isset($_POST['comment_id']))
        header("Location: .");

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

    $statement = $mysqli->prepare("DELETE FROM comments WHERE comment_id = ?;");
    $statement->bind_param("i", $_POST['comment_id']);

    $executed = $statement->execute();
    if(!$executed)
    {
        echo $mysqli->error;
    }

    if($statement->affected_rows != 1)
	{
		echo "Error deleting comment";
    }
    
    $statement->close();
    $mysqli->close();
?>