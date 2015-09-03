<?php
/**
  Author: Musavir Ifitkahr:
 * April 27 2015
 * kuala lumpur Malaysia
 */
 /**
 LANDING PAGE:
 */

class IndexController extends Zend_Controller_Action {
    private $baseurl = '';
	var $user_session = null;
	private $db = null;
    private $cookie = null;
    private $mail = null;
	private $video = null;
	private $results = null;
	private $contact = null;
	private $comments = null;
	private $testimonial = null;
	private $mainBanner = null;
	private $social = null;
	private $photos = null;

    public function init() {
		$this->_helper->layout->setLayout('layout');
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
		$this->video = new Application_Model_Video();
		$this->testimonial = new Application_Model_Testimonials();
		/*$this->contact = new Application_Model_Contact();
		$this->comments = new Application_Model_comment();*/
		//$this->mail = new Application_Model_Email();
	    $this->photos = new Application_Model_Photos();
	    $this->mainBanner = new Application_Model_Banner();
		$this->social = new Application_Model_SocialLinks();
		}


    public function indexAction() {

	//get landing page text
	$mptext = new Application_Model_Mpr();
	$this->view->row_text = $mptext->getText();

	// for banner
	$banner = new Application_Model_Banner();
	$this->view->banner = $banner->getMainBanner();

	//for social links
	$links =  new Application_Model_SocialLinks();
	$this->view->links = $links->getSocialLinks();

	//for strip banner
	$strip = new Application_Model_StripBanner();
	$this->view->strip = $strip->getMainStripBanner();
	


	//for videos
    $results = $this->video->getFeaturedVideos();
	  	if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}

	//for testimonial is featured

	 $result = $this->testimonial->getFeaturedTestimonial();
	 $this->view->i = 1;
	 $this->view->list = $result;
	}

	public function moreTestimonialsAction(){

			//for social links
		$links =  new Application_Model_SocialLinks();
		$this->view->links = $links->getSocialLinks();

	  $results = $this->testimonial->getAllTestimonials();
	  $this->TestimonialPaginator($results);
	}


	public function videosAction(){

		//for social links
	$links =  new Application_Model_SocialLinks();
	$this->view->links = $links->getSocialLinks();

	$id = $this->_request->getParam('id');

if(!isset($id) || $id < 1){
	$this->view->main_video = $this->video->getMainVideo();
}
else{
$this->view->main_video = $this->video->getVideo($id);
}
 	$results = $this->video->getAllVideos();
	$this->VideoPaginator($results);

//	$this->view->list = $results;


		}


   public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(3);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

public function VideoPaginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(12);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

	public function TestimonialPaginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(4);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

	public function BannerPaginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(4);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

public function savevAction(){
$this->ajaxed();
 $url_video = $this->getRequest()->getParam('url_video');

echo $url_video;
return;
$data = array ("v_id" => 15,'url_video' => $url_video);
$results = $this->video->updateVideo($this->db, $data);

}

 public function mainBannerAction(){

	$id = $this->_request->getParam('banner_id');

if(!isset($id) || $id < 1){
	$this->view->main_banner = $this->banner->getMainBanner();
}
else{
$this->view->main_banner = $this->video->getBanner($id);
}
 	$results = $this->banner->getAllBanners();
	$this->BannerPaginator($results);
}

		public function siteMapAction() {
			$links =  new Application_Model_SocialLinks();
	$this->view->links = $links->getSocialLinks();
			}

		public function galleryAction(){
		$links =  new Application_Model_SocialLinks();
		$this->view->links = $links->getSocialLinks();

			$results = $this->photos->getAllPhotos();
			$this->view->images = $results;
		}

		public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }

 public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()
            )return; // if not a ajax request leave function

    }
}
//.end of class
