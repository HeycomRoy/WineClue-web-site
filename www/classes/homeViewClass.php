<?php

class HomeView extends View /////////////////This class is contain methods to display home page of wine club
{
    
    protected function displayContent() ////////////display right content images galery, and latest news
    {
        $html = '<div id="RightSide">'."\n";
        
        $html .= '<div id="gallery" >'."\n";
        $gimgs = $this->model->getGallery(); ////////////to get record from database(for the images gallery)
        foreach($gimgs as $gimg){
            $html .= '<a href="#" class="show">'."\n";
            $html .= '<img src="images/images/'.$gimg['GalleryImg'].'" alt="'.$gimg['GalleryTitle'].'" width="550" height="330" title="" rel="<h3>'.$gimg['GalleryTitle'].'</h3>'.$gimg['GalleryDescription'].'" /></a>'."\n";
        }
        $html .= '<div class="caption">'."\n";
        $html .= '<div class="content"></div></div>'."\n";
        $html .= '</div>'."\n";
        $html .= '<div class="clear"></div>'."\n";
        
        $html .= '<div id="NewsNDEvents">'."\n";
        $html .= '<h3 class="SmallTitle">News And Events</h3>'."\n";
        $news = $this->model->getNews();  ////////////to get record from database(for the news)
        foreach($news as $new){
            $html .= '<div class="newsEvents"><p class="NewsTitle">'.stripslashes($new['NewsTitle']).'</p>'."\n";

            if($_SESSION['UserType'] == 'Admin'){
                $html .= '<a href="index.php?pageID=EditNews&amp;NID='.$new['NeID'].'" class="editLink"><span>Edit</span></a></div>'."\n";
            }
            else{
                $html .= '</div>'."\n";
            }
            $html .= '<p class="News">'.stripslashes($new['NeContentTXT']).'</p>'."\n";
        }
        $html .= '</div>'."\n";
        $html .= '</div>'."\n";
        return $html;

    }
}
?>