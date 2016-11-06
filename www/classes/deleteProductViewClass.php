<?php

class DeleteProductView extends View  //////////////////////This class is contain methods for delete product page
{
    protected $model;
    private $PID;
    private $product;
    private $msg;
    
    public function __construct($rs, $model, $PID)
    {
        $this->rs = $rs;
        $this->model = $model;
        $this->PID = $PID;
    }
    
   
    protected function displayContent() /////////////////////to display delete product page content
    {
        $html = '<div id="RightSide">'."\n";
        $html .= '<div id="ShowProductZone">'."\n";
        $html .= '<h3 class="SmallTitle">'.$this->rs['PageHeading'].'</h3>'."\n";
        
        if($_SESSION['UserType'] != "Admin"){  ////////////////////if not admin user login
            $html .= '<div class="pageMsg">Sorry, but this is a restricted page!</div>'."\n";
        }
        /////////////process
        else{
            if($_POST['confirm']){
                $result = $this->model->deleteProductDetails($_POST['PID'], $_POST['PImage']);
                $html .= '<div class="pageMsg">'.$result['msg'].'</div>'."\n";
                $html .= '<meta http-equiv="refresh" content=2;URL="index.php?pageID=WineCollection"'."\n";
            }
            elseif($_POST['cancel']){
                $this->msg = "No Product Has been Deleted";
            }
            $this->product = $this->model->getProducts($this->PID);
            if(!$_POST['confirm']){
                $html .= $this->displayDeleteForm();
            }
        }
        $html .= '</div></div>'."\n";
        return $html;
    }
    
   
    private function displayDeleteForm()         ///////////////////////////display delete product form
    {
        $html = '<div class="prdrow">'."\n";
        $html .= '<div class="bigImage"><img src="images/img/big_'.$this->product['ProductsImages'].'" alt="'.$this->product['ProductsTitle'].'" /></div>'."\n";
        $html .= '<div class="prdDetails">'."\n";
        $html .= '<p class="product_title">'.$this->product['ProductsTitle'].'</p>'."\n";
        $html .= '<div class="delform">Do you want to delete this product?<br />'."\n";
        $html .= '<form method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'">'."\n";
        $html .= '<input type="hidden" name="PID" value="'.$this->product['ProductsID'].'" />'."\n";
        $html .= '<input type="hidden" name="PImage" value="'.$this->product['ProductsImages'].'" />'."\n";
        $html .= '<input type="submit" name="confirm" value="Yes" /> '."\n";
        $html .= '<input type="submit" name="cancel" value="No" />'."\n";
        $html .= '</form>'."\n";
        $html .= '</div>'."\n";
        $html .= '<p class="pageMsg">'.$this->msg.'</p>'."\n";
        $html .= '</div>'."\n";
        $html .= '</div>'."\n";
        return $html;
    }
    
}
?>