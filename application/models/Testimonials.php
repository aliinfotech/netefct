<?php
 
class Application_Model_Testimonials extends Zend_Db_Table
{ 
    protected $_name = 'testimonials';
    protected $_primary = 'test_id';
    protected $result = null;
  
 
 public function getTestimonial($id){
	 $select = $this->select();
	 $select->from($this)->where("test_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
 
 // add new testimonial
public function addTestimonials($formData) {
	
if(isset($formData['is_featured']) && $formData['is_featured'] == true){
 $data = array('is_featured' => 0);
 $result = $this->update($data);	
}	
	
 $data = array('first_name' => $formData['first_name'],
				'last_name' => $formData['last_name'],
				'is_featured' => $formData['is_featured'],
				'short_description' => $formData['short_description'],
				'email' => $formData['email'],
				'image1' => $formData['image1']
				);
 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>New Testimonial Added Successfully </div>" ;
		}  else {
			return "Some error in saving record";
		}
   }
 
 
     // for get all testimonials
 public function getAllTestimonials(){
$select = $this->select();
$select->from($this, array('test_id','email','first_name','short_description','last_name','image1','is_featured'));
$result = $this->fetchAll($select);
return $result;
 }
  
  // for specifically for landing page is_faetured 1
  
public function getFeaturedTestimonial(){
		$select = $this->select();
	$select->from($this)->where("is_featured = 1")->order('test_id DESC');
	$result = $this->fetchAll($select);   
		 return $result; 
	}


 public function getLastInsertRecord()
{
$select = $this->select();
$select->from($this)->where("is_featured = 1")->order('test_id DESC');
$result = $this->fetchRow($select);
return $result;
}	
 

  public function editTestimonial($formData)
  {
	  
	if(isset($formData['is_featured']) && $formData['is_featured'] == true){
 $data = array('is_featured' => 0);
 $result = $this->update($data);	
}	
  
	  
	 $data = array('first_name' => $formData['first_name'],
	'last_name' => $formData['last_name'],
	'email' => $formData['email'],
	'is_featured' => $formData['is_featured'],
	'image1' => $formData['image1'],	
	'short_description' => $formData['short_description']);
     $where = "test_id= ". $formData['t_id'];
	 $result = $this->update($data,$where);
	 return $result;
  }
  
/* public function editTestimonial($id, $formData){
	 
	 $data = array('first_name' => $formData['first_name'],
	'last_name' => $formData['last_name'],
	'email' => $formData['email'],
	'is_featured' => $formData['is_featured'],
	'image1' => $formData['image1'],	
	'short_description' => $formData['short_description']);
     //$where['test_id = ?'] = $id; 
	 $where['test_id = ?'] = $id;
	 $result = $this->update($data,$where);
	 if ($result > 0) {
			 return true; 
	 }else{
		 return false; 
		 }
		  }*/


   public function removeTestimonial($db, $id){
	   
	   $rowset   = $this->fetchAll();
	   $rowCount = count($rowset);
	   if($rowCount < 2 || $rowCount == 1) return 3;

		$id = $this->delete($db->quoteInto("test_id = ?", $id));
		if($id > 0){
			return 1;
		}else{
			return 2;
		}
	 }
  
}
?>
