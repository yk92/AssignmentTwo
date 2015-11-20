<?php
	
    function rNum($u, $e){
		
		if ($e == ''){
			
			$stmt = $GLOBALS['db']->prepare("select user from REGISTERED where user = ?");
		
			$stmt->bind_param('s', $u);
		
			$stmt->execute();
		
			$stmt->bind_result($user);
			
			$count = 0;
			
			while($stmt->fetch()){
			
				$count++;
		}
		
			//return the count
			
			$stmt->close();
			return $count;
			
		}
		
		//prepared statements to access REGISTERED
		$stmt = $GLOBALS['db']->prepare("select user from REGISTERED where user = ? and email = ?");
		
		$stmt->bind_param('ss', $u, $e);
		
		$stmt->execute();
		
		$stmt->bind_result($user);
		
		// set a count to check if anything was returned
		$count = 0;
		while($stmt->fetch()){
			
			$count++;
		}
		
		//return the count
		$stmt->close();
		return $count;
        
    }
	
	function gNum($tempU, $c){
		
		$stmt = $GLOBALS['db']->prepare("select user from GRADES where user = ? and course = ?");
		
		$stmt->bind_param('ss', $tempU, $c);
		
		if ( !$stmt->execute() ){
			
			die("<br/>Error - could not access GRADES table to verify registration<br/>");
		}
		
		$stmt->bind_result($user);
		
		// set a count to check if anything was returned
		$count = 0;
		while($stmt->fetch()){
			
			$count++;
		}
		
		//return the count
		$stmt->close();
		return $count;
	}
    
?>