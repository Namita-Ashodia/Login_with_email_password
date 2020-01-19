<?php
session_start();
?>
<html>
<head> 
<meta charset="utf-8">
 <title> Login ::Namita Ashodia </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/login.css">
  
  <link rel="icon" href="images/logo.jpg" type="image/jpg" sizes="16x16">
</head>
<header>
<img  src="images/abc.png" width="300" height="80" style="padding-left:2%;" />

</header>
<body>
     <div class="container" id="hide">
        <div class="card card-container">
            <img id="profile-img" class="profile-img-card" src="images/logo.jpg" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" action="" method="POST">

<?php
$redirect = "";

if(isset($_POST['SUBMIT']))
{
    include "inc/db.php";
    $reg_date = date('Y-m-d');

    $error = array();
   
    if (empty($_POST['email']))
    {
        $error[] = 'Please Enter your Email ';
    }
    else
    {

        if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['email'])) 
        {
         
            $email = $_POST['email'];
        } 
        else 
        {
             $error[] = 'Your Email Address is invalid  ';
        }
    }
  
    if(!empty($_POST['password']))
    {
         $password = validate($_POST['password']);
         $password = md5($password);
    }
    else 
    {
         $error[] = "Please check your Password";
    }
  
 
 
    if(empty($error))
    {
        $login_sql = "SELECT * FROM tms_admin  WHERE email ='$email' AND password = '$password'";
        $result = mysqli_query($dbc, $login_sql) or die(mysqli_error($dbc));
      
	   
        if(mysqli_num_rows($result) == '1') 
        {
           
             if(isset($_SESSION['redirect'])) 
            { 
                 $redirect = $_SESSION['redirect']; 
            }
		    $_SESSION = mysqli_fetch_assoc($result);
		 
            if($redirect=="")  
            {  
                if($_SESSION['designation']=='superadmin'){
                  echo '<script type="text/javascript"> window.location.href = "apanel/dashboard.php" </script>';
                exit();
                //echo("hi");
               }
               else
               {
                echo '<script type="text/javascript"> window.location.href = "apanel/dashboard.php" </script>';
                  // echo("only admin");
                   exit();
               }
            }
            else 
            {
        		  echo '<script type="text/javascript"> window.location.href = "'.$redirect.'" </script>';
        		  exit();
             }
   
		             
         } 
    
     /*   elseif(mysqli_num_rows($result) == '0')
        {  
            //echo("hi");
            $login_sql = "SELECT * FROM tms_admin WHERE email ='$email' AND password = '$password'";
            $result = mysqli_query($dbc, $login_sql) or die(mysqli_error($dbc));
            echo(mysqli_num_rows($result));
            if(mysqli_num_rows($result) == '1')
            { 
                if(isset($_SESSION['redirect'])) 
                { 
                     $redirect = $_SESSION['redirect']; 
                }
                
                //if(isset($_SESSION['redirect'])) 
               $_SESSION = mysqli_fetch_assoc($result);
		 
            if($redirect=="")  
            {  
                  echo '<script type="text/javascript"> window.location.href = "apanel/dashboard.php" </script>';
                exit();
            }
            else 
            {
        		  echo '<script type="text/javascript"> window.location.href = "'.$redirect.'" </script>';
        		  exit();
             }
            }
            else 
            {
                echo "<h5 style='color:red; font-weight:bold; text-align:center;'> Invalid Login </h5>";
            }
            
            }
*/
        else
        {  
            $login_sql = "SELECT id,fname,lname,email,mobile,department,designation,profile_picture,usertype FROM tms_employee WHERE email ='$email' AND password = '$password' ";
            $result = mysqli_query($dbc, $login_sql) or die(mysqli_error($dbc));
            if(mysqli_num_rows($result) == '1')
            {
                
                if(isset($_SESSION['redirect'])) 
                { 
                    $redirect = $_SESSION['redirect']; 
                }
                $_SESSION = mysqli_fetch_assoc($result);
                
                if($redirect=="")
                {  
                    // $_SESSION['id']=$result['id'];
                        echo '<script type="text/javascript"> window.location = "timesheet.php"  </script>';
                }
                else 
                {
                    echo '<script type="text/javascript"> window.location.href = "'.$redirect.'" </script>';
                    exit();
                }
            } 
            else 
            {
                echo "<h5 style='color:red; font-weight:bold; text-align:center;'> Invalid Login </h5>";
            }
        }
    }     
    else 
    {
         foreach($error as $key) 
         {
	    	  echo "<li style='text-align:left;'>". $key ."</li>";
	     }
         mysqli_close($dbc);  
    }
}

?>		<!-- Result of Invalid login appears here -->
																
				<input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
				
			<br>
				
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="SUBMIT">Sign in</button>
            </form><!-- /form -->
            <a href="#" class="forgot-password" id="myBtn">  Forgot the password?  </a>
        </div><!-- /card-container -->
    </div><!-- /container -->
	
	
	<div class="container" id="resethide">
        <div class="card card-container">
        
            <img id="profile-img" class="profile-img-card" src="images/logo.jpg" />
            <p id="profile-name" class="profile-name-card"></p>
            <div>
                
                
            	<input type="email" name="email" id="emails" class="w3-input" placeholder="Email address" style="padding: 2%; width: 70%;" required autofocus>
              	
                <button  onclick="resetpwd()" name="reset" style="padding:2%;">Reset</button>
            </div><!-- /form -->
            
        </div><!-- /card-container -->
    </div><!-- /container -->
                
	</body>
	<script type="text/javascript">
    function resetpwd(){ 
        
        var email=$('#emails').val();
        //alert(email);
        
        var atpos = email.indexOf("@");
        var dotpos = email.lastIndexOf(".");
    
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {
        alert("Not a valid e-mail address");
        return false;
    }
        
         if(email =="") {
             alert("Enter your email first");
             return false;
         }
        $.post("reset.php",'email='+email,function(result,status,xhr) {
                if( status.toLowerCase()=="error".toLowerCase() )
                { alert("An Error Occurred.."); }
                else {
                    alert(result);
                      window.location.href='login.php';
                    $('#sucessMessage').html(result);
                }
            })
           
            .fail(function(){ alert("something went wrong. Please try again") });  
    }
</script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
      //  var email=$('#email').val();
        
var resethide = document.getElementById('resethide');
 resethide.style.display = "none";

var hidelogin = document.getElementById('hide');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    //modal.style.display = "block";
    hidelogin.style.display = "none";
    resethide.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
/*
span.onclick = function() {
    modal.style.display = "none";
    hidelogin.style.display = "block";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        hidelogin.style.display = "block";
    }
}
*/ </script>


	</html>