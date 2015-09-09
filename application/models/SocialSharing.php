 <?php
 
class Application_Model_Socialsharing extends Zend_Db_Table
{ 
    protected $_name = 'social_sharing';
    protected $_primary = 'ss_id';
    protected $result = null;
  
   
   public function getSocialSharing(){
	$select = $this->select();
	$select->from($this);
	$result = $this->fetchRow($select);
	return $result; 
	 }
 
 
  public function updateSocialSharing($formData)
  {
	  $data = array('facebook' => $formData['facebook'],
				'linkedin' => $formData['linkedin'],
				'twitter' => $formData['twitter'],
				'youtube' => $formData['youtube'],
				'instagram' => $formData['instagram'],
				'google_plus' => $formData['google_plus'],
				'pinterest' => $formData['pinterest'],
				'vimeo' => $formData['vimeo'],
				'dailymotion' => $formData['dailymotion'],
				'tumblr' => $formData['tumblr']);
	  $where = $this->getAdapter()->quoteInto('ss_id = ?',$formData['ss_id']);
	 $result = $this->update($data, $where); 
	 if($result){
			return  "<div class='alert alert-success'>Social Sharing Link Updated Successfully </div>" ;
		}  else {
			return "<div class='alert alert-danger'>Some error in updating record</div>";
		}
	 return $result;
  }
  
}
?>
