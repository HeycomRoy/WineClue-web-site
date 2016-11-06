<?php


abstract class View /////////////////////////////////////////////////////////////This class is contain methods for the site main content construct
{
    protected $rs;
    protected $model;
    protected $result;
    
    public function __construct ($rs, $model)
    {
        $this->rs=$rs;
	$this->model=$model;
    }
    
    public function displayPage()
    {
	
	$this->result = $this->model->checkUserSession();/////////////////////////////////////////////To get function checkUserSession from model Class.
        $html = $this->displayHeader();
        $html .= $this->displayContent();
        $html .= $this->displayFooter();
        return $html;
    }
    

    abstract protected function displayContent();
    
    
    protected function displayHeader(){
        $html = $this->displayHtmlHeader();
        $html .= $this->displayBanner();
	$html .= $this->displaySearchBar();
	$html .= $this->displayNavBar();
	
	$html .= $this->displayContentLeft();
        return $html;
    }
    
    private function displayHtmlHeader()
    {
        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
	$html .= '<html xmlns="http://www.w3.org/1999/xhtml">'."\n";
	$html .= '<head>'."\n";
	$html .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />'."\n";
	$html .= '<meta name="description" content="'.$this->rs['PageDescription'].'" />'."\n";
	$html .= '<meta name="keywords" content="'.$this->rs['PageKeywords'].'" />'."\n";
	$html .= '<link type="text/css" href="css/style.css" rel="stylesheet" />'."\n";
	$html .= '<link type="text/css" href="css/imagesgallery.css" rel="stylesheet" />'."\n";
	/**/$html .= '<script type="text/javascript" src="js/jquery-1.3.1.min.js"></script>'."\n";
	$html .= '<script type="text/javascript" src="js/jsForIG.js"></script>'."\n";
	/**/$html .= '<!--[if IE]><style type="text/css">#SideBG{position:relative;} .LoginIB{margin-left:-60px;}</style><![endif]-->'."\n";
	$html .= '<title>'.$this->rs['PageTitle'].'</title>'."\n";
	$html .= '</head>'."\n";
	return $html;
    }
    
   
    private function displayBanner() ////////////////////////////////////////////////////////////////////display page banner
    {
	$html = '<body>'."\n";
	$html .= '<div id="banner">'."\n";
	$html .= '<div id="logo">'."\n";
	$html .= '<img src="images/WC_Logo.gif" alt="Wine Club"/>'."\n";
	$html .= '</div>'."\n";
	return $html;
    }
    
    
    private function displaySearchBar() ///////////////////////////////////////////////////////////////////display search bar
    {
        $html .= '<div id="searchBar">'."\n";
	$html .= '<form method="post" id="formSB" action="index.php?pageID=Search">'."\n";
	$html .= '<input id="search" type="text" name="Skeyword" maxlength="50" value="'.$_POST['Skeyword'].'" />'."\n";
	$html .= '<button type="submit" id="search_btton" ></button>'."\n";
	$html .= '</form>'."\n";
	$html .= '</div>'."\n";
	return $html;
    }
    
   
    private function displayNavBar() ///////////////////////////////////////////////////////////////////////display navigation
    {
	$pageArray = array('Home', 'WineCollection', 'Register', 'Contact');
	$navArray = array('Home', 'Products', 'Register', 'Contact');
	
	$numLinks = count($navArray);
	$html = '<div id="nav">'."\n";
	$html .= '<ul id="menu1">'."\n";
	if($_GET['pageID']){
	    $pageID = $_GET['pageID'];
	}
	else{
	    $pageID = 'Home';
	}
	for($i=0; $i<$numLinks; $i++){
	    $html .= '<li><a href="index.php?pageID='.$pageArray[$i].'" class="navgition">'."\n";
	    if($pageID == $pageArray[$i]){
		$html .= '<span>'.$navArray[$i].'</span></a></li>'."\n";
	    }
	    else{
		$html .= $navArray[$i].'</a></li>'."\n";
	    }
	    if($i<$numLinks-1){
		$html .= '<li><span class="break">|</span></li>'."\n";
	    }
	}
	$html .= '</ul>'."\n";
	$html .= '</div>'."\n";
	$html .= '</div>'."\n";
	return $html;
    }
    
   
    private function displayContentLeft() //////////////////////////////////////////////////////////////display left content
    {
	$html = '<div id="ContentBG">'."\n";
	$html .= '<div id="TopBG"></div>'."\n";
	$html .= '<div id="SideBG">'."\n";
	$html .= '<div id="Content">'."\n";
	$html .= '<div class="LeftSide">'."\n";
	$html .= '<div id="Login">'."\n";
	$html .= '<div class="SideCTitle">'."\n";
	if($_SESSION['User']){
	    $msg = 'Welcome '.$_SESSION['User'];
	}
	else{
	    $msg = 'Log-in';
	}
	$html .= '<h3 class="SmallTitle">'.$msg.'</h3>'."\n";
	$html .= '</div>'."\n";
	$html .= '<div class="BoxModel">'."\n";
	$html .= $this->displayLoginForm();
	$html .= '</div>'."\n";
	$html .= '</div>'."\n";
	$html .= '<div id="PopularPicks">'."\n";
	$html .= '<div class="SideCTitle">'."\n";
	$html .= '<h3 class="SmallTitle">Popular Picks</h3>'."\n";
	$html .= '</div>'."\n";
	$html .= '<div class="BoxModel">'."\n";
	
	$popular = $this->model->getPopular(); ///////////////////////////////////////////////////////////////// function for display popular picks
	foreach($popular as $p ){
            $html .= '<div class="PopularDisplay">'."\n";
	    $html .= '<div class="PopularT"><a href="index.php?pageID=ProductsCollec&amp;PID='.$p['ProductsID'].'">'.$p['ProductsTitle'].'</a></div>'."\n";
	    //$html .= '<br />'."\n";
	    $html .= '<div class="PopularI"><a href="index.php?pageID=ProductsCollec&amp;PID='.$p['ProductsID'].'">'."\n";
            $html .= '<img src="images/img/'.$p['ProductsImages'].'" alt="'.$p['ProductsTitle'].'"/></a></div>'."\n";			
            $html .= '</div>'."\n";
	}
	$html .= '</div>'."\n";
	$html .= '</div>'."\n";
	$html .= '</div>'."\n";
	return $html;
    }
    
  
    protected function displayLoginForm()  ////////////////////////////////////////////////////////////////////////display left content login form
    {
	
	$html = '<form method="post" id="formLI" action="'.htmlentities($_SERVER['REQUEST_URI']).'">'."\n";
	if(isset($_SESSION['User'])){
	    //$_POST['Login'] = 'Logout';
	    $html .= '<input id="Submit" type="submit" value="Logout" name="Logout"/>'."\n";
	}
	else{
	    $html .= '<label for="Email">Email Address:</label>'."\n";
	    $html .= '<div class="LoginBG"><input id="Email" class="LoginIB" type="text" name="userName" value="'.$_POST['userName'].'"/></div>'."\n";
	    $html .= '<label for="PassWord">PassWord:</label>'."\n";
	    $html .= '<div class="LoginBG"><input id="PassWord" class="LoginIB" type="password" name="userPassword"/></div>'."\n";
	    $html .= '<input id="Submit" type="submit" value="Login" name="Login"/>'."\n";
	}
	$html .= '</form>'."\n";
	$html .= '<div class="pgErrMsg">'.$this->result['errorMsg'].'</div>'."\n";
	
	return $html;
    }
    
   
    protected function displayFooter() ////////////////////////////////////////////////////////////////////////display footer navigation and copyright
    {
	$html = '</div>'."\n";
	$html .= '</div>'."\n";
	$html .= '<div id="BottonBG"></div>'."\n";
	$html .= '</div>'."\n";
	$pageArray = array('Home', 'WineCollection', 'Register', 'Contact');
	$navArray = array('Home', 'Products', 'Register', 'Contact');
	
	$numLinks = count($navArray);
	$html .= '<div id="nav_footer">'."\n";
	$html .= '<ul id="menu2">'."\n";
	if($_GET['pageID']){
	    $pageID = $_GET['pageID'];
	}
	else{
	    $pageID = 'Home';
	}
	for($i=0; $i<$numLinks; $i++){
	    $html .= '<li><a href="index.php?pageID='.$pageArray[$i].'" class="navgition">'."\n";
	    if($pageID == $pageArray[$i]){
		$html .= '<span>'.$navArray[$i].'</span></a></li>'."\n";
	    }
	    else{
		$html .= $navArray[$i].'</a></li>'."\n";
	    }
	}
	$html .= '</ul>'."\n";
	$html .= '</div>'."\n";
	$html .= '<div id="copyright"> Copyright &copy; 2010, Wine Club Ltd.'."\n";
	$html .= '<a href="cui.yao.yu@gmail.com">cui.yao.yu@gmail.com</a>'."\n";
	$html .= '</div>'."\n";
	$html .= '</body>'."\n";
	$html .= '</html>'."\n";
    
	return $html;
    }

}
?>