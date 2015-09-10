<?php

class Application_Model_Photos extends Zend_Db_Table
{ 
    protected $_name = 'photos';
    protected $_primary = 'photo_id';
    protected $result = null;
	

	public function getPhoto($id){
	 $select = $this->select();
	 $select->from($this)->where("photo_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
 
 // add new banner
public function addPhoto($formData) {
	
 $data = array('photo_name' => $formData['photo_name']);
 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>New Photo Added Successfully </div>" ;
		}  else {
			return "Some error in saving record";
		}
   }
 
 
     // for get all Photos
   public function getAllPhotos(){
	$select = $this->select();
	$select->from($this);
	$result = $this->fetchAll($select);
	return $result;
	 }
  
  public function editPhoto($formData)
  {	  
	 $data = array('photo_name' => $formData['photo_name']);
     $where = "photo_id= ". $formData['photo_id'];
	 $result = $this->update($data,$where);
	 return $result;
  }
  
   //for delete Photo image data
  public function removeImage($photo_id){
        $where = "photo_id = " . (int) $photo_id;
    $id = $this->delete($where);
    if($id > 0){
        return true;
    }else{
        return false;
    }
 }

}
?>