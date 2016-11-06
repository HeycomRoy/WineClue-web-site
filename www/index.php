<?php
session_start();
include_once 'classes/viewClass.php';
include_once 'classes/modelClass.php';


/*This class gets the pageID from URL*/
class PageSelector
{
    public function run(){
        if($_GET['pageID']){
            $pageID = $_GET['pageID'];
        }
        else{
            $pageID = 'Home';
        }
        $model = new Model;
        
        
        if($_GET['pageID']!='logout'){
            if($_GET['pageID']=='editView'){
                $page = $_GET['page'];
            }
            else{
                $page = $pageID; 
            }
            $rs = $model->getPage($page);
        }
        
        
        switch($page){
            case 'Home':include('classes/homeViewClass.php');
                $view = new HomeView($rs, $model);
                break;
            case 'WineCollection':include('classes/productsViewClass.php');
                $view = new ProductsView($rs, $model);
                break;
            
            case 'Register':include('classes/registerViewClass.php');
                $view = new RegisterView($rs, $model);
                break;
            
            case 'Contact':include('classes/contactViewClass.php');
                $view = new ContactView($rs, $model);
                break;
            
            case 'ProductsCollec':include('classes/showProductViewClass.php');
                $view = new ShowProductView($rs, $model, $_GET['PID']);
                break;
            
            case 'AddProduct':include('classes/addProductViewClass.php');
                $view = new AddProductView($rs, $model);
                break;
            
            case 'DeleteProduct':include('classes/deleteProductViewClass.php');
                $view = new DeleteProductView($rs, $model, $_GET['PID']);
                break;
            
            case 'DeleteComment':include('classes/deleteCommentViewClass.php');
                $view = new DeleteCommentView($rs, $model, $_GET['CID'],$_GET['PID']);
                break;
             
            case 'EditProduct':include('classes/editProductViewClass.php');
                $view = new EditProductView($rs, $model, $_GET['PID']);
                break;
            
            case 'EditNews':include('classes/editNewsClass.php');
                $view = new EditNewsView($rs, $model, $_GET['NID']);
                break;
            
            case 'Search':include('classes/searchViewClass.php');
                $view = new SearchView($rs, $model, $_POST['Skeyword']);
                break;
        }
        echo $view->displayPage();
    }
}

$page = new PageSelector;
$page -> run();


?>