<?php
 
class Application_Model_Mpr extends Zend_Db_Table
{ 
    protected $_name = 'mpr1';
    protected $_primary = 'mpr1_id';
 
 
 public function getText(){
	 $select = $this->select();
	 $select->from($this);
	 $result = $this->fetchRow($select);
	 return $result->row_text;
 }
 
 
  public function updateText($text)
  {
	 $data = array('row_text' => $text);
     //$where = "banner_id= ". $formData['banner_id'];
	 $result = $this->update($data);
	 return $result;
  }
  
}
