<?php
//////to include all files need for this model class
include 'classes/dbaseClass.php';
include 'classes/validateClass.php';
include 'classes/resizeImageClass.php';
include 'classes/uploadClass.php';

class Model extends DBase
{
    private $validate;
  
    public function __construct()
    {
        parent::__construct();
        $this->validate = new Validate;  
        
    }
    
   
    public function validateRegisterEntries()       ////////////////////////////to validate register form
    {
        $result = array();
        $result['fnameMsg'] = $this->validate->checkRequired($_POST['Firstname']);
	    /*if(!$result['fnameMsg']){
		$result['fnameMsg'] = $this->validate->checkName($_POST['Firstname']);
	    }*/
        $result['lnameMsg'] = $this->validate->checkRequired($_POST['lastname']);
	    /*if(!$result['lnameMsg']){
		$result['lnameMsg'] = $this->validate->checkName($_POST['lastname']);
	    }*/
        $result['emailMsg'] = $this->validate->checkEmail($_POST['email']);
        $result['passMsg'] = $this->validate->checkRequired($_POST['password']);
	$result['CpassMsg'] = $this->validate->checkRequired($_POST['password']);
	if($_POST['password'] != $_POST['Cpassword']){
	    $result['CpassMsg'] = '*passwords do not match';
	} 	
        $result['cityMsg'] = $this->validate->checkRequired($_POST['city']);
        $result['suburbMsg'] = $this->validate->checkRequired($_POST['suburb']);
        $result['hno_streetMsg'] = $this->validate->checkRequired($_POST['hno_street']);
	
        
        foreach($result as $errmsg){ //////////////////////////////////////////////add other field validations here
            if(strlen($errmsg)>0){
                $result['ok'] = false;
                return $result;
            }
        }
        $result['ok'] = true;
        return $result;
    }
    
    public function validateCommentsEntries()
    {
        $result['commentMsg'] = $this->validate->checkComments($_POST['Comment']);
	$result['ratingMsg'] = $this->validate->checkRating($_POST['Rating']);
       
        foreach($result as $errmsg){ //////////////////////////////////////////////add other field validations here
            if(strlen($errmsg)>0){
                $result['ok'] = false;
                return $result;
            }
        }
        $result['ok'] = true;
        return $result;
    }
    
    
   
