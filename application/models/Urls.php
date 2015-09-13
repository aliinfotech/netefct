<?php
 
class Application_Model_Urls extends Zend_Db_Table
{ 
    protected $_name = 'site_info';
    protected $_primary = 'si_id';
 
 
 public function getUrls(){
	 $select = $this->select();
	 $select->from($this);
	 $result = $this->fetchRow($select);
	 return $result;
	 
 }
 
 
  public function updateUrls($formData)
  {
	 $data = array('page_url' => $formData['page_url'],
				   'post_url' => $formData['post_url']
	 );
 	 $result = $this->update($data);
	 return $result;
  }
  
}