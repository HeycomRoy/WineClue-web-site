<?php
include 'classes/addProductViewClass.php';


class EditProductView extends AddProductView  //////////////////This class is contain methods for edit product page
{
    protected $model;
    private $PID;
    
    
    public function __construct($rs, $model, $PID) ////////////////////Constructs a Reflection
    {
        $this->rs = $rs;
        $this->model = $model;
		$this->PID = $PID;
    }

    protected function displayContent()
    {
		$html = '<div id="RightSide">'."\n";
	        $html .= '<div id="ShowProductZone">'."\n";
	        $html .= '<h2 class="pagehead">'.$this->rs['PageHeading'].'</h2>'."\n";
			
	        if ($_SESSION['UserType'] != "Admin") {       ///////////////to determine if not admin user
	            $html .= '<div class="pageMsg">Sorry, but this is a restricted page!</div>'."\n";
		    
	        }
		else{
			$product = $this->model->getProducts($this->PID);////////////////////////////take products recode from data base
			if ($_POST['Update']) {
				$result = $this->model->updateProductDetails($_POST);
				$product = $_POST;
				if ($result['PImage']) {
					$_POST['PImage'] = $result['PImage'];
				}
				$html .= '<meta http-equiv="refresh" content=2;URL="index.php?pageID=WineCollection"'."\n";
			}
			
			$html .= $this->displayProductForm("Update", $result, $product);

		}
		$html .= '</div></div>'."\n";
	        return $html;
    }
}

?>