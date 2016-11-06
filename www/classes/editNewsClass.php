<?php

class EditNewsView extends View   ///////////////////This class contain methods to update news on home page
{
    protected $model; 
    private $nid;
	
    public function __construct($rs, $model, $nid)
    {
        $this->rs = $rs;
        $this->model = $model;
		$this->nid = $nid;
    }
    
    
    protected function displayContent()   ///////////////////This function to display edit news page content
    {

        $html = '<div id="RightSide">'."\n";
        $html .= '<div class="ProductsShowZone">'."\n";
        $html .= '<h3 class="pagehead">'.$this->rs['PageHeading'].'</h3>'."\n";
	
	
        if ($_SESSION['UserType'] != 'Admin') {  //////////////////to determine if not admin user
            $html .= '<div class="pageMsg">Sorry, but this is a restricted page!</div>'."\n";
        }
		else{
			
			if ($_POST['Upload']) {  /////////////////pass 'upload' to function processAddNews 
			$result = $this->model->processAddNews($_POST);
			}
			$new = $this->model->getNews($this->nid);   
			$html .= $this->displayProductForm("Upload", $result, $new);
			
			//$html .= '<meta http-equiv="refresh" content=2;URL="index.php"'."\n";
		}
		$html .= '</div></div>'."\n";
		return $html;
    }

    protected function displayProductForm($mode, $result, $news) //////////////////////This function is going to display edit news form
    {
	
        if (is_array($result)) {  ////////////////Import variables into the current function
            extract($result);	
        }
        $html = '<form id="edit_form" method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'" >'."\n";
		$html .= '	<label for="NTitle" class="col1">News Title: </label>'."\n";
        $html .= '	<input type = "text" name="NewsTitle" id = "NTitle"  class="col2" value="'.htmlentities(stripslashes($news['NewsTitle']),ENT_QUOTES).'" />'."\n";
        $html .= '	<div class="clear"></div>'."\n";
	$html .= '	<label for="PSum" class="col1">News Content: </label>'."\n";
        $html .= '	<textarea rows="6" cols="20" name="NeContentTXT" id = "PSum"  class="col2" >'.htmlentities(stripslashes($news['NeContentTXT']),ENT_QUOTES).'</textarea> '."\n";	
        $html .= '	<div id="ptitle_msg" class="col3"> '.$psummary_msg.'</div>'."\n";		
        $html .= '	<div class="clear"></div>'."\n";
        
		$html .= '	<input type = "hidden" name="NID" value="'.$news['NeID'].'" />'."\n";
        $html .= '	<input type = "submit" name="'.$mode.'" value="'.$mode.'" class="submitButton" />'."\n";
        $html .= '	<div class="clear"></div>'."\n";
        $html .= '</form>'."\n";                          /////////// end of editform                                                         
        $html .= '<div class="pagemsg">'.$msg['msg'].'</div>'."\n";
        return $html;
    }
}
?>