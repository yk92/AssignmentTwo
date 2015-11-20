<?php
   
   include("../acc/accounts.php");
   
   $db = new mysqli($dbServer, $userName, $password, $dbName); //create new mysqli object and connect to database
   
   if($db->connect_error){ //test the database connection for errors
    
	    die("Error connecting to the database!\r\n");
   }
   
   if ($_POST['password'] != $_POST['passwordConfirm']){
		
		echo "<script>
				alert('Passwords do not match')
			  </script>";
		die("Passwords do not match!");
   }
   

   include("myFunctions.php");
   
   if ( rNum($_POST['user'], $_POST['email']) > 0 ){
	
	   die("Information is already in REGISTERED table!<br/>");
   }
   
   echo "Entering data into REGISTERED table...<br/>";
   
   $stmt = $db->prepare("INSERT INTO REGISTERED (user, email, pwd, fullname, phone, address, regist_datetime, major, numcourses) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)");
   
   $stmt->bind_param('sssssssi', $u, $e, $p, $f, $c, $a, $m, $n);
   
   $u = $_POST['user'];
   
   $e = $_POST['email'];
   
   $p = sha1($_POST['password']);
   
   $f = $_POST['fullname'];
   
   $c = $_POST['cell'];
   
   $a = $_POST['address'];
   
   $m= $_POST['major'];
   
   $n = 0;
   
   if( !$stmt->execute() ){
	
	  die("ERROR - Not able to enter data into REGISTERED!<br/>");
   }
   
   else{  
	
	  echo "Entered data into REGISTERED successfully!<br/>";
	  echo "Created row in REGISTERED with user: " . $_POST['user'] . " and " . "email: " . $_POST['email'] . "<br/>";
   }
   
   $stmt->close();
   
   $db->close();
   
?>