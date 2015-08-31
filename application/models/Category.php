<?php
 
class Application_Model_Category extends Zend_Db_Table
{ 
    protected $_name = 'category';
    protected $_primary = 'category_id';
    protected $result = null;
  
 
 public function getCategoryByID($id){
	 $select = $this->select();
	 $select->from($this)->where("category_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
 
 // add new page
public function addCategory($formData) {
	
	
 $data = array('category' => $formData['category']);
				 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>New Category Added Successfully </div>" ;
		}  else {
			return "Some error occurred in Creating a Category";
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
	$select->from($this)->where('category = ?', $category);
	$result = $this->fetchRow($select);
	if(is_object($result)){
		return true;
		}else return false; 
	} 

 
     // for get all categories 
 public function getAllCategories(){
$select = $this->select();
$select->from($this, array('category_id','category'));
$result = $this->fetchAll($select);
return $result;
 }
  
 
 
  
}
?>
