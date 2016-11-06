<?php

class ProductsView extends View//////////////////////////////*This class contains methods to generate the content of the products page*/
{
    
    protected $model;
    
    public function __construct($rs, $model)
    {
        $this->rs = $rs;
        $this->model = $model;  
    }
    
   
    protected function displayContent() /////////////////////////////////////////////////////////////display content on products page
    {
        $html = '<div id="RightSide" style="overflow: scroll; height:1100px;">'."\n";
        $html .= '<div class="ProductsShowZone">'."\n";
        $html .= '<h3 class="SmallTitle">'.$this->rs['PageHeading'].'</h3>'."\n";
        $html .= $this->displayProducts();
        return $html;
    }
    
   
    private function displayProducts() ////////////////////////////////////////////////////////////////////display a list of products
    {
        $html = '<div id="showPzone">'."\n";
        $products = $this->model->getProducts();

        foreach($products as $product){
            $html .= '<div class="ProductDisplay"><a href="index.php?pageID=ProductsCollec&amp;PID='.$product['ProductsID'].'">'."\n";
            $html .= '<img src="images/img/'.$product['ProductsImages'].'" alt="'.$product['ProductsTitle'].'" align="left" /></a>'."\n";
            $html .= '<a class="PC" href="index.php?pageID=ProductsCollec&amp;PID='.$product['ProductsID'].'">'.stripslashes($product['ProductsTitle']).'</a>'."\n";
            $html .= '<p>'.stripslashes($product['ProductsSummary']).'</p>'."\n";
            $html .= '<a href="index.php?pageID=ProductsCollec&amp;PID='.$product['ProductsID'].'" class="moreLink"><span>More</span></a>'."\n";
            
            if($_SESSION['UserType'] == 'Admin'){  ////////////////////////////////////////////////////////This is for Admin LoginView
                $html .= '<a href="index.php?pageID=EditProduct&amp;PID='.$product['ProductsID'].'" class="editLink1"><span>Edit</span></a>'."\n";
                $html .= '|<a href="index.php?pageID=DeleteProduct&amp;PID='.$product['ProductsID'].'" class="deleteLink1"><span>Delete</span></a>'."\n";
            }
            $html .= '</div>'."\n";
        }
        $html .= '</div>'."\n";
        if($_SESSION['UserType'] == 'Admin'){
            $html .= '<a href="index.php?pageID=AddProduct" class="editLink">Add new product</a>'."\n";
        } 
        $html .= '</div></div>'."\n";
        return $html;
    }
}
?>