 <?php
 class Application_Model_TextBlocks extends Zend_Db_Table
{
    protected $_name = 'text_blocks';
    protected $_primary = 'tb_id';

 public function getTextBlock($id){
	 $select = $this->select();
	 $select->from($this)->where("tb_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }

 // For get all Text Block
 public function getAllTextBlocks(){
$select = $this->select();
$select->from($this);
$result = $this->fetchAll($select);
return $result;
 }

 public function editTextBlock($formData)
  {
    $data = array('tb_name' => $formData['tb_name'], 'tb_text' => $formData['tb_text']);
    $where = $this->getAdapter()->quoteInto('tb_id = ?',$formData['tb_id']);
     $result = $this->update($data,$where);
	  if($result){
			return  "<div class='alert alert-success'>Text Block Updated Successfully </div>" ;
		}  else {
			return "<div class='alert alert-danger'>Some error in updating record</div>";
		}
	 return $result;
  }
}