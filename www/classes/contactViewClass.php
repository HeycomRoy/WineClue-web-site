<?php

class ContactView extends View  ///////////////////This class is contain methods for contact page
{
	protected $model;
	protected $rs;
	private $eResult;
    
	public function __construct($rs,$model)
	{
		    $this->rs = $rs;
		    $this->model = $model;
		    $this->eResult = $eResult;
	}
	
	
	public function displayPage()  ///////////////////////public function displaypage to validate send email form
	{
		if($_POST['submit']){
			$this->eResult = $this->model->validateEmailForm($_POST);
		}
		$html = parent::displayPage();
		return $html;
	
	}
	
	protected function displayContent()
	{
	    $html = '<div id="RightSide">'."\n";
	    $html .= '<div id="SendEmailZone">'."\n";
	    $html .= '<h3 class="SmallTitle">'.$this->rs['PageHeading'].'</h3>'."\n";
	
		if($this->eResult['ok']){  //////////////////////////// to determine all the informations are filed
			$this->eResult = $this->model->sendMail();
		}
		else{
			$html .= $this->showContentForm();
		}
	    $html .= '<div class="pagemsg">'.$this->eResult['msg'].'</div>'."\n";
	    $html .= '<div class="pagemsg">'.$this->eResult['pgMsg'].'</div>'."\n";
	    $html .= '</div></div>'."\n";
    
	    return $html;
	}
    
	
	public function showContentForm()  ///////////////////////////////to show contact page content form
	{
		    
	    $html = '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";
	    $html .= '<label for="UserName" class="col1">Name:</label>'."\n";
	    $html .= '<input type="text" name="UserName" id="UserName" value="'.htmlentities(stripslashes($_POST['UserName']),ENT_QUOTES).'" class="col2" /><br />'."\n";
	    $html .= '<div class="col3">'.$this->eResult['name_msg'].'</div>'."\n";
	    $html .= '<div class="clear"></div>'."\n";
	    $html .= '<label for="uEmail" class="col1">Email Address:</label>'."\n";
	    $html .= '<input type="text" name="Email" id="uEmail" value="'.$_POST['Email'].'" class="col2" />'."\n";
	    $html .= '<div class="col3">'.$this->eResult['email_msg'].'</div>'."\n";		
	    $html .= '<div class="clear"></div>'."\n";
	    $html .= '<label for="Message" class="col1">Message:</label>'."\n";
	    $html .= '<textarea name="Message" id="Message" class="col2" rows="6" cols="20">'.htmlentities(stripslashes($_POST['Message']),ENT_QUOTES).'</textarea>'."\n";//<stripslashes->for this case is special char.
	    $html .= '<div class="col3">'.$this->eResult['message_msg'].'</div>'."\n";
	    $html .= '<div class="clear"></div>'."\n";
	    $html .= '<input type="submit" name="submit" value="Send" class="sub"/>'."\n";
	    $html .= '</form>'."\n";
	    return $html;
	}
}
?>