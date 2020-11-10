<?php
  session_start();
?>

<!DOCTYPE html>
  <head>
    <title>Best in PGH</title>
    <link rel="stylesheet" href="style.css">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
  </head>

  <body>
    <div class="title">
    
    <img src="images/skyline.jpg" alt="Skyline of Pittsburgh" class="skyline">
    <!--"Pittsburgh Skyline" by Brook-Ward is licensed with CC BY-NC 2.0. To view a copy of this license, visit https://creativecommons.org/licenses/by-nc/2.0/-->
    <h1><a href="index.php">"Best In Pittsburgh" Forum</a></h1>
    </div>
    <div class="menu">
        <?php
          //menu bar for already logged in users
          if($_SESSION['signed_in'] && $_SESSION["user_level"] == 1){
            echo '<div id="menu">
            <a href="index.php">Home</a>
            <a href="create_topic.php">Create Topic</a>
            <a href="create_category.php">Create Category</a>';
            echo '</div>';
            echo '<div id="log-in-menu">';
            echo 'Hello Admin, ' . $_SESSION['username'] . '. Not you? <a href="signout.php">Sign out</a>';
            echo '</div>';
          }elseif($_SESSION['signed_in']){
            //menu bar for regular users
            echo '<div>
                    <a href="index.php">Home</a>
                    <a href="create_topic.php">Create Topic</a>
                    </div>';
            echo '<div id="log-in-menu">';
            echo 'Hello ' . $_SESSION['username'] . '. Not you? <a href="signout.php">Sign out</a>';
            echo '</div>';
          }else{
            //menu bar for not logged in users
            echo '<div><a href="index.php">Home</a></div>
                  <div id="log-in-menu">
                    <a href="signin.php">Sign In</a> or <a href="register.php">Register</a>
                  </div>';
          }
        ?>
    </div>
  <br>
  <br>
    <div id="main-content">
