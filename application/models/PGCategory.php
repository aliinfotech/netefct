<?php
 
class Application_Model_PGCategory extends Zend_Db_Table
{ 
    protected $_name = 'pg_categories';
    protected $_primary = 'pg_at_id';
    protected $result = null;
  
 
 public function getCategoryByID($id){
	 $select = $this->select();
	 $select->from($this)->where("pg_at_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
 
   // add new photo category
 public function addPhotoCategory($formData) {

 $data = array('category_name' => $formData['category_name'], 'banner' => $formData['banner']);			 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>New Photo Category Added Successfully </div>" ;
		}  else {
			return "Some error occurred in Creating a New Photo Category";
		}
   }
 
    public function updateCategory($formData){

	$data = array("category" => $formData['category']);
	$where = "category_id = " . (int) $formData["id"];
	$this->id=$this->update($data,$where);

	if($this->id){
		return  "<div class='alert alert-success'> ".$formData['category'] ." Update Successfully </div>" ;
	}  else {
		return "<div class='alert alert-danger'>Some error in update record</div>";
	} 
	}

    // for delete categories
	public function deleteCategory($id){

	$where = "category_id = " . (int) $id;
    $id = $this->delete($where);
    if($id > 0){
        return true;
    }else{
        return false;
    }  
	}
	 
   // for check categoery name
	public function checkCategoryName($category){

	$select = $this->select();
	$select->from($this)->where('category_name = ?', $category);
	$result = $this->fetchRow($select);
	if(is_object($result)){
		return true;
		}else return false; 
	} 

 
     // for get all categories 
 public function getAllCPhotoategories(){
$select = $this->select();
$select->from($this, array('category_id','category'));
$result = $this->fetchAll($select);
return $result;
 }
  
 
 
  
}
?>
