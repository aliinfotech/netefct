 <?php
 class Application_Model_ImageBlocks extends Zend_Db_Table
{
    protected $_name = 'image_blocks';
    protected $_primary = 'ib_id';

 public function getImageBlock($id){
	 $select = $this->select();
	 $select->from($this)->where("ib_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }

 // For get all Text Block
 public function getAllImageBlocks(){
$select = $this->select();
$select->from($this, array('ib_id','name1','link1','caption1'));
$result = $this->fetchAll($select);
return $result;
 }

}