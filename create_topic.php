<!-- For this php file I will be using transactions in order to make sure that both functions required for submitting a topic go through successfully. Those two parts are creating a topic as well as creating the first post in a topic, because a topic shouldn't be empty on creation or else there will be nothing for the users to respond to. The transaction confirms the topic was successfully created then the post was created.

-->

<?php
    include 'connection.php';
    include 'header.php';
    session_start(); //start the session to gain access to user level

    echo '<h2> Create a topic</h2>';
    echo '<div id="create-form">';

    if($_SESSION['signed_in'] == false){
        echo 'Please <a href="signin.php"> sign in </a> to create a topic';
    }else{
        //the user is signed in

            $sql = 'SELECT category_id, category_name FROM categories';
            $result = mysqli_query($conn, $sql);

            if(!$result){
                echo 'Error, please try again another time.';
            }else{
                if(mysqli_num_rows($result) == 0){ 
                    //there are no categories thus no topics
                    if($_SESSION['user_level'] == 1){ 
                        //admin user
                        echo 'Hey admin, there aren\'t any categories yet. Do you wanna <a href="create_category.php">make some categories?</a>';
                    }else{ 
                        //regular user
                        echo 'There are no categories created by the admins yet';
                    }
                }else{
                    echo '<form method="post" action="">
                            Topic Title: <input type="text" name="topic_subject" value="' . $_POST['topic_subject'] . '"/>';
                    echo '<br>';
                    echo 'Category:';
                    echo '<select name="topic_category">';
                        while($row = mysqli_fetch_assoc($result)){
                            echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                        }
                    echo '</select>';
                    echo '<br>';
                    echo 'Brief Topic Description: <textarea name="post_content"/>' . $_POST['post_content'];
                    echo '</textarea><br><input type="submit" name="submit" value="Create topic"/></form>';
                }
            }

            if(isset($_POST['submit'])){
                //Validate: make sure subject and message are not empty
                $errors = array();

                if(empty($_POST['topic_subject'])){
                    $errors[] = 'Please provide a title for your topic.';
                }

                if(empty($_POST['post_content'])){
                    $errors[] = 'Please provide a brief description for your topic.';
                }

                if(strlen($_POST['topic_subject']) >= 70){
                    $errors[] = 'Please shorten your topic title';
                }
                if(strlen($_POST['post_content']) >= 200){
                    $errors[] = 'Please shorten your topic description';
                }

                //If there are not any errors, process the information
                if(empty($errors)){
                    //Start the transaction
                    $query = 'BEGIN WORK';
                    $result = mysqli_query($conn, $query);

                    if(!$result){
                        //an issue in starting the transaction
                        echo 'An error occured while creating your topic, please try again later';
                    }else{
                        $user_id = $_SESSION['user_id'];
                        //we will first sanitize the data
                        $topic_name = test_input($_POST['topic_subject']);
                        $topic_category = test_input($_POST['topic_category']);
                        $topic_content = test_input($_POST['post_content']);

                        //escape data for mysql
                        $topic_name = mysqli_real_escape_string($conn, $topic_name);
                        $topic_category = mysqli_real_escape_string($conn, $topic_category);
                        $topic_content = mysqli_real_escape_string($conn, $topic_content);

                        //sql line
                        $sql = "INSERT INTO topics(topic_name, topic_date, topic_category, topic_author, topic_content) 
                        VALUES ('$topic_name', NOW(), '$topic_category', '$user_id', '$topic_content')";


                        $result = mysqli_query($conn, $sql);
                        if(!$result){
                            echo 'An error occurred while inserting your new topic, please try again.';
                            $sql = 'ROLLBACK';
                            $result = mysqli_query($conn, $sql);
                        }else{
                            //now send the reply(post) to the database
                            $topic_id = mysqli_insert_id($conn);
                            //get the id from the topic that was just inserted
                            $post_topic = mysqli_real_escape_string($conn, $_POST['post_content']);
                            $reply_author = $_SESSION['user_id'];
                            $sql = "INSERT INTO reply(reply_comment, reply_date, reply_topic, reply_author)
                                    VALUES ('$post_topic', NOW(), '$topic_id', '$reply_author')";
                                
                            $result = mysqli_query($conn, $sql);

                            if(!result){
                                echo 'An error occurred while inserting your topic comment, please try again';
                                $sql = 'ROLLBACK';
                                $result = mysqli_query($conn, $sql);
                            }else{
                                $sql = 'COMMIT';
                                $result = mysqli_query($conn, $sql);
                                echo 'You have successfully created a topic';//click here to view
                                echo '<br>';
                                echo '<a href="topic.php?id=' . $topic_id .'">Click Here to View</a>';
                            }
                        }

                    }
            }else{
                //handle user errors
                echo '<ul>';
                    foreach($errors as $e){
                        echo '<li>' . $e . '</li>';
                    }
                echo '</ul>';
            }
        }
        
    }
    //Function to clean user input
    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    echo '</div>';
include 'footer.php';
mysqli_close($conn);
?>