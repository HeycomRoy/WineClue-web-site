<?php
class RegisterView extends View  ////////////////////////////////////////////////////////////This class is contain methods for register page
{
    protected $rs;
    protected $model;
    protected $result;
    
    public function __construct($rs, $model)
    {
        $this->rs = $rs;
        $this->model = $model;
    }
   
     
    protected function displayContent() ////////////////////////////////////////////////////////////////To display content of register page
    {
        $html = '<div id="RightSide">'."\n";
        $html .= '<div id="RegisterZone">'."\n";
        $html .= '<h3 class="SmallTitle">'.$this->rs['PageHeading'].'</h3>'."\n";
       
	if($_POST['process']){ ////////////////////////////////////////if user submit the register form going to saving thier data in to data base
            $this->result = $this->model->validateRegisterEntries();
            if($this->result['ok']){
                $this->result = $this->model->createUser();
                $html .= '<div style=" height:50px">'.$this->result['msg'].'</div>'."\n";
            } 
        }
        if(!$this->result['msg']){
            $html .= $this->showRegisterForm();
        }
        
        $html .= '</div></div>'."\n";
        return $html;
    }
   
  
    private function showRegisterForm() ////////////////////////////////////////////////////////////This funtion is for display register form 
    {
       
        if(is_array($this->result)){ /////////////////////////////////////////////////////////////////Import variables into the current function
            extract($this->result);
        }     
        if($_POST['process']){
            extract($_POST);
        }
        
       
        if($_SESSION['UserType'] == 'User'){ ///////////////////////////////////////if logined user on register page will auto jump to home page
		header('location: index.php');
        }
	
        else{ /////////////////////////////////////////////////////////////////////////////////////////////////////////display register form
		$html = '<div id="R_F"><form method="post" id="RegisterForm" action="'.$_SERVER['REQUEST_URI'].'">'."\n";
		$html .= '<label for="Firstname" class="Register1">First Name:</label>'."\n";
		$html .= '<div class="Register3"><input id="Firstname" class="Register" type="text" name="Firstname" value="'.htmlentities(stripcslashes($Firstname), ENT_QUOTES).'"/>'."\n";
		$html .= '<div class="vMsg">'.$fnameMsg.'</div></div>'."\n";
		$html .= '<div class="clearR"></div>'."\n";
		$html .= '<label for="Lastname" class="Register1">Last Name:</label>'."\n";
		$html .= '<div class="Register3"><input id="Lastname" class="Register" type="text" name="lastname" value="'.htmlentities(stripcslashes($lastname), ENT_QUOTES).'"/>'."\n";
		$html .= '<div class="vMsg">'.$lnameMsg.'</div></div>'."\n";
		$html .= '<div class="clearR"></div>'."\n";
		$html .= '<label for="email" class="Register1">Email Address:</label>'."\n";
		$html .= '<div class="Register3"><input id="email" class="Register" type="text" name="email" value="'.$email.'"/>'."\n";
		$html .= '<div class="vMsg">'.$emailMsg.'</div></div>'."\n";
		$html .= '<div class="clearR"></div>'."\n";
		$html .= '<label for="Password" class="Register1">Password:</label>'."\n";
		$html .= '<div class="Register3"><input id="Password" class="Register" type="password" name="password" value="'.$password.'"/>'."\n";
		$html .= '<div class="vMsg">'.$passMsg.'</div></div>'."\n";
		$html .= '<div class="clearR"></div>'."\n";
		$html .= '<label for="CPassword" class="Register1">Confirm Password:</label>'."\n";
		$html .= '<div class="Register3"><input id="CPassword" class="Register" type="password" name="Cpassword" value="'.$Cpassword.'"/>'."\n";
		$html .= '<div class="vMsg">'.$CpassMsg.'</div></div>'."\n";
		$html .= '<div class="clearR"></div>'."\n";
		$html .= '<label for="Towncity" class="Register1">Town/City:</label>'."\n";
		$html .= '<div class="Register3"><input id="Towncity" class="Register" name="city" value="'.htmlentities(stripcslashes($city), ENT_QUOTES).'"/>'."\n";
		$html .= '<div class="vMsg">'.$cityMsg.'</div></div>'."\n";
		$html .= '<div class="clearR"></div>'."\n";
		$html .= '<label for="Suburb" class="Register1">Suburb:</label>'."\n";
		$html .= '<div class="Register3"><input id="Suburb" class="Register" type="text" name="suburb" value="'.htmlentities(stripcslashes($suburb), ENT_QUOTES).'"/>'."\n";
		$html .= '<div class="vMsg">'.$suburbMsg.'</div></div>'."\n";
		$html .= '<div class="clearR"></div>'."\n";
		$html .= '<label for="HNoStreet" class="Register1">H.No,Street:</label>'."\n";
		$html .= '<div class="Register3"><textarea id="HNoStreet" class="Register" name="hno_street" rows="2" cols="20">'.htmlentities(stripcslashes($hno_street), ENT_QUOTES).'</textarea>'."\n";
		$html .= '<div class="vMsg">'.$hno_streetMsg.'</div></div>'."\n";
		$html .= '<div class="clearR"></div>'."\n";
		$html .= '<div id="RegisterButton"><input type="submit" value="Submit" name="process" class="submitButton"/></div>'."\n";
		$html .= '</form></div>'."\n";
        }
        return $html;
    }
}
?>