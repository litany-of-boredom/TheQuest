<?php
    session_start();
    
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

    $sql = "SELECT * FROM comments
    JOIN users ON comments.user_id = users.user_id
    JOIN puzzles ON comments.puzzle_id = puzzles.puzzle_id
    WHERE puzzles.puzzle_id = 1
    ORDER BY comment_id DESC;";

    $results = $mysqli->query($sql);
    if ($results == false)
    {
        echo $mysqli->error;
        exit();
    }
    
    $mysqli->close();
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
		#nav-puzzle1
		{
			color: #00aeff !important;
		}
	</style>
    <title>Puzzle 1</title>
</head>
<body onload="shuffle();">
    <?php include 'nav.php'; ?>

    <div class="jumbotron">
		<h1 class="display-4">Puzzle 1</h1>
    </div>

    <div class="container">
		<div class="row">
			<div class="col col-12 mb-4">
                <div class="h1">Puzzle 1:</div>
                <div>Our sources have discovered a mysterious image that seems to have some sort of message on it, but it's been broken into 15 pieces! You must reassemble it and decipher its meaning.</div>
            </div>
            <div class="col col-12 mb-4">
                <div id="sliderpuzzle" style="display: table;">
                    <div id="row1" style="display: table-row;">
                        <div id="tile0" class="tile" onClick="clickTile(0);"></div>
                        <div id="tile1" class="tile" onClick="clickTile(1);"></div>
                        <div id="tile2" class="tile" onClick="clickTile(2);"></div>
                        <div id="tile3" class="tile" onClick="clickTile(3);"></div>
                    </div>
                    <div id="row2" style="display: table-row;">
                        <div id="tile4" class="tile" onClick="clickTile(4);"></div>
                        <div id="tile5" class="tile" onClick="clickTile(5);"></div>
                        <div id="tile6" class="tile" onClick="clickTile(6);"></div>
                        <div id="tile7" class="tile" onClick="clickTile(7);"></div>
                    </div>
                    <div id="row3" style="display: table-row;">
                        <div id="tile8" class="tile" onClick="clickTile(8);"></div>
                        <div id="tile9" class="tile" onClick="clickTile(9);"></div>
                        <div id="tile10" class="tile" onClick="clickTile(10);"></div>
                        <div id="tile11" class="tile" onClick="clickTile(11);"></div>
                    </div>
                    <div id="row4" style="display: table-row;">
                        <div id="tile12" class="tile" onClick="clickTile(12);"></div>
                        <div id="tile13" class="tile" onClick="clickTile(13);"></div>
                        <div id="tile14" class="tile" onClick="clickTile(14);"></div>
                        <div id="tile15" class="tile" onClick="clickTile(15);"></div>
                    </div>
                </div>
                <br>
                <form id="answer-form">
                    <div class="form-group row">
                        <div class="col-6 col-md-3">
                            <label for="answer" class="h6">Answer:</label>
                            <input type="text" class="form-control" id="answer" placeholder="Enter answer">
                        </div>
                    </div>
                    <button type="submit" id="submit-answer" class="btn btn-color btn-primary">Submit</button>
                </form>
                <div id="output"></div>
            </div>
        </div>

        <div class="row">
            <div class="col col-12" id="hint-section">
                <div class="h2">Hints:</div>
                <div class="h5">Hint 1 ▼</div>
                <p class="hide">The piece with the word "Start" on it goes in the top left. Use the lines to build the rest of the puzzle.</p>
                <div class="h5">Hint 2 ▼</div>
                <p class="hide">Once the puzzle is complete, follow the lines in the indicated direction and read out the letters you encounter.</p>
                <div class="h5">Hint 3 (answer) ▼</div>
                <p class="hide">"Answer: icanseeformiles"</p>
            </div>
        </div>

        <div class="row">
            <div class="col col-12 mb-3" id="comment-section">
                <div class="h2">Comments</div>
                <div id="no-comments"><?php if($results->num_rows == 0){echo "No comments yet.";}?></div>
                <?php while($row = $results->fetch_assoc()) : ?>
                    <div id="comment-<?php echo $row['comment_id'];?>" class="comment"><strong><?php echo $row['username'];?></strong> says: <span id="comment-text-<?php echo $row['comment_id'];?>"><?php echo $row['comment'];?></span>
                        <?php if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] && $row['user_id'] == $_SESSION['user_id']) : ?>
                            <a class="show-form" onclick="showForm(<?php echo $row['comment_id'];?>);"><u>(Edit/Delete)</u></a>
                        <?php endif; ?>
                    </div>
                    <?php if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] && $row['user_id'] == $_SESSION['user_id']) : ?>
                        <div class="row">
                            <form class="edit-form col-11 col-md-8 col-lg-6 ml-4 mt-2 mb-2" id="edit-form-<?php echo $row['comment_id'];?>">
                                <div class="form-group">
                                    <div class="text-danger" id="error-<?php echo $row['comment_id'];?>"></div>
                                    <label for="edit-<?php echo $row['comment_id'];?>" class="h6">Edit comment:</label>
                                    <input type="text" class="form-control" id="edit-<?php echo $row['comment_id'];?>" value="<?php echo $row['comment'];?>">
                                </div>
                                <button type="submit" id="submit-edit-<?php echo $row['comment_id'];?>" class="btn btn-color btn-primary mr-1" onclick="editComment(<?php echo $row['comment_id'];?>);">Save</button>
                                <button type="button" id="delete-comment-<?php echo $row['comment_id'];?>" class="btn btn-danger mr-1" onclick="deleteComment(<?php echo $row['comment_id'];?>);">Delete</button>
                                <button type="button" id="cancel-form-<?php echo $row['comment_id'];?>" class="btn btn-secondary" onclick="hideForm(<?php echo $row['comment_id'];?>);">Cancel</button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endwhile;?>
            </div>
            <?php if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) : ?>
                <div class="col col-12">
                    <form id="comment-form" onsubmit="postComment()">
                        <div class="form-group row">
                            <div class="col-6 col-md-3">
                                <label for="comment" class="h6">Add a comment:</label>
                                <input type="text" class="form-control" id="comment" placeholder="Your comment" required>
                                <input type="number" value="1" id="puzzle-id-hidden">
                                <input type="number" value="<?php echo $_SESSION["user_id"];?>" id="user-id-hidden">
                                <input type="text" value="<?php echo $_SESSION["username"];?>" id="username-hidden">
                            </div>
                        </div>
                        <button type="submit" id="post-comment" class="btn btn-color btn-primary">Post</button>
                    </form>
                </div>
            <?php endif;?>
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
    <script type="text/javascript" src="./puzzle1.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>