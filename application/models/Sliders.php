<?php
 class Application_Model_Sliders extends Zend_Db_Table
 {
    protected $_name = 'sliders';
    protected $_primary = 'slider_id';

 public function getSliderByID($id){
	 $select = $this->select();
	 $select->from($this)->where("slider_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }

 public function getAllSlides(){
    $select = $this->select();
    $select->from($this);
    $result = $this->fetchAll($select);
    return $result;
 }

 public function updateSlider($formData)
  {
	$data = array('name' => $formData['name'],
	'slide1' => $formData['slide1'],
    'slide2' => $formData['slide2'],
    'slide3' => $formData['slide3'],
    'slide4' => $formData['slide4'],
    'slide5' => $formData['slide5'],
    'slide6' => $formData['slide6'],
    'link1' => $formData['link1'],
    'link2' => $formData['link2'],
    'link3' => $formData['link3'],
    'link4' => $formData['link4'],
    'link5' => $formData['link5'],
    'link6' => $formData['link6'],);
        
      $where = $this->getAdapter()->quoteInto('slider_id = ?',$formData['id']);
	 $result = $this->update($data, $where);
	 if($result){
			return  1;
		}  else {
			return 0;
		}
	 return $result;
  }

 }