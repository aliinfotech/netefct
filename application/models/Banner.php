<?php
 
class Application_Model_Banner extends Zend_Db_Table
{ 
    protected $_name = 'banner';
    protected $_primary = 'banner_id';
    protected $result = null;
  
 
 public function getBanner($id){
	 $select = $this->select();
	 $select->from($this)->where("banner_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
 
 // add new banner
public function addBanner($formData) {
	
if(isset($formData['is_main']) && $formData['is_main'] == true){
 $data = array('is_main' => 0);
 $result = $this->update($data);	
}	
	
 $data = array('banner_img' => $formData['banner_img'],
				'target_url' => $formData['target_url'],
				'is_main' => $formData['is_main']);
 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>New Banner Added Successfully </div>" ;
		}  else {
			return "Some error in saving record";
		}
   }
 
 
     // for get all Banner
 public function getAllBanners(){
$select = $this->select();
$select->from($this, array('banner_id','banner_img','target_url','is_main'));
$result = $this->fetchAll($select);
return $result;
 }
  
  public function getMainBanner(){
	$select = $this->select();
	$select->from($this)->where("is_main = ?", 1);
	$result = $this->fetchRow($select);
	return $result; 
	 }
  

 public function getLastInsertedRecord()
{
$select = $this->select();
$select->from($this)->where("is_main = 1")->order('banner_id DESC');
$result = $this->fetchRow($select);
return $result;
}	
 

  public function editBanner($formData)
  {
	  
	if(isset($formData['is_main']) && $formData['is_main'] == true){
 $data = array('is_main' => 0);
 $result = $this->update($data);	
}	
  
	  
	 $data = array('banner_img' => $formData['banner_img'],
	'target_url' => $formData['target_url'],
		'is_main' => $formData['is_main']
		);
     $where = "banner_id= ". $formData['banner_id'];
	 $result = $this->update($data,$where);
	 return $result;
  }


   public function removeBanner($db, $id){
	   
	   $rowset   = $this->fetchAll();
	   $rowCount = count($rowset);
	   if($rowCount < 2 || $rowCount == 1) return 3;

		$id = $this->delete($db->quoteInto("banner_id = ?", $id));
		if($id > 0){
			return 1;
		}else{
			return 2;
		}
	 }
  
}
