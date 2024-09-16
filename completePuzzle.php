<?php
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

    // check if user has already solved puzzle

    $statement1 = $mysqli->prepare("SELECT * FROM solves WHERE user_id = ? AND puzzle_id = ?;");
    $statement1->bind_param("ii", $_POST['user_id'], $_POST['puzzle_id']);
    $executed1 = $statement1->execute();
    if(!$executed1)
    {
        echo $mysqli->error;
    }

    $result = $statement1->get_result();
    if($result->num_rows == 0)
    {
        $statement = $mysqli->prepare("INSERT INTO solves (puzzle_id, user_id) VALUES (?, ?);");
        $statement->bind_param("ii", $_POST['puzzle_id'], $_POST['user_id']);

        $executed = $statement->execute();
        if(!$executed)
        {
            echo $mysqli->error;
        }

        if($statement->affected_rows != 1)
        {
            echo "Error updating database";
        }
    }
    
    $statement1->close();
    $statement->close();
    $mysqli->close();
?>