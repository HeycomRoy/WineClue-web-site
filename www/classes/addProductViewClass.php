<?php

class AddProductView extends View //////////////////////This class is contain methods for add new product page
{
    protected $model; 

    public function __construct($rs, $model)
    {
        $this->rs = $rs;
        $this->model = $model;
    }
    
    protected function displayContent() ////////////////////To display content of this page
    {
        $html = '<div id="RightSide">'."\n";
        $html .= '<div class="ProductsShowZone">'."\n";
        $html .= '<h3 class="pagehead">'.$this->rs['PageHeading'].'</h3>'."\n";
	
        if ($_SESSION['UserType'] != 'Admin') {   ///////////////////to determine if not admin user
            $html = '<div class="pageMsg">Sorry, but this is a restricted page!</div>'."\n";
            return $html;
        }
	
	else{    ////////////////////if user submit the form process processAddProduct function in model class
	    if ($_POST['Add']) {
		$result = $this->model->processAddProduct($_POST);
		if ($result['PImage']) {
		    $_POST['PImage'] = $result['PImage'];
		}
		//$html .= '<meta http-equiv="refresh" content=2;URL="index.php?pageID=WineCollection"'."\n";
	    }
		    
	    $html .= $this->displayProductForm("Add", $result, $_POST);
	    $html .= '</div></div>'."\n";
	    return $html;
	}
    }


    protected function displayProductForm($mode, $result, $product) //////////////////////display add new product form
    {   
        if (is_array($result)) {
            extract($result);	
        }
		
        $html = '<div class="hmecontent">'."\n";
        $html .= '<div id="editform">'."\n";	
        $html .= '<form id="edit_form" method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data">'."\n";
        $html .= '	<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />'."\n";
        $html .= '	<input type="hidden" name="ProductsID" value="'.$product['ProductsID'].'" />'."\n";
        $html .= '	<input type="hidden" name="PImage" value="'.$product['ProductsImages'].'" />'."\n";
		
        $html .= '	<label for="PTitle" class="col1">Products Title </label>';
        $html .= '	<input type = "text" name="ProductsTitle" id = "PTitle"  class="col2" value="'.htmlentities(stripslashes($product['ProductsTitle']),ENT_QUOTES).'" /><div id="pname_msg" class="col3"> '.
                        $ptitle_msg.'</div>'."\n";		
        $html .= '	<div class="clear"></div>'."\n";
		
        $html .= '	<label for="PSum" class="col1">Products Summary </label>';
        $html .= '	<textarea rows="6" cols="20" name="ProductsSummary" id = "PSum"  class="col2">'.htmlentities(stripslashes($product['ProductsSummary']),ENT_QUOTES).'</textarea> <div id="ptitle_msg" class="col3"> '.
                        $psummary_msg.'</div>'."\n";		
        $html .= '	<div class="clear"></div>'."\n";
		
        $html .= '<label for="PCont" class="col1">Products Content </label>';
        $html .= '<textarea rows="6" cols="20" name="ProductsContent" id = "PCont"  class="col2" >'.htmlentities(stripslashes($product['ProductsContent']),ENT_QUOTES).'</textarea><div id="pdesc_msg" class="col3"> '.
                        $pcontent_msg.'</div>'."\n";
       
        if ($product['ProductsImages']) { /////////////////////////display products image
            $html .= '<div class="col1a">Product Images<br />';
            $html .= '<img src="images/img/'.$product['ProductsImages'].'" alt="'.$product['ProductsImages'].'"/></div>'."\n";
		
        } 
        else {
            $html .= '<div class="col1" >&nbsp;</div>';
        }
        $html .= '	<div class="col2u"><label for="pimage">Upload New Image</label><br />'."\n";
        $html .= '	<input id="pimage" type="file" name="PImage" /></div>'."\n";
		
        $html .= '	<div id="pimage_msg" class="col3"><br /> '.$pimage_msg.'</div>'."\n";
        $html .= '	<div class="clear"></div>'."\n";
		
        $html .= '	<input type = "submit" name="'.$mode.'" value="'.$mode.'" class="submitButton" />'."\n";
       // $html .= '</div>'."\n"; 
        $html .= '	<div class="clear"></div>'."\n";
        $html .= '</form>'."\n";
        $html .= '</div>'."\n";                       // end of div editform
        $html .= '<div class="pagemsg">'.$msg.'</div>';
		$html .= '</div>'."\n";
        return $html;
    }
}
?>