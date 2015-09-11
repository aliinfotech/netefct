<?php
 
class Application_Model_PGCategory extends Zend_Db_Table
{ 
    protected $_name = 'pg_categories';
    protected $_primary = 'pg_cat_id';
    protected $result = null;
  
 
 public function getCategoryByID($id){
	 $select = $this->select();
	 $select->from($this)->where("pg_cat_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
 
   // add new photo category
 public function addPhotoCategory($formData) {

 $data = array('category_name' => $formData['category_name'], 'banner' => $formData['banner']);			 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>New Category Added Successfully </div>" ;
		}  else {
			return "Some error occurred in Creating a New Photo Category";
		}
   }
 
   public function editCategory($formData)
  {
	$data = array('banner' => $formData['banner'],
	'category_name' => $formData['category_name']);
      $where = $this->getAdapter()->quoteInto('pg_cat_id = ?',$formData['pg_cat_id']);
	 $result = $this->update($data,$where);
	 if($result){
			return  "<div class='alert alert-success'>Category Updated Successfully </div>" ;
		}  else {
			return "<div class='alert alert-danger'>Some error in updating record</div>";
		}
	 return $result;
  }
    // for delete categories
	public function deleteCategory($db,$id){

	$rowset   = $this->fetchAll();
	   $rowCount = count($rowset);
	   if($rowCount < 2 || $rowCount == 1) return 3;

		$id = $this->delete($db->quoteInto("pg_cat_id = ?", $id));
		if($id > 0){
			return 1;
		}else{
			return 2;
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
    public function getAllCategories(){
     $select = $this->select();
     $select->from($this);
     $result = $this->fetchAll($select);
     return $result;
   }

}
?>
