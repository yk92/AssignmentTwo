<script>
    function pwdChecker() {
        var checkBox = document.getElementById("passChecker")        
        if (checkBox.checked) {
            document.getElementById("passwd").attributes["type"].value = "text"
        }
        else {
            document.getElementById("passwd").attributes["type"].value = "password"
        }
    }
</script>

<!DOCTYPE html/>

<html>
<head>
    <title>Assignment One - Form.php</title>
</head>

<body>

        
    <form action="newRetrieve.php">
        <fieldset>
            <legend>Database Query Form</legend>
            <p>
                <label for="adminName">Admin:</label>
                <input type="text" name="adminName" autofocus placeholder='Enter admin account'/>
				<br>
            </p>
            
            <?php
                include("../acc/accounts.php");

                $db = new mysqli($dbServer, $userName, $password, $dbName);

                $results = $db ->query("SELECT * FROM REGISTERED");
                echo "<p> <label for ='dropdown'>User:</label>";
                echo "<select name='dropdown'>";
                
                echo "<option value=''>"."</option>";

                while($users = $results->fetch_object()){

                    echo "<option value='".$users->user."'>".$users->user."</option>";
                }
                echo "</select>";
                echo "<br></p>";
            
                mysqli_free_result($results);
                mysqli_close($db);
            ?>
			<p>
                <label for="password">Password:</label>
                <input type="password" name="password" id="passwd" placeholder='Password?' required/>
                <input type="checkbox" name="passCheck" id="passChecker" onclick='pwdChecker()'/>See password
				<br>
            </p>
			
            <p>
                <label for='emailCheck'>Send email copy?</label>
                <input type="checkbox" name="emailCheck" />
            <br>
			</p>
            <p>
                <label for='submit' class='subLabel'>Pushmeover</label>
                <input type="submit" name="submit" value="Submit">
				<input type="reset" name="reset" value="Reset">
            </p>
        </fieldset>
    </form>
    
    <a href="https://web.njit.edu/~yk92/it202/AssignmentOne/images/Request.PNG">Click for HTTP Request image</a>
	<br>
	<a href="https://web.njit.edu/~yk92/it202/AssignmentOne/images/ExperienceStatement.txt">Click for personal statement</a>
	<br>
	<a href="https://web.njit.edu/~yk92/it202/AssignmentOne/images/Retrieve.txt">Click for Retrieve.php Code</a>
    <br>
    <a href="https://web.njit.edu/~yk92/it202/AssignmentOne/images/Form.txt">Click for Form.php Code</a>
	<br>
	<a href="https://web.njit.edu/~yk92/it202/AssignmentOne/images/sampleEmail.png">Click for image of sample email with HTML in body</a>







</body>
</html>

<style>
    fieldset {
        width: 600px;
		border: ridge
    }
	label {
		background: yellow;
		width: 10em;
		float: left;
	}
	.subLabel {background-color: white;
				   color: white;  
	}
    
    
</style>
