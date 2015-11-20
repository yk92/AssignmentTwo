<?php
	
	/*		Need to output the data entered into grades table back to the browser in a table
			Need to add admin password check into the if...elseif...else statement blocks 
			
			Fix up the files to work within the context of Assignment Two complete

			Need to add email checkbox option and associated logic
				send registration email to user after registering for class

			Add homepage for project with main menu / links
			
			Extra stuff to add:
			
			Possible addition of a button that shows the entire table to check grades after entering/updating
			
	*/
	
    include("../acc/accounts.php");
   
    $db = new mysqli($dbServer, $userName, $password, $dbName); //create new mysqli object and connect to database
   
    if($db->connect_error){ //test the database connection for errors
    
        die("Error connecting to the database!\r\n");
    }
   
    include("myFunctions.php");
    
    $emailTemp = '';
	
	if (!empty($adminPw) && $adminPw != $adminPW){
		die("Incorrect password given...<br/>");
	}
	
   
    if ( rNum($_POST['user'], $emailTemp) == 0 ){
	
        die("Student is not registered for any courses!<br/>");
    }
    
    //need to check if user is already registered for a course before creating the entry in GRADES table
    
    if ( gNum($_POST['user'], $_POST['course']) > 0 && !isset($_POST['applyA1']) && !isset($_POST['applyA2']) && empty($_POST['partic']) ){
        
        die("Error - Cannot create grades entry for " . $_POST['user'] . "<br/>Student already registered for " . $_POST['course'] . "<br/>");
    }
    
    elseif ( gNum($_POST['user'], $_POST['course']) > 0 && ( isset($_POST['applyA1']) || isset($_POST['applyA2']) ) ){
   
      echo "Updating grades for " . $_POST['user'] . "<br/>";
      
      if ( isset($_POST['applyA1']) ){
    
      $stmt = $db->prepare("UPDATE GRADES SET A1 = ?, A1S = ? WHERE user = ? AND course = ?");
    
      $stmt->bind_param('isss', $a1, $a1s, $userTemp, $courseTemp);
    
      $a1 = $_POST['A1range'];
      $a1s = $_POST['A1submit'];
      $userTemp = $_POST['user'];
      $courseTemp = $_POST['course'];
    
      if ( !$stmt->execute() ){
        
          die("Error - Could not update Grades for " . $userTemp . "<br/>");
      }
    
      else{
        
          echo "Updated " . $userTemp . "'s A1 grade for " . $courseTemp . "<br/>";
          echo "<br/><br/>Recalculating the total grade and percentage...<br/>";
          $stmt->close();
      }
      
      if ( empty($_POST['partic']) ){
       
       echo "Done updating grades for " . $userTemp . "<br/>";  
      }
      
      else{
         
         $stmt = $db->prepare("UPDATE GRADES SET PART = PART + ? WHERE user = ? and course = ?");
         
         $stmt->bind_param('iss', $p, $userTemp, $courseTemp);
         
         $p = $_POST['partic'];
         $userTemp = $_POST['user'];
         $courseTemp = $_POST['course'];
         
         $stmt->execute();
         
		 
         $db->query("UPDATE GRADES SET TOTAL = (A1 + A2 + PART), PERCENT = (TOTAL / 150) * 100 WHERE user = '$userTemp' AND course = '$courseTemp'");
      
         $stmt->close();
         
         echo "Finished recalculating grades...<br/><br/>";
      }   
   }
   
      if ( isset($_POST['applyA2']) ){
    
         $stmt = $db->prepare("UPDATE GRADES SET A2 = ?, A2S = ? WHERE user = ? AND course = ?");
    
         $stmt->bind_param('isss', $a2, $a2s, $userTemp, $courseTemp);
    
         $a2 = $_POST['A2range'];
         $a2s = $_POST['A2submit'];
         $userTemp = $_POST['user'];
         $courseTemp = $_POST['course'];
    
         if ( !$stmt->execute() ){
        
             die("Error - Could not update Grades for " . $userTemp . "<br/>");
         }
    
         else{
        
             echo "Updated " . $userTemp . "'s A2 grade for " . $courseTemp . "<br/>";
             echo "<br/><br/>Recalculating the total grade and percentage...<br/>";
             $stmt->close();
         }
      
         if ( empty($_POST['partic']) ){
       
             echo "Done updating grades for " . $userTemp . "<br/>";  
         }
      
         else{
         
             $stmt = $db->prepare("UPDATE GRADES SET PART = PART + ? WHERE user = ? and course = ?");
            
             $stmt->bind_param('iss', $p, $userTemp, $courseTemp);
         
             $p = $_POST['partic'];
             $userTemp = $_POST['user'];
             $courseTemp = $_POST['course'];
         
             $stmt->execute();
         
             $db->query("UPDATE GRADES SET TOTAL = (A1 + A2 + PART), PERCENT = (TOTAL / 150) * 100 WHERE user = '$userTemp' AND course = '$courseTemp'");
      
             $stmt->close();
         
             echo "Finished recalculating grades...<br/><br/>";
         }   
      }
   }
   
   elseif( gNum($_POST['user'], $_POST['course']) > 0 && !isset($_POST['applyA1']) && !isset($_POST['applyA2']) && !empty($_POST['partic']) ){
      
      echo "Updating participation grade for " . $_POST['user'] . "<br/>";
      
      $stmt = $db->prepare("UPDATE GRADES SET PART = PART + ? WHERE user = ? and course = ?");
            
      $stmt->bind_param('iss', $p, $userTemp, $courseTemp);
         
      $p = $_POST['partic'];
      $userTemp = $_POST['user'];
      $courseTemp = $_POST['course'];
         
      $stmt->execute();
         
      $db->query("UPDATE GRADES SET TOTAL = (A1 + A2 + PART), PERCENT = (TOTAL / 150) * 100 WHERE user = '$userTemp' AND course = '$courseTemp'");
      
      $stmt->close();
         
      echo "Finished recalculating grades...<br/><br/>";
   }
   
   else{
    
      echo "Entering data into GRADES table...<br/>";
   
      // user, course, A1, A1S, A2, A2S, PART, TOTAL, PERCENT are the columns in grades table
    
      $stmt = $db->prepare("INSERT INTO GRADES (user, course, A1, A1S, A2, A2S, PART, TOTAL, PERCENT) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
   
      $stmt->bind_param('ssisisiii', $us, $co, $nullA1, $tempDate1, $nullA2, $tempDate2, $nullPart, $nullTotal, $nullPercent);
    
      //set variables to create blank entries after creating new column in Grades with user/course data
      $us = $_POST['user'];
      $co = $_POST['course'];
      $nullA1 = 0;
      $tempDate1 = '10-10-10';
      $nullA2 = 0;
      $tempDate2 = '10-10-10';
      $nullPart = 0;
      $nullTotal = 0;
      $nullPercent = 0;
   
      if( !$stmt->execute() ){
	
          die("ERROR - Not able to enter data into GRADES table!<br/>");
      }
   
      else{  
	
          echo "Entered data into GRADES successfully!<br/>";
          echo $us . " has registered for " . $co ."<br/>";
      }
    
      //clean up prepared statement
      $stmt->close();
      
      //update the registered table by adding 1 to numcourses for the course added to grades table
    
      $db->query("UPDATE REGISTERED SET numcourses = numcourses + 1 WHERE user = '" . $_POST['user'] . "'");
      
      // Update grades in table
    
      if ( isset($_POST['applyA1']) ){
    
        $stmt = $db->prepare("UPDATE GRADES SET A1 = ?, A1S = ? WHERE user = ? AND course = ?");
    
        $stmt->bind_param('isss', $a1, $a1s, $userTemp, $courseTemp);
    
        $a1 = $_POST['A1range'];
        $a1s = $_POST['A1submit'];
        $userTemp = $_POST['user'];
        $courseTemp = $_POST['course'];
    
        if ( !$stmt->execute() ){
        
            die("Error - Could not update Grades for " . $userTemp . "<br/>");
        }
    
        else{
        
            echo "Updated " . $userTemp . "'s A1 grade for " . $courseTemp . "<br/>";
            echo "<br/><br/>Recalculating the total grade and percentage...<br/>";
            $stmt->close();
        }
      
        if ( empty($_POST['partic']) ){
       
         echo "Done updating grades for " . $userTemp . "<br/>";  
        }
      
        else{
         
           $stmt = $db->prepare("UPDATE GRADES SET PART = PART + ? WHERE user = ? and course = ?");
         
           $stmt->bind_param('iss', $p, $userTemp, $courseTemp);
         
           $p = $_POST['partic'];
           $userTemp = $_POST['user'];
           $courseTemp = $_POST['course'];
         
           $stmt->execute();
         
           $db->query("UPDATE GRADES SET TOTAL = (A1 + A2 + PART), PERCENT = (TOTAL / 150) * 100 WHERE user = '$userTemp' AND course = '$courseTemp'");
      
           $stmt->close();
         
           echo "Finished recalculating grades...<br/><br/>";
        }   
      }
   
      if ( isset($_POST['applyA2']) ){
    
         $stmt = $db->prepare("UPDATE GRADES SET A2 = ?, A2S = ? WHERE user = ? AND course = ?");
    
         $stmt->bind_param('isss', $a2, $a2s, $userTemp, $courseTemp);
    
         $a2 = $_POST['A2range'];
         $a2s = $_POST['A2submit'];
         $userTemp = $_POST['user'];
         $courseTemp = $_POST['course'];
    
         if ( !$stmt->execute() ){
        
             die("Error - Could not update Grades for " . $userTemp . "<br/>");
         }
    
         else{
        
             echo "Updated " . $userTemp . "'s A2 grade for " . $courseTemp . "<br/>";
             echo "<br/><br/>Recalculating the total grade and percentage...<br/>";
             $stmt->close();
         }
      
         if ( empty($_POST['partic']) ){
       
             echo "Done updating grades for " . $userTemp . "<br/>";  
         }
      
         else{
         
             $stmt = $db->prepare("UPDATE GRADES SET PART = PART + ? WHERE user = ? and course = ?");
            
             $stmt->bind_param('iss', $p);
         
             $p = $_POST['partic'];
         
             $stmt->execute();
         
             $db->query("UPDATE GRADES SET TOTAL = (A1 + A2 + PART), PERCENT = (TOTAL / 150) * 100 WHERE user = '$userTemp' AND course = '$courseTemp'");
      
             $stmt->close();
         
             echo "Finished recalculating grades...<br/><br/>";
         }   
      }
   }
   
   echo "<br><br><a href='https://web.njit.edu/~yk92/it202/AssignmentTwo/GradeInsert.html'>Click to update another grade</a>";
   
?>
