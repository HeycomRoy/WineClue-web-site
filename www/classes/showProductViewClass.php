<?php

class ShowProductView extends View  //////////////////////This class contain method for display each product information
{
    protected $model;
    private $PID;
    private $product;
    private $comments;

    
    public function __construct($rs, $model, $PID)
    {
        $this->rs = $rs;
        $this->model = $model;
        $this->PID = $PID;

    }
    
    
    protected function displayContent() /////////////////////////to display content of show each product page
    {
	
        $html = '<div id="RightSide">'."\n";
        $html .= '<div id="ShowProductZone">'."\n";
        $html .= '<h3 class="SmallTitle">'.$this->rs['PageHeading'].'</h3>'."\n";
        
		$this->product = $this->model->getProducts($this->PID); ///////////////////////////Useing "getProducts" this function from dbaseClass.
	
	
	if($_POST['submit']){  //////////////////////////////Validate Comments input box
	    $this->result = $this->model->validateCommentsEntries();

	    if(!$this->result['commentMsg'] && !$this->result['ratingMsg']){
		$this->result = $this->model->newComment();
	    }
        }
		
	$html .= $this->showProduct();
	
        $html .= '</div></div>'."\n";
	
	
        return $html;
    }
    
    private function showProduct()
    {
		if(is_array($this->product)){
            extract($this->product);
        }   
	  
        if($_POST['submit']){
            extract($_POST);
        }
        
		$html = '<div class="ShowProduct"><div class="PicDisplay">'."\n";
        $html .= '<img src="images/img/big_'.$ProductsImages.'" alt="'.$ProductsTitle.'"/></div>'."\n";
        $html .= '<div class="ProductDetail"><h3>'.stripslashes($ProductsTitle).'</h3><p>'.stripslashes($ProductsContent).'</p></div></div>'."\n";
	
	
        if(isset($_SESSION['User'])){  /////////////////////////////////////this area is for commentting and rating
	    $html .= '<div class="CommentZone">'."\n";
	    $html .= '<form method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'">'."\n";
	    $html .= '<label for="CommentArea" class="CommentAreaTitle">Comments:</label>'."\n";
	    $html .= '<textarea name="Comment" id="CommentArea" rows="6" cols="20">'.htmlentities(stripslashes($Comment),ENT_QUOTES).'</textarea>'."\n";
	    $html .= '<input type="hidden" name="pid" value="'.$this->PID.'"/>'."\n";
	    $html .= '<label for="Rating" class="RatingTitle">Give a rating:</label>'."\n";
	    $html .= '<select id="Rating" name="Rating">'."\n"; 
	    $html .= '<option value="'.$Rating.'" selected="selected">'.$Rating.'</option>'."\n";
	    for ($i=1; $i<6; $i++) {
		$html .= '<option value="'.$i.'">'.$i.'</option>'."\n";
	    }
	    $html .= '</select>'."\n";
	    $html .= '<input type="submit" name="submit" value="Send" class="sub"/></form>'."\n";
	    $html .= '<div class="col3">'.$this->result['commentMsg'].'</div>'."\n";
	    $html .= '<div class="col3">'.$this->result['ratingMsg'].'</div>'."\n";
	    $html .= '<div class="col3">'.$this->result['msg'].'</div></div>'."\n";
	}

	
	
	$html .= '<div class="CommentDisplay">'."\n";        /////////////////////////////////////////////Display comments
	$html .= '<h2 id="ComTile">Comments</h2>'."\n";
	$comments = $this->model->getComment($this->PID);
	if(is_array($comments)){
	    foreach($comments as $comment){
		$html .= '<div class="Uname" style=" font-size:1.2em;">'.$comment['FirstName'].'&nbsp;</div>'."\n";
		$html .= '<div class="Ra"> gave this product a rating of '.$comment['Rating'].' </div>'."\n";
		if($_SESSION['UserType'] == 'Admin' || $comment['UserID'] == $_SESSION['UserID']){
		    $html .= '<a href="index.php?pageID=DeleteComment&amp;PID='.$this->PID.'&amp;CID='.$comment['FeedBackID'].'" class="deleteLink"><span>Delete</span></a>'."\n";
		}
		$html .= '<br />'."\n";
		$html .= '<div class="recomment">'.stripslashes($comment['Comments']).'</div>'."\n";
	    }
	}
	$html .= '</div>'."\n";
        return $html;
    }
    
	
}
?>