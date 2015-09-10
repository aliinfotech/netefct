<?php
 class Application_Model_Sliders extends Zend_Db_Table
 {
    protected $_name = 'sliders';
    protected $_primary = 'id';

 public function getSliderByID($id){
	 $select = $this->select();
	 $select->from($this)->where("id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }

 public function getAllSliders(){
    $select = $this->select();
    $select->from($this);
    $result = $this->fetchAll($select);
    return $result;
 }

 public function updateSlider($formData)
  {
	$data = array('slider_name' => $formData['slider_name'],
	'slider1' => $formData['slider1'],
    'slider2' => $formData['slider2'],
    'slider3' => $formData['slider3'],
    'slider4' => $formData['slider4'],
    'slider5' => $formData['slider5'],
    'slider6' => $formData['slider6'],
    'slider1_link' => $formData['slider1_link'],
    'slider2_link' => $formData['slider2_link'],
    'slider3_link' => $formData['slider3_link'],
    'slider4_link' => $formData['slider4_link'],
    'slider5_link' => $formData['slider5_link'],
    'slider6_link' => $formData['slider6_link'],);
        
      $where = $this->getAdapter()->quoteInto('id = ?',$formData['id']);
	 $result = $this->update($data, $where);
	 if($result){
			return  1;
		}  else {
			return 0;
		}
	 return $result;
  }

 }