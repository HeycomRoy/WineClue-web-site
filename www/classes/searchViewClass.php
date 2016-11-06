<?php

class SearchView extends View ////////////////////////////////////////////////////////*This class contain method forsearch result display*/
{
    
    protected $model;
    private $sResults;
    
    public function __construct($rs, $model, $Skeyword)
    {
        $this->rs = $rs;
        $this->model = $model;
        $this->Skeyword = $Skeyword;
        $this->sResults = $sResults;
    }
    
   
    protected function displayContent() //////////////////////////////////////////////////////////////////////display search page content
    {
        $html = '<div id="RightSide" style="overflow: scroll; height:1100px;">'."\n";
        $html .= '<div class="ProductsShowZone">'."\n";
        $html .= '<h3 class="SmallTitle">'.$this->rs['PageHeading'].'</h3>'."\n";
        $this->sResults = $this->model->getSearchContents($this->Skeyword);
        if($this->sResults['msg']){
            //echo $this->sResults['msg'];
            $html .= '<div class="pagemsg">'.$this->sResults['msg'].'</div>';
        }
        else {
            $html .= $this->displayResult();
        }
        $html .= '</div></div>'."\n";
        return $html;
    }
    
   
    private function displayResult() ///////////////////////////////////////////////////////////////////////////display search results
    {
        $html = '<ul>'."\n";
        if(is_array($this->sResults)){
            foreach($this->sResults as $sResult){
                $html .= '<li class="SearchResultDisplay"><a href="index.php?pageID=ProductsCollec&amp;PID='.$sResult['ProductsID'].'">'."\n";
                $html .= '<div class="SearchPContent"><a class="Stitle" href="index.php?pageID=ProductsCollec&amp;PID='.$sResult['ProductsID'].'">'.$sResult['ProductsTitle'].'</a>'."\n";
                $html .= '<p>'.$sResult['ProductsSummary'].'</p>'."\n";
                $html .= '<a href="index.php?pageID=ProductsCollec&amp;PID='.$sResult['ProductsID'].'" class="moreLink">More</a>'."\n";                    
                $html .= '</div></li>'."\n";
            }
        }
        else{
            $html .= '<div id="SMessage"><p>No match result has been found!</p></div>'."\n";
        }
        $html .= '</ul>'."\n";
        return $html;
    }
}

?>