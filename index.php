<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "sesame";
        $dbname = "web_blog";

// Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT postID, postTitle, postCont, postDate FROM blog_post ORDER BY postDate DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row

            while ($row = $result->fetch_assoc()) {
                echo "ID: " . $row["postID"] . "\nTitle: " . $row["postTitle"] . "\nContent:" . $row["postCont"] . "\nDate:" . $row["postDate"] . "<br>";
            }
        } else {
            echo "0 results";
        }
        
        $conn->close();
        //References
        //https://www.w3schools.com/php/php_mysql_select.asp
        ?>
    </body>
</html>
