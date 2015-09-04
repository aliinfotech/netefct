 <?php
 
class Application_Model_TextBlocks extends Zend_Db_Table
{ 
    protected $_name = 'text_blocks';
    protected $_primary = 'tb_id';
    protected $result = null;
  
 
 public function getTextBlock($id){ 
	 $select = $this->select();
	 $select->from($this)->where("tb_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
 
 
 
     // for get all Banner
 public function getAllTextBlocks(){
$select = $this->select();
$select->from($this, array('tb_id','tb_name','tb_text'));
$result = $this->fetchAll($select);
return $result;
 }
  
   public function getMainStripBanner(){
	$select = $this->select();
	$select->from($this)->where("is_main = ?", 1);
	$result = $this->fetchRow($select);
	return $result; 
	 }
  

 

  public function editTextBlock($formData)
  {
	  
	 $data = array('tb_name' => $formData['tb_name'],
	'tb_text' => $formData['tb_text']);
    
     $where = "tb_id= ". $formData['tb_id'];
	 $result = $this->update($data,$where);
	  if($result){
			return  "<div class='alert alert-success'>Text Block Updated Successfully </div>" ;
		}  else {
			return "Some error in updating record";
		}
	 return $result;
  }
  

  
}
?>
