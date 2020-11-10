<?php
    include 'header.php';
    include 'connection.php';
    $category_sql = "SELECT category_id, category_name FROM categories";

   
    $result = mysqli_query($conn, $category_sql);
   
    if(!$result){
        echo "Categories cannot be displayed, something went wrong :(";
    }else{
        if(mysqli_num_rows($result) == 0){
            echo 'No categories defined yet, <a href="create_category.php">make a new one!</a>';
        }else{
            echo 'Categories:';
            echo '<table border="1">
              <tr>
                <th>Category</th>
                <th>Last topic</th>
              </tr>'; 
              while($row = mysqli_fetch_assoc($result)){               
                echo '<tr>';
                    echo '<td class="left">';
                        echo '<h3><a href="category.php?id=' . $row['category_id'] . '">' . $row['category_name'] . '</a></h3>';
                    echo '</td>';
                    //need to fix this or delete it
                    echo '<td class="right">';
                                echo '<a href="topic.php?id="' . $row['topic_id'] . '> Topic subject</a> at 10-10';
                    echo '</td>';
                echo '</tr>';
            }
             
        }
    }

include 'footer.php';
?>