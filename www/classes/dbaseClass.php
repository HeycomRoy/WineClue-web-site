<?php
include_once'../conf.php';


class Dbase  ///////////////////////////////////*This class for connecting to the database and database queries*/
{
    private $db;
    
    public function __construct()
    {
        try{
            $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
            if(mysqli_connect_errno()){
                throw new exception("Unable to connect to database");
            }
            else{
                return $this->db;
            }
        }
        catch(exception $e){
            echo $e->getMessage();
        }
    }
    
   
    public function getPage($pageID) //////////////////////////////////////////to get record form pages table
    {
        $qry = "SELECT PageID, PageTitle, PageHeading, PageKeywords, PageDescription FROM pages WHERE PageName = '$pageID'";
        $rs = $this->db->query($qry);
        if($rs){
            if($rs->num_rows>0){
                $page = $rs->fetch_assoc();
                return $page;
            }
            else{
                die('Page not found');
            }
        }
        else{
            die('Error executing query:'.$qry);
        }
    }
    
    
    public function getUser($userName)  ////////////////////////////////////to get user details form users table
    {
        $qry = "SELECT UserID, Email, PassWord, UserType, FirstName FROM user WHERE Email='$userName'";
        $rso = $this->db->query($qry);
        if($rso){
            return $rso;
        }
        else{
            echo 'Error executing query:'.$qry;
        }
    }
    
   
    public function getProducts($PID="") /////////////////////////////////////*This function is used get records from products table*/
    {    
        if($PID){
            $qry = "SELECT ProductsID, ProductsTitle, ProductsImages, ProductsSummary, ProductsContent FROM productsc WHERE ProductsID=$PID";
            $rs = $this->db->query($qry);
            if($rs){
                if($rs->num_rows>=0){
                    $product = $rs->fetch_assoc();
                    return $product;
                }
                else{
                    $msg = 'Product not found on table';
                    return $msg;
                }
            }
            else{
                echo"Error executing query:".$qry;
            }
        }
        else{
            $qry = "SELECT ProductsID, ProductsTitle, ProductsImages, ProductsSummary, ProductsContent FROM productsc";
            $rs = $this->db->query($qry);
            if($rs){
                if($rs->num_rows>0){
                    $products = array();
                    while($product = $rs->fetch_assoc()){
                        $products[] = $product;
                    }
                    return $products;
                }
                else{
                    $msg = 'The Product table is empty';
                    return $msg;
                }
            }
            else{
                echo"Error executing query:".$qry;
            }
        }
    }
    
   
    public function getComment($PID) ////////////////////////////////////////////////////////get comments for feedback table
    {
		
        $qry = "SELECT feedback.UserID, feedback.FeedBackID, feedback.ProductsID, feedback.Comments, feedback.Rating, user.FirstName FROM feedback, user WHERE ProductsID=$PID && feedback.UserID = user.UserID";
        $rs = $this->db->query($qry);
        if($rs){
            if($rs->num_rows>=0){
                $comments = array();
                while($comment = $rs->fetch_assoc()){
                    $comments[] = $comment;
                }
                return $comments;
            }
            else{
                $msg = 'Not comments record';
                return $msg;
            }
        }
        else{
            echo"Error executing query:".$qry;
        }
        
    }
    
   
    public function getGallery()  ///////////////////////////////////////////////get images gallery's images form gallery table
    {
        $qry = "SELECT GalleryID, GalleryImg, GalleryTitle, GalleryDescription FROM homegallery";
        $rs = $this->db->query($qry);
        if($rs){
            if($rs->num_rows>0){
                $gimgs = array();
                while($gimg = $rs->fetch_assoc()){
                    $gimgs[] = $gimg;
                }
                return $gimgs;
            }
            else{
                $msg = 'Not comments record';
                return $msg;
            }
        }
        else{
            echo"Error executing query:".$qry;
        }
    }
    
    
    public function getNews($nid="")  ////////////////////////////////////////////////////get news record from homenews table
    {
        if($nid)
        {
            $qry = "SELECT NeID, NewsTitle, NeContentTXT FROM homenews where NeID = '$nid'" ;
            $rs = $this->db->query($qry);
            if($rs){
                    
                $new = $rs->fetch_assoc();
                return $new;
            }
                    
            
            else{
                echo"Error executing query:".$qry;
            }
        }
        else{
            $qry = "SELECT NeID, NewsTitle, NeContentTXT FROM homenews ORDER BY Newsdate DESC";
            $rs = $this->db->query($qry);
            if($rs){
                if($rs->num_rows>0){
                    $news = array();
                    while($new = $rs->fetch_assoc()){
                        $news[] = $new;
                    }
                    return $news;
                }
                else{
                    $msg = 'Not comments record';
                    return $msg;
                }
            }
            else{
                 echo"Error executing query:".$qry;
            }
        }
        
    }
    
   
    public function getPopular() ///////////////////////////////////////////////////////////////get most commented products
    {
        $qry = "SELECT count( FeedBackID ) AS Replies, feedback.ProductsID, ProductsTitle, ProductsImages FROM feedback, productsc WHERE feedback.ProductsID = productsc.ProductsID GROUP BY ProductsID ORDER BY Replies DESC LIMIT 3"; 
        $rs = $this->db->query($qry);
        if($rs){
            if($rs->num_rows>=0){
                $popular = array();
                while($ppl = $rs->fetch_assoc()){
                    $popular[] = $ppl;
                }
                return $popular;
            }
            else{
                $msg = 'Not popular product yet.';
                return $msg;
            }
        }
        else{
            echo"Error executing query:".$qry;
        }
    }
    
   
    public function createUser() /////////////////////////////////////////////////////////insert a new user into users table
    {
        if($_POST){
            extract($_POST);
        }
        if(!get_magic_quotes_gpc()){
            $Firstname = $this->db->real_escape_string($Firstname);
            $lastname = $this->db->real_escape_string($lastname);
            $email = $this->db->real_escape_string($email);
            $password = $this->db->real_escape_string($password);
            $city = $this->db->real_escape_string($city);
            $suburb = $this->db->real_escape_string($suburb);
            $hno_street = $this->db->real_escape_string($hno_street);
        }
        $password = sha1($password);
        $qry = "INSERT INTO user VALUES('', '$Firstname', '$lastname', '$email', '$password', '$city', '$suburb', '$hno_street', 'User')";
        $rs = $this->db->query($qry);
        if($rs){
            $result['msg'] = 'New user has been created.';
            return $result;
        }
        else{
            echo "Error creating user record, email address already exist".$qry;
            return false;
        }    
    }
    
    
    public function newComment()  ///////////////////////////////////////////////////////insert new comment in to feedback table
    {
        if($_POST){
            extract($_POST);
        }
        $UserID = $_SESSION['UserID'];
        $qry = "INSERT INTO feedback VALUES('', '$pid', '$UserID', '$Comment', '$Rating')";
        $rs = $this->db->query($qry);
        if($rs){
            $result['msg'] = 'New comments has been successfully inserted.';
            return $result;
        }
        else{
            echo "Error inserting new comments.".$qry;
            return false;
        }
    }
    
   
    public function insertProduct($product) ////////////////////////////////////////////////insert a new product into products table
    {
        if($product){
            extract($product);
        }
        if(!get_magic_quotes_gpc()){
            $ProductsTitle = $this->db->real_escape_string($ProductsTitle);
            $ProductsSummary = $this->db->real_escape_string($ProductsSummary);
            $ProductsContent = $this->db->real_escape_string($ProductsContent);
        }
        $qry = "INSERT INTO productsc VALUES('', '$ProductsTitle', '', '$ProductsContent', '$ProductsSummary')";
        $rs = $this->db->query($qry);
        if($rs){
            $result['msg'] = 'Product record created.';
            $result['PID'] = $this->db->insert_id;
        }
        else{
            echo "Error inserting product:".$qry;
            return false;
        }
        return $result;
    }
    
    
    public function updateNews($news)  ///////////////////////////////////////////////////////////////to update news on home page
    {
        if($news){
            extract($news);
        }
        if(!get_magic_quotes_gpc()){
	   $NewsTitle = $this->db->real_escape_string($NewsTitle);
            $NeContentTXT = $this->db->real_escape_string($NeContentTXT);
        }
        $qry = "UPDATE homenews SET NewsTitle = '$NewsTitle', NeContentTXT = '$NeContentTXT' WHERE NeID = '$NID'";
        $rs = $this->db->query($qry);
        if($rs){
            $result['msg'] = 'update success.';
        
        }
        else{
            echo "Error inserting product:".$qry;
            return false;
        }
        return $result;
    }
    
   
    public function updateProductImagePath($PID, $PImg) //////////////////////////to update products' images path from product table
    {
        $qry = "UPDATE productsc SET ProductsImages = '$PImg' WHERE ProductsID = '$PID'";
        $rs = $this->db->query($qry);
        if($rs){
            return true;
        }
        else{
            echo"Error updating product image path:".$qry;
            return false;
        }
    }
    
   
    public function updateProduct($product) //////////////////////////////////////////////////////////////to update product details
    {
        extract($product);
        if (!get_magic_quotes_gpc()) {
            $ProductsTitle = $this->db->real_escape_string($ProductsTitle);
            $ProductsSummary = $this->db->real_escape_string($ProductsSummary);
            $ProductsContent = $this->db->real_escape_string($ProductsContent);
        }
        $qry = "UPDATE productsc SET ProductsTitle='$ProductsTitle', ProductsContent='$ProductsContent', ProductsSummary='$ProductsSummary', ProductsImages='$PImage' WHERE ProductsID='$ProductsID'";
        $rs = $this->db->query($qry);
        if ($rs) {
            $msg = "Product updated successfully";
            return $msg;
        }
        else {
            echo "Error executing query ".$qry;
            return false;
        }		
    }
    
    
    public function deleteProduct($PID) /////////////////////////////////////////////////to delete product record from products table
    {
        //print_r($PID);
        $qry = "DELETE FROM productsc WHERE ProductsID = '$PID'";

        $rs = $this->db->query($qry);
        if ($rs) {
            $result['ok'] = true;
            $this->deleteAllComments($PID);
            $result['msg'] = 'Product successfully deleted!';
        }
        else {
            $result['ok'] = false;
            $result['msg'] = 'Error deleting product: '.$qry;
        }
        return $result;
    }
    
    
    public function deleteAllComments($PID) ////////////////////////////to delete all comments of one product(when admin delete one product)
    {
        //print_r($PID);
        $qry = "DELETE FROM feedback WHERE ProductsID = '$PID'";
        $rs = $this->db->query($qry);
        if ($rs) {
            $result['ok'] = true;
        }
        return $result;
    }

    
    public function deleteComment($CID)  /////to delete each diffrent comment(admin can delet all comment other can delete their own comment)
    {
        print_r($CID);
        $qry = "DELETE FROM feedback WHERE FeedBackID = '$CID'";
        $rs = $this->db->query($qry);
        if ($rs) {
            $result['ok'] = true;
            $result['msg'] = 'Comment successfully deleted!';
        }
        else {
            $result['ok'] = false;
            $result['msg'] = 'Error deleting comment: '.$qry;
        }
        return $result;
    }
    
    
    public function getSearchContents($Skeyword)  //////////////////////////////////////////////////////to get search result from product table
    {
        $Skeyword = $this->db->real_escape_string($Skeyword);
        $qry = "SELECT ProductsID, ProductsTitle, ProductsSummary FROM productsc WHERE ProductsSummary LIKE '%$Skeyword%' || ProductsTitle LIKE '%$Skeyword%'";
        $rs = $this->db->query($qry);
        
        if($rs){
            if($rs->num_rows > 0){
                $Sresults = array();
                while($result = $rs->fetch_assoc()){
                    $Sresults[] = $result;
                }
                return $Sresults;
            } 
        }
        else{
            die('Error executing query:'.$qry);
        }
    }

}
?>