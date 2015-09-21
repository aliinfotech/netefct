<?php

class Application_Model_Photos extends Zend_Db_Table
{ 
    protected $_name = 'photos';
    protected $_primary = 'photo_id';
    protected $result = null;
	

	public function getPhotoByID($id){
	 $select = $this->select();
	 $select->from($this)->where("photo_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
 
 // add new banner
public function addPhoto($formData) {
	
 $data = array('photo_name' => $formData['photo_name'],'caption' => $formData['caption']
 	,'description' => $formData['description'],'link' => $formData['link'],
 	'pg_cat_id' => $formData['category']);
 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>New Photo Added Successfully </div>" ;
		}  else {
			return "Some error in saving record";
		}
   }
 
 
     // for get all Photos by decending order
   public function getAllPhotos(){
	$select = $this->select();
	$select->from($this)->order("photo_id DESC");
	$result = $this->fetchAll($select);
	return $result;
	 }

	  // for get all Photos
   public function getAllGalleryPhotos(){
	$select = $this->select();
	$select->from($this);
	$result = $this->fetchAll($select);
	return $result;
	 }
  
  public function editPhoto($formData)
  {	  
	  $data = array('photo_name' => $formData['photo_name'],'caption' => $formData['caption'],
 	'description' => $formData['description'],'link' => $formData['link'],'pg_cat_id' => $formData['category']);
     $where = $this->getAdapter()->quoteInto('photo_id = ?',$formData['photo_id']);
	 $result = $this->update($data,$where);
	 if($result){
			return  "<div class='alert alert-success'>Gallery Photo Updated Successfully </div>" ;
		}  else {
			return "<div class='alert alert-danger'>Some error in updating record</div>";
		}
	 return $result;
  }

}