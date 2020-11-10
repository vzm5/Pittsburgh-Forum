<!--View a selected category and all the topics in the category 
User can be signed in or not
-->
<?php
include 'connection.php';
include 'header.php';

    //SQL for accessing the category
    $category_id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT category_name FROM categories WHERE category_id = '$category_id'";

    $result = mysqli_query($conn, $sql);

    if($result){
        //check and see if rows were returned or not
       if(mysqli_num_rows($result) == 0){
            echo "There is nothing in this category or the category does not exist";
        }else{
            //write SQL for getting all the topics in a category
            $topic_sql = "SELECT topics.topic_id, topics.topic_name, topics.topic_date, topics.topic_author, topics.topic_category, topics.topic_content, users.user_id, users.username FROM topics LEFT JOIN users on topics.topic_author = users.user_id WHERE topic_category = '$category_id'";
            $topic_result = mysqli_query($conn, $topic_sql);
            if(!$topic_result){//something went wrong
                echo "Something went wrong, please try again later";
            }else{
                //handle a category without any topics
                if(mysqli_num_rows($topic_result)==0){
                    echo "There are no topics in this category yet, would you like to <a href='create_topic.php'>make one?</a>";
                }else{ //handle a category with many topics
                    echo "<br>";
                    echo "<br>";
                    echo "<table border='1'><tr><th>Topic</th><th>Date</th><th>Description</th><th>Topic Creator</th><tr>";
                    while($row = mysqli_fetch_assoc($topic_result)){
                        echo "<tr>";
                        echo "<td>";
                        echo "<h3><a href='topic.php?id=" . $row['topic_id'] . "'>" . $row['topic_name'] . "</a><h3>";
                        echo "</td>";
                        echo "<td>";
                        echo date('jS \of F Y h:i:s A', strtotime($row['topic_date']));
                        echo"</td>";
                        echo "<td>" . $row['topic_content'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        

                    echo"</tr>";
                }
                    echo "</table>";
                }
            }
        }
    }else{//there was not a result, somthing went wrong
        echo "Something went wrong, please try again later";
    }

?>