    public function validateProductEntries() /////////////////////////////////////////validate edit product page form
    {
        $result = array();
        $result['ptitle_msg'] = $this->validate->checkRequired($_POST['ProductsTitle']);
	$result['psummary_msg'] = $this->validate->checkRequired($_POST['ProductsSummary']);
	$result['pcontent_msg'] = $this->validate->checkRequired($_POST['ProductsContent']);
	
	if ($_GET['pageID'] == 'AddProduct') {
			$result['pimage_msg'] = $this->validate->checkRequired($_FILES['PImage']['name']);	
	}
       
        foreach($result as $errmsg){ ///////////////////////////////////////////////add other field validations here
            if(strlen($errmsg)>0){
                $result['ok'] = false;
                return $result;
            }
        }
        $result['ok'] = true;
        return $result;
    }

   
    private function validateUser() ///////////////////////////////////////////////////////////validate login form 
    {
        if($_POST['Login']&&$_POST['userName']&&$_POST['userPassword']){
            $rso = $this->getUser($_POST['userName']);
            if($rso->num_rows>0){
                $user = $rso->fetch_assoc();
                if(sha1($_POST['userPassword']) == $user['PassWord']){
                    $_SESSION['User'] = $user['FirstName'];
                    $_SESSION['UserID'] = $user['UserID'];
                    $_SESSION['UserType'] = $user['UserType'];
                    $result['pageMsg'] = 'You have successfully logged-in as'.$_SESSION['User'];
                    $result['ok'] = true;
                    $result['errorMsg'] = '';
                    return $result;
                }
                else{
                    $result['errorMsg'] = 'Invalid User Name/Password Combination';
                }
            }
            else{
                $result['errorMsg'] = 'Invalid User Name/Password Combination';
            }
        }
        else{
            $result['errorMsg'] = 'Please fill in all information!';
        }
        $result['ok'] = false;
        return $result;
    }
    
   
    public function validateEmailForm($Contact) ////////////////////////////validate send email form on contact page
    {
		
	$result = array();
	$result['name_msg'] = $this->validate->checkRequired($_POST['UserName']);
	    /*if(!$result['name_msg']){
		$result['name_msg'] = $this->validate->checkName($_POST['UserName']);
	    }*/
	$result['email_msg'] = $this->validate->checkEmail($Contact['Email']);
	$result['message_msg'] = $this->validate->checkRequired($Contact['Message']);
	foreach($result as $errmsg) {
	    if (strlen($errmsg) > 0) {
		$result['ok'] = false;
		//$result['msg'] = 'It is Unable to send!';
		return $result;
	    }
		else
			$result['ok'] = true;
	}
	
	return $result;
    }
    
    
    public function checkUserSession() ///////////////////////////////////////////////////////////check user session
    {
        if($_POST['Logout'] ){
            unset($_SESSION['User'], $_SESSION['UserID'], $_SESSION['UserType']);
            $result['pageMsg'] = 'You are now loggedout!';
        }
        if($_POST['Login']){
            $result = $this->validateUser();
        }
        return $result;
    }
    
   
    public function processAddProduct($product) ///////////////////////////////////////////validate add new product form
    {

       
        $vresult = $this->validateProductEntries(); //////////////////////////////////////////validate add product form
	if (!$vresult['ok']) {
	    return $vresult;
	}
        $iresult = $this->insertProduct($product);
        $PID = $iresult['PID'];
        if(!$PID){
            return $iresult;
        }
	
        $PImage = $this->uploadAndResizeImage($PID); ////////////////////////////////////////////////////resize images
        if($PImage){
            if($this->updateProductImagePath($PID, $PImage)){
                $iresult['msg'] .= 'Image uploaded/resized successfully.';
                $iresult['PImage'] .= $PImage;
            }
        }
        else{
            $iresult['msg'] .= 'Unable to upload/resize image.';
        }
        return $iresult;
    }
    
   
    public function processAddNews($news) ////////////////////////////////////////////////process add news on home page
    {
	$uresult['msg'] = $this->updateNews($news);
	return $uresult;
    }
    
   
    private function uploadAndResizeImage($PID) ///////////////////////////////////////////////upload and resize images
    {
        $imgsPath = "images/img";
        if (!$_FILES['PImage']['name']) {
		return false;
        }
        $extension = explode('.',$_FILES['PImage']['name']);
        if (stristr($extension[1],"jp")) {
            $extension[1] = "jpg";
        }
        $fileTypes = array("image/jpeg","image/pjpeg","image/gif");
        $upload = new Upload("PImage", $fileTypes, $imgsPath);
        $returnFile = $upload->isUploaded();
        if (!$returnFile) {
            return false;
        }
        $thumbPath = $imgsPath.'/'.$PID.'.'.$extension[1];
        $uid = uniqid('bk');
        $thumbBackup = $imgsPath.'/'.$uid.''.$PID.'.'.$extension[1];
        if (file_exists($thumbPath)) {
            rename($thumbPath, $thumbBackup);
        }
        $bigPath = $imgsPath.'/big_'.$PID.'.'.$extension[1];
        $bigBackup = $imgsPath.'/big_'.$uid.''.$PID.'.'.$extension[1];
        if (file_exists($bigPath)) {
            rename($bigPath, $bigBackup);
        }
        copy($returnFile, $thumbPath);
        if (!file_exists($thumbPath)) {
            return false;
        }
        $imgSize = getimagesize($returnFile);
        if ($imgSize[0] > 108 || $imgSize[1] > 108) {
            $thumbImage = new ResizeImage($thumbPath, 108, $imgsPath,'');
            if (!$thumbImage->resize()) {
                echo 'Unable to resize image to 150 pixels';
            }
        }
        rename($returnFile, $bigPath);
        if ($imgSize[0] > 350 || $imgSize[1] > 350) {
            $bigImage = new ResizeImage($bigPath, 350, $imgsPath,'');
            if (!$bigImage->resize()) {
                echo 'Unable to resize image to 350 pixels';
            }
        }
        if (file_exists($thumbPath) && file_exists($bigPath)) {
            @unlink($thumbBackup);
            @unlink($bigBackup);
            return basename($thumbPath);
        }
        else {
            rename($thumbBackup, $thumbPath);
            rename($bigBackup, $bigPath);
            return false;
        }
    }
    
   
    public function updateProductDetails($product) ///////////////////////////////////////////////edit product details
    {
	
	$vresult = $this->validateProductEntries(); /////////////////exit function if there is an in error in form validation
	if (!$vresult['ok']) {
	    return $vresult;
	}
	$PID = $product['ProductsID'];
	if ($_FILES['PImage']['name']) {
	    $PImage = $this->uploadAndResizeImage($PID);
	    if ($PImage) {
			$product['PImage'] = $PImage;
			$uresult['PImage'] = $PImage;
			$uresult['msg'] = ' Image uploaded/resized successfully. ';
	    }
	    else {
			
			$uresult['msg'] .= 'Unable to upload/resize image. ';
	    }
		
	}
	
	$uresult['msg'] .= $this->updateProduct($product);
	return $uresult;
    }
    
   
    public function deleteProductDetails($PID, $PImage) ///////////////////////////////////////////////delete product 
    {
	$dresult = $this->deleteProduct($PID);
	$thumbImage = "images/img".$PImage;
	$bigImage = "images/img/big_".$PImage;
	if ($dresult['ok']) {
		@unlink($thumbImage);
		@unlink($bigImage);
	}
	return $dresult;
    }
    
   
    public function sendMail(){ ///////////////////////////////////////////////////////////send email on contact page
        $to = 'cui.yao.yu@hotmail.com';
        $subject = 'WineClub Mail Contact';
        $messeage = 'From:'.stripslashes($_POST['UserName'])."\n";
        $messeage = stripslashes($_POST['Message']);
        $headers = 'From:'.stripslashes($_POST['UserName'])."\r\n Reply-To:".$_POST['Email'];
        if(mail($to, $subject, $messeage, $headers)){
            $msg['pgMsg'] = 'Email sent successfully';
        }
        else{
            $msg['pgMsg'] = 'Unable to send email';
        }
        return $msg;   
    }
}
?>