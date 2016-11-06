<?php


class Validate {/////////////////////////////////////////////////////////////////////This class is contain methods to validate form
   
  
    public function checkRequired($field) //////////////////////////////////////////////////////////////validate input box is field
    {
		if (!$field) {
			$msg = "*This information is required!";
		}
		return $msg;
    }
    
   
    public function checkComments($field) ///////////////////////////////////////////////////////////validate for text box is field
    {
		if (!$field) {
			$msg = "*Required to write some comments!";
		}
		return $msg;
    }
    
   
    public function checkRating($field) //////////////////////////////////////////////////////////validate drop down rating is field
    {
		if (!$field) {
			$msg = "*Required to give this product a rating!";
		}
		return $msg;
    }
   
  
    public function checkName($name) //////////////////////////////validate name is field
    {
	if(!preg_match('/^[[:alpha:]+\'[:blank:]]+$/',$name)){ ///////////////////////////////////// checking names, firstname, surname etc
	    $msg = "*Only letters, spaces, hyphens and apostrophes are allowed!";	
	}
	return $msg;
    }
   
   
    public function checkEmail($email)
    {
	if(!preg_match('/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-\.]+\.[a-zA-Z0-9_\-]+$/',$email)){ ////////////////////////// checking for a valid email
	    $msg = "*Email Address is required!";	
	}
	return $msg;
    }
    

}//end of class validate
?>