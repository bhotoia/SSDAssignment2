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
        <form action="createPost.php" method="post">
            Title: <input type="text" name="postTitle"><br/>
            Content: <textarea name="postCont"></textarea>
            <input type="submit">
        </form>
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

        
// insert record
      
        ?>
    </body>
</html>
