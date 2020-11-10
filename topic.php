<?php
    //View the topics page 
    include 'header.php';
    include 'connection.php';
    session_start();
    $topic_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    //get topic info and display it
    $sql = "SELECT topic_name, topic_date, topic_author FROM topics WHERE topics.topic_id = '$topic_id'";

    $result = mysqli_query($conn, $sql);

    if(!$result){
        echo "Something went wrong please try again later";
    }else{
        if(mysqli_num_rows($result)==0){
            echo "This topic does not exist please try again later.";
        }else{ 
            //get replies to this topic as well as the author's username
            $reply_sql = "SELECT replies.reply_comment, replies.reply_date, replies.reply_author, users.user_id, users.username FROM replies LEFT JOIN users on replies.reply_author = users.user_id WHERE replies.reply_topic = '$topic_id'";

            $replies_result = mysqli_query($conn, $reply_sql);

            if(!$replies_result){
                echo "Something went wrong";
            }else{
                $topics = mysqli_fetch_assoc($result);
                echo $topics['topic_name'];
                echo "<br>";
                if(mysqli_num_rows($replies_result) == 0){
                    echo "There are no posts in this topic yet.";
                }else{
                    echo "<table border='1'>
                            <tr>
                                <th>Comment</th>
                                <th>Author</th>
                                <th>Date</th>
                            </tr>";
                    while($row = mysqli_fetch_assoc($replies_result)){
                        echo "<tr>";
                            echo "<td>" . $row['reply_comment'];
                            echo "</td>";
                            echo "<td>";
                                echo $row['username'];
                            echo "</td>";
                            echo "<td>";
                                echo date('jS \of F Y h:i:s A', strtotime($row['reply_date']));
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } 
                
            }
        }
        
    }

    //Allow users to post comment
    if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true){
        //user is signed in and therefore can make a post
        echo '<form method="post" action="topic.php?id=' .$topic_id . '">';
                echo '<textarea name="comment" rows="4" cols="50"></textarea><br>
                <input type="submit" value="Post" name="user_reply"></form>';

        if(isset($_POST['user_reply'])){
            //validate for emptyness
            if(empty($_POST["comment"])){
                echo "Please enter a comment";
            }else{
                //get variables
                $reply_content = $_POST['comment'];
                $reply_author = $_SESSION['user_id'];
                $reply_topic = $topic_id;
                
                //sanitize
                $reply_content = trim($reply_content);
                $reply_content = stripslashes($reply_content);
                $reply_content = htmlspecialchars($reply_content);
                $reply_content = mysqli_real_escape_string($conn, $reply_content);

                $post_sql = "INSERT INTO replies(reply_comment, reply_date, reply_author, reply_topic)
                        VALUES ('$reply_content', NOW(), '$reply_author', '$reply_topic')";
                
                $post_result = mysqli_query($conn, $post_sql);
                
                if(!$post_result){
                    echo "Something went wrong, please try again later.";
                }else{
                    echo 'Success';
                    header('Location: topic.php?id='. $topic_id);
                }
            }      
        }
    }else{
        echo '<br>';
        echo 'Please <a href="signin.php">sign in</a> to post';
    }

include 'footer.php';
?>