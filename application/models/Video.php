<?php

class Application_Model_Video extends Zend_Db_Table
{ 
    protected $_name = 'videos';
    protected $_primary = 'v_id';
    protected $result = null;


	public function getVideo($id){
	$select = $this->select();
	$select->from($this)->where("v_id = ?", $id);
	$result = $this->fetchRow($select);
	return $result; 
	 }


	public function getMainVideo(){
	$select = $this->select();
	$select->from($this)->where("is_main = ?", 1);
	$result = $this->fetchRow($select);
	return $result; 
	 }


	public function getAllVideos(){
	$select = $this->select();
	$select->from($this)->order("is_featured DESC");
	$result = $this->fetchAll($select);
	return $result; 
	 }

	public function getFeaturedVideos(){
	$select = $this->select();
	$select->from($this)->where("is_featured = ?", 1)->order("is_featured");
	$result = $this->fetchAll($select);
	return $result; 
	 }
 
	 

	// add new video link
	public function addVideo($formData) {
		
	 $data = array('title' => $formData['title'],
					'url_video' => addslashes($formData['url_video']),
					'short_description' => $formData['short_description'],
					'is_featured' => $formData['is_featured'],
					'is_main' => $formData['is_main'],
					'video_img' => $formData['video_img']
					);
	 
	 $result = $this->insert($data); 
	// $main_video = $this->getMainVideo();
	 if ($result) {
	 // if no main video make this as main video 
//if(count($main_video) < 1)$this->makeMain($formData['video_id']);
		return  "<div class='alert alert-success'>New Video Added Successfully </div>" ;
			 }else{
//if(count($main_video) < 1)$this->makeMain($formData['video_id']);
				return "Some error in saving record";
	 }
	 

	   }
	  
	   public function removeVideo($db, $id){
	   
	   $rowset   = $this->fetchAll();
	   $rowCount = count($rowset);
	   if($rowCount < 2 || $rowCount == 1) return 3;

		$id = $this->delete($db->quoteInto("v_id = ?", $id));
		if($id > 0){
			return 1;
		}else{
			return 2;
		}
	 }

 	public function updateVideo($formData){

	 $data = array('title' => $formData['title'],
	 'short_description' => $formData['short_description'],
	 'url_video' => $formData['url_video'],
	 'video_img' => $formData['video_img'],
	 'is_featured' => $formData['is_featured'],
	 'is_main' => $formData['is_main']);
	 
	// 'main_video' => $formData['main_video']);
	  $where = "v_id= ". $formData['v_id'];
	 $result = $this->update($data,$where);
	$main_video = $this->getMainVideo();
	 if ($result > 0) {
	 // if no main video make this as main video 
if(count($main_video) < 1)$this->makeMain($formData['v_id']);
	 return true; 
			 }else{
if(count($main_video) < 1)$this->makeMain($formData['v_id']);
			 return false; 
		 }
		  }

public function makeMain($v_id){
	 $data = array('is_main' => 1);
	  $where = "v_id= ". $v_id;
	 $result = $this->update($data,$where);
	
}		  
	/* 
	 public function updateVideo($v_id, $url_video){
	 $where['v_id = ?'] = $v_id;
	 $data = array('url_video' => $url_video);
	 $result = $this->update($data,$where);
	 if ($result > 0) {
			 return true; 
	 }else{
		 return false; 
		 }
		  }
*/



}