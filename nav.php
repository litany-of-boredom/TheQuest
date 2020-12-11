<nav class="navbar navbar-expand-md navbar-dark">
    <a class="navbar-brand" href=".">The Quest</a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_content" aria-controls="navbar_content" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
        
    <div class="collapse navbar-collapse order-1" id="navbar_content">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a id="nav-home" class="nav-link" href=".">Home</a>
            </li>
            <li class="nav-item">
                <a id="nav-puzzle1" class="nav-link" href="./puzzle1.php">Puzzle 1</a>
            </li>
            <li class="nav-item">
                <a id="nav-puzzle2" class="nav-link" href="./puzzle2.php">Puzzle 2</a>
            </li>
            </ul>
        <ul class="navbar-nav ml-auto">
            <?php if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>
                <li class="nav-item">
                    <a id="nav-login" class="nav-link" href="./login.php">Log in</a>
                </li>
                <li class="nav-item">
                    <a id="nav-signup" class="nav-link" href="./signup.php">Sign up</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <div id="hello-msg" class="nav-link">Hi, <?php echo $_SESSION["username"];?></div>
                </li>
                <li class="nav-item">
                    <a id="nav-logout" class="nav-link" href="./logout.php">Log out</a>
                </li>
            <?php endif;?>
        </ul>
    </div>
</nav>