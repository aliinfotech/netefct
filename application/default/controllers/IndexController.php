<?php

class IndexController extends Zend_Controller_Action {
    private $baseurl = '';
	var $user_session = null;
	private $db = null;
    private $cookie = null;
    private $text_block = null;
    private $results = null;
   	private $slides = null;
   	private $image_block = null;
    private $gallery = null;

    public function init() {
		$this->_helper->layout->setLayout('layout');
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
		$this->text_block = new Application_Model_TextBlocks();
		$this->image_block =  new Application_Model_ImageBlocks();
		$this->slides =  new Application_Model_Sliders();
        $this->gallery =  new Application_Model_Photos();
		}


    public function indexAction() {
		
	$results = $this->slides->getAllSlides();
	$this->view->list = $results;
		/*for text blocks*/
	$text_block =  new Application_Model_TextBlocks();
	$this->view->text_block = $text_block->getAllTextBlocks();	

		/*for image blocks*/
	$image_block =  new Application_Model_ImageBlocks();
	$this->view->image_block = $image_block->getAllImageBlocks();

    /*for gallery*/
    $gallery =  new Application_Model_Photos();
    $this->view->gallery = $gallery->getAllGalleryPhotos();
	
	}
	

/*
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


		}*/


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
