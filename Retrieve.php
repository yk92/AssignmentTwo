<?php
echo "<style> table { border-collapse: collapse;
                      margin-left: auto;
                      margin-right: auto;
                    } </style>";
echo "<style> table, caption { border: 2px solid blue;
                               padding: 10px;
                             } </style>";
echo "<style> th { text-align: center;
                   background: #bbbbbb
                 } </style>";
echo "<style> caption { color: red ; } </style>";
echo "<style> .gradeTable {border-collapse: collapse;
                           border: 2px solid green ;
                           padding: 10px;
                          } </style>";
echo "<style> .gradeTable { margin-left:auto;
                            margin-right:auto; 
                           } </style>";
echo "<style> .gradeCaption { border: 2px solid green;
                              padding: 10px;
                            } </style>";

/*
 *This file will be the retrieval script that uses the form data to go into
 *the database and return the proper query data
 *
 *If case 1: select * from REGISTERED
 *If case 2: select * from REGISTERED where user = '$user'
 *If case 3: select * from REGISTERED where user = '$user' and pw = '$password'
 *If case 4: Do not query the database - send a message that no data was requested
 
 */
    include("../acc/accounts.php"); //database information and admin username/pw
    
    
    $db = new mysqli($dbServer, $userName, $password, $dbName); //create new mysqli object and connect to database
    
    if($db->connect_error){ //test the database connection for errors
        
        die("Error connecting to the database!");
    }
    
    echo "This is Retrieve.php - you have been redirected here from Form.php<br><br>";
    
    //case #1 - admin wants entire table output
    if($_GET['adminName'] == 'admin' && $_GET['password'] == 'password' && $_GET['dropdown'] == ''){
        
        //Retrieve the entire REGISTERED table and output it
        
        $results = $db->query("SELECT * FROM REGISTERED");
        $gradeResults = $db->query("SELECT * FROM GRADES");
        
        echo "Admin, Here is the entire REGISTERED Table. <br><br>";
    }
        
    //case #2 admin wants a specific user's data output
    elseif($admin == $_GET['adminName'] && $adminPW == $_GET['password'] && !empty($_GET['dropdown'])){
        $userQuery = $_GET['dropdown'];
        
        $results = $db->query("SELECT * FROM REGISTERED WHERE user = '$userQuery'");
        $gradeResults = $db->query("SELECT * FROM GRADES WHERE user = '$userQuery'");
        
        $message = "Here is the requested data from query ". $userQuery. "\n"; //variable to store email message
        echo $message . "<br><br>";
    }
    
    //case #3 user wants his own data output
    elseif(empty($_GET['adminName']) && !empty($_GET['dropdown']) && !empty($_GET['password'])){
        
        $userQuery = $_GET['dropdown'];
        $userPassQuery = sha1($_GET['password']);
        
        $results = $db->query("SELECT * FROM REGISTERED WHERE user = '$userQuery' AND pwd = '$userPassQuery'");
        $gradeResults = $db->query("SELECT * FROM GRADES WHERE user = '$userQuery'");
        
        $message = "Dear ".$row->email.",\r\n";
        $message .= "Here is the requested data from query ". $userQuery. "\r\n"; //variable to store email message
        
        echo $userQuery . ", " . "Here are your results:<br><br>";
        //in case# 2 - if user requests email copy, the email is to be sent to user's email address from REGISTERED table
    }
    
    else{ //case #4: error case - acknowledge incorrect credentials and deny database query
        
        die("Invalid credentials entered!");
    }
    
    //output Table
    echo "<table border=3>";
    echo "<caption> REGISTERED TABLE RESULTS </caption>";
            
            echo "<tr>";
            
                echo "<th> User </th>";
                echo "<th> Email </th>";
                echo "<th> Password </th>";
                echo "<th> Full Name </th>";
                echo "<th> Phone # </th>";
                echo "<th> Address </th>";
                echo "<th> Registration Time </th>";
                echo "<th> Major </th>";
                echo "<th> Number of Courses </th>";
            
            echo "</tr>";
        
            while($row = $results->fetch_object()){
                echo "<tr>";
                    echo "<td>". $row->user."</td>";
                    $message .= "User: " . $row->user . "\r\n";
                    echo "<td>". $row->email."</td>";
                    $message .= "Email: " . $row->email . "\r\n";
                    echo "<td>". $row->pwd."</td>";
                    $message .= "Password: " . $row->pwd . "\r\n";
                    echo "<td>". $row->fullname."</td>";
                    $message .= "Full Name: " . $row->fullname . "\r\n";
                    echo "<td>". $row->phone."</td>";
                    $message .= "Phone #: " . $row->phone . "\r\n";
                    echo "<td>". $row->address."</td>";
                    $message .= "Address: " . $row->address . "\r\n";
                    echo "<td>". $row->regist_datetime."</td>";
                    $message .= "Registration Time: " . $row->regist_datetime . "\r\n";
                    echo "<td>". $row->major."</td>";
                    $message .= "Major: " . $row->major . "\r\n";
                    echo "<td>". $row->numcourses."</td>";
                    $message .= "Number of Courses: " . $row->numcourses . "\r\n";
                echo "</tr>";
            }
    echo "</table>";
    
    echo "<br>";
    
    echo "<table border=3 class=gradeTable>";
    echo "<caption class=gradeCaption> GRADES TABLE RESULTS </caption>";
            
            echo "<tr>";
            
                echo "<th> User </th>";
                echo "<th> Course </th>";
                echo "<th> A1 </th>";
                echo "<th> A1S </th>";
                echo "<th> A2 </th>";
                echo "<th> A2S </th>";
                echo "<th> PART </th>";
                echo "<th> TOTAL </th>";
                echo "<th> PERCENT </th>";
            
            echo "</tr>";
        
            while($row = $gradeResults->fetch_object()){
                echo "<tr>";
                    echo "<td>". $row->user."</td>";
                    $message .= "User: " . $row->user . "\r\n";
                    echo "<td>". $row->course."</td>";
                    $message .= "Course: " . $row->course . "\r\n";
                    echo "<td>". $row->A1."</td>";
                    $message .= "Assignment 1: " . $row->A1 . "\r\n";
                    echo "<td>". $row->A1S."</td>";
                    $message .= "Assignment 1 Submitted: " . $row->A1S . "\r\n";
                    echo "<td>". $row->A2."</td>";
                    $message .= "Phone #: " . $row->A2 . "\r\n";
                    echo "<td>". $row->A2S."</td>";
                    $message .= "Assignment 2 Submitted: " . $row->A2S . "\r\n";
                    echo "<td>". $row->PART."</td>";
                    $message .= "Participation grade: " . $row->PART . "\r\n";
                    echo "<td>". $row->TOTAL."</td>";
                    $message .= "TOTAL Grade: " . $row->TOTAL . "\r\n";
                    echo "<td>". $row->PERCENT."</td>";
                    $message .= "Percent out of 100: " . $row->PERCENT . "\r\n";
                echo "</tr>";
            }
    echo "</table>";
    
    //check for sending email
    if(isset($_GET['emailCheck']) && $_GET['adminName'] == $admin && !empty($_GET['dropdown'])){ //if email checkbox is checked - send email to admin in this case
            mail('yk92@njit.edu', 'Test Mail', $message); //mail(TO, TITLE, MESSAGE TO SEND)
            echo "Mail sent to admin..";
    }
    elseif(isset($_GET['emailCheck']) && empty($_GET['adminName']) && !empty($_GET['dropdown']) && $results->num_rows > 0 ){ //if email checkbox is checked - send email to user in this case
            mail($row->email, 'Test Mail', $message); //mail(TO, TITLE, MESSAGE TO SEND)
            echo "<br>Email sent to ".$row->email;
    }
    elseif(isset($_GET['emailCheck']) && $results->num_rows == 0){
        echo "Email request denied!<br>";
    }
    else{
        echo "<br>Email copy not requested<br>";
        echo "Database interaction completed<br>";
        echo "Have a nice day!";
    }
    
    mysqli_free_result($results); //free the results variable for another query
    
    mysqli_close($db); //close the database connection
    
    
    echo "<br><br><a href='https://web.njit.edu/~yk92/it202/AssignmentOne/Form.php'>Click to return to Query Form</a>";
?>
