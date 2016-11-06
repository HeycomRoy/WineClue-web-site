<?php

class DeleteCommentView extends View  ////////////////////This class is for delete comment view contain all method to delete each comment
{
    protected $model;
    private $CID;
    private $msg;
    
    public function __construct($rs, $model, $CID, $PID)
    {
        $this->rs = $rs;
        $this->model = $model;
        $this->CID = $CID;
        $this->PID = $PID;
    }
    
    
    protected function displayContent()   ////////////////////////////to display content
    {
        $html = '<div id="RightSide">'."\n";
        $html .= '<div id="ShowProductZone">'."\n";
        $html .= '<h3 class="SmallTitle">'.$this->rs['PageHeading'].'</h3>'."\n";
        if(!($_SESSION['UserType'] != "Admin" || $this->CID != $_SESSION['UserID'])){
            $html .= '<div class="pageMsg">Sorry, but this is a restricted page!</div>'."\n";
        }
        else{
            if($_POST['yes']){
                $result = $this->model->deleteComment($this->CID);
                $html .= '<div class="pageMsg">'.$result['msg'].'</div>'."\n";
                $html .= '<meta http-equiv="refresh" content=2;URL="index.php?pageID=ProductsCollec&PID='.$this->PID.'"'."\n";
            }
            elseif($_POST['no']){
                $this->msg = 'No comment Has been Deleted.<meta http-equiv="refresh" content=2;URL="index.php?pageID=ProductsCollec&PID='.$this->PID.'"'."\n";
            }
            if(!$_POST['yes']){
                $html .= $this->displayDeleteCommentForm();
            }
        }
        $html .= '</div></div>'."\n";
        return $html;
    }
    
    
    private function displayDeleteCommentForm()  /////////////////////////////////display comment form
    {
        $html = '<div class="prdrow">'."\n";
        $html .= '<div class="prdDetails">'."\n";
        $html .= '<div class="delform">Do you want to delete this comment?<br />'."\n";
        $html .= '<form method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'">'."\n";
        $html .= '<input type="hidden" name="CID" value="'.$this->CID.'" />'."\n";
        $html .= '<input type="hidden" name="PID" value="'.$this->PID.'" />'."\n";
        $html .= '<input type="submit" name="yes" value="Yes" /> '."\n";
        $html .= '<input type="submit" name="no" value="No" />'."\n";
        $html .= '</form>'."\n";
        $html .= '</div>'."\n";
        $html .= '<p class="pageMsg">'.$this->msg.'</p>'."\n";
        $html .= '</div>'."\n";
        $html .= '</div>'."\n";
        return $html;
    }
    
}
?>