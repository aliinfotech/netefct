 <?php
 class Application_Model_ImageBlocks extends Zend_Db_Table
{
    protected $_name = 'image_blocks';
    protected $_primary = 'ib_id';

 public function getByID($id){
	 $select = $this->select();
	 $select->from($this)->where("ib_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }

 // For get all Text Block
 public function getBlocks(){
$select = $this->select();
$select->from($this);
$result = $this->fetchRow($select);
return $result;
 }

}