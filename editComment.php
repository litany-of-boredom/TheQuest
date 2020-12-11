<?php
    if(!isset($_POST['comment']) || !isset($_POST['comment_id']))
        header("Location: .");
    if(empty($_POST['comment']))
        echo "Comment cannot be blank";

    $host = "303.itpwebdev.com";
    $user = "bglascoc_db_user";
    $pass = "mydbpassword";
    $db = "bglascoc_puzzle_db";

    $mysqli = new mysqli($host, $user, $pass, $db);
    if($mysqli->errno)
    {
        echo $mysqli->error;
        exit();
    }

    $statement = $mysqli->prepare("UPDATE comments SET comment = ? WHERE comment_id = ?;");
    $statement->bind_param("si", $_POST['comment'], $_POST['comment_id']);

    $executed = $statement->execute();
    if(!$executed)
    {
        echo $mysqli->error;
    }
    
    $statement->close();
    $mysqli->close();
?>