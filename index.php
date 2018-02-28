<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
   
        <?php
        require_once 'Config.php';
        $username = $password = "";
        $username_err = $password_err = $login_err = "";
        
        
   
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = ($_POST["username"]);
        $password = ($_POST["password"]);
        
        
    // Check if username is empty
    if(empty(trim($username))){
        $username_err = 'Please enter username.';
    }else{
        //Strip sensitive stuff we don't want before credentials check, to prevent XSS and stuff
        //Some of this was taken from Assignment 1
        $username = trim($username);
        $username = preg_replace('/UPDATE|INSERT|DELETE/i', '', $username);
        $username = strip_tags($username);
        $username = html_entity_decode($username);
        $username = urldecode($username);
        $username = preg_replace('/[^A-Za-z0-9 ]/', '', $username);
        //Now username is ready for credentials check.
    }
    
    // Check if password is empty
    if(empty(trim($password))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($password);
        $password = preg_replace('/UPDATE|INSERT|DELETE/i', '', $password);
        $password = strip_tags($password);
        $password = html_entity_decode($password);
        $password = urldecode($password);
        //Now password is ready for credentials check.
    }

    
    //If this is a regular user, and not Admin
    if(empty($username_err) && empty($password_err) && $username != 'masterlogin'){
        //Select statement
        $sql = "SELECT username, password FROM User_Info WHERE username = ?";
        
        ////////////////////////////////////////////////////////////////////////////////
        //!!Some part of the following codes are taken from a referenced source!!    ///
        ///////////////////////////////////////////////////////////////////////////////
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            $year = time() + 31536000;
                            if($_POST['remember']) {
				setcookie('remember_me', $_POST['username'].":".$_POST['password'], $year);
				}
			    elseif(!$_POST['remember']) {
				if(isset($_COOKIE['remember_me'])) {
					$past = time() - 100;
					setcookie(remember_me, gone, $past);
				}
			    }
                            session_start();
                            $_SESSION['username'] = $username;      
                            header("location: User_Home.php");
                        } else{
                            // Display a generic error message if password is not valid
                            $login_err = 'The username or password you entered is incorrect.';
                        }
                    }
                } else{
                    // Display a generic error message if username doesn't exist
                    $login_err = 'The username or password you entered is incorrect.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    
    //if the user is the admin
    if(empty($username_err) && empty($password_err) && $username == 'masterlogin'){
        // Prepare a select statement
        $sql = "SELECT username, password FROM Login_Info WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;      
                            header("location: Master.php"); //change header to if statement. username must equal master login user to go to master.php
                        } else{
                            // Display a generic error message if password is not valid
                            $login_err = 'The username or password you entered is incorrect.';
                        }
                    }
                } else{
                    // Display a generic error message if username doesn't exist
                    $login_err = 'The username or password you entered is incorrect.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
/*References:
 * http://php.net/manual/en/reserved.variables.cookies.php
 * http://forum.wampserver.com/read.php?2,148225,printview,page=1
 * https://stackoverflow.com/questions/48123325/warning-mysqli-stmt-bind-param-number-of-variables-doesnt-match-number-of-p?noredirect=1&lq=1
*/     
?>
    
    
    
    
    
    
    
     <body>
         <!--If there are error messages, then display the error message -->
          <?php
                    if (!empty($login_err)){
    			echo "<strong>Error! </strong>" . $login_err;
  		    }
  		    ?>
         <?php
  		    $process = $_COOKIE['remember_me'];
  		    $remember = explode(":",$process);
  		    ?>
         
         <form class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
         
             <input type="text" name ="username" placeholder="Username" value="<?php echo $remember[0]?>" required="">
             <span><?php echo $username_err?></span>
             
             <input type="password" name="password" placeholder="Password" value="<?php echo $remember[1]?>" required="">
             <span><?php echo $password_err?></span>
             
             <input type="checkbox" class="" name="remember" <?php
             if(isset($_COOKIE['remember_me'])) {
                 echo 'checked="checked"';
             }else {
                 echo ''; }?>>Remember me
             <button type="submit">Sign in</button>
         </form>
         
        <a href="Registration.php" class="">Register New User</a>
    </body>


   