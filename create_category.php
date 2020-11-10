<?php
    include "connection.php";
    include "header.php";
    session_start();

    //Rules: only logged in Admins (user_level == 1) can create categories
    echo "<h2>Create a New Category</h2>";

    if($_SESSION["signed_in"] == false){
        echo "Please <a href='signin.php'> sign in </a> to create a category";
    }else{
        if($_SESSION["user_level"] != 1){
            echo "You must be an admin to create a new category, please enjoy the existing categories";
        }else{
            echo "Welcome back, " . $_SESSION['username'] . "!";
            echo "<br>";
            echo "<form method='post' action=''>
                    Category name: <input type='text' name='category_name' />
                    <input type='submit' value='Add category' />
                </form>";

            //User tried to make a new category
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $category_name = $_POST['category_name'];
                $errors = "";
                //Validation Rules: must not be empty
                if(empty($category_name)){
                    $errors = "Please insert a name for your new category";
                }
                if(empty($errors)){
                    $category_name = trim($category_name);
                    $category_name = stripslashes($category_name);
                    $category_name = htmlspecialchars($category_name);
                    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

                    $sql = "INSERT INTO categories(category_name) VALUES ('$category_name')";
            
                    $result = mysqli_query($conn, $sql);
            
                    if(!result){
                        echo "Something went wrong, please try again later.";
                    }else{
                        echo "New category successfully added, <a href='index.php'>check it out!</a>";
                    }
                }else{
                    echo $errors;
                }
            }
        }

    }
include 'footer.php';
?>