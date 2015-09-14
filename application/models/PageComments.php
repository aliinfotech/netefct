<?php

class Application_Model_PageComments extends Zend_Db_Table
{ 
    protected $_name = 'comments_page';
    protected $_primary = 'comment_id';
    protected $result = null;


public function getComment($id){
$select = $this->select();
$select->from($this)->where("comment_id = ?", $id);
$result = $this->fetchRow($select);
return $result;
 }
 
 // for search comment from admin comments list
	public function searchComments($query_string){
		$select = $this->select(); 
		$cols = array("comment_id","email","date","is_comment","comments");
		$select->from($this, $cols)->Where("date like ? ", "%" .$query_string . "%")->orWhere("email like ? ", "%" .$query_string . "%");
		$result = $this->fetchAll($select);
		return $result;
		if(is_object($result)){
				return $result;
		}else return false; 
	}	
 
     // for get total comments
 public function getAllComments($db){
	$select = new Zend_Db_Select($db);
	$cols = array('comment_id','page_id', 'comment','comment_date','name','email','status');
    $select->from(array('c' =>'comments'), $cols)->order('comment_date DESC')
    ->joinLeft(array('p' => 'pages'),'p.page_id = c.page_id',array('url_slug','title'));//->where("p.page_id =?", 'c.page_id' );
    $stmt = $db->query($select);
    $results = $stmt->fetchAll(); 
    return $results;
	}
 
public function getCommentsByPage($id) 
{
$select = $this->select();
$select->from($this)->where("status = 2")->where("page_id = ?", $id);
$result = $this->fetchAll($select);
return $result;
}
//get approved comments. 
public function getApprovedComments($db)
{
	$select = new Zend_Db_Select($db);
	$cols = array('comment_id','page_id', 'comment','comment_date','name','email','status');
    $select->from(array('c' =>'comments'), $cols)->order('comment_date DESC')->where("status = 2")
    ->joinLeft(array('p' => 'pages'),'p.page_id = c.page_id',array('url_slug','title'));//->where("p.page_id =?", 'c.page_id' );
    $stmt = $db->query($select);
    $results = $stmt->fetchAll(); 
    return $results;
	}

public function getRejectedComments($db)
{
$select = new Zend_Db_Select($db);
	$cols = array('comment_id','page_id', 'comment','comment_date','name','email','status');
    $select->from(array('c' =>'comments'), $cols)->order('comment_date DESC')->where("status = 3")
    ->joinLeft(array('p' => 'pages'),'p.page_id = c.page_id',array('url_slug','title'));//->where("p.page_id =?", 'c.page_id' );
    $stmt = $db->query($select);
    $results = $stmt->fetchAll(); 
    return $results;
	}
	
public function getPendingComments($db)
{
$select = new Zend_Db_Select($db);
	$cols = array('comment_id','page_id', 'comment','comment_date','name','email','status');
    $select->from(array('c' =>'comments'), $cols)->order('comment_date DESC')->where("status = 1")
    ->joinLeft(array('p' => 'pages'),'p.page_id = c.page_id',array('url_slug','title'));//->where("p.page_id =?", 'c.page_id' );
    $stmt = $db->query($select);
    $results = $stmt->fetchAll(); 
    return $results;
	}


  // for get comments by date search
public function getSearchComments($db, $query_string){
$select = $db->select()->from(array('c' => 'comments_experts'),array('comment_id','is_comment','first_name','last_name','email','comments','consistency','knowledge','approach','communication','is_comment','date'))->Where("date like ? ", "%" .$query_string . "%")->order('date DESC')->join(array('e' => 'experts'), 'c.expert_id=e.expert_id', array('expert_FName' => 'e.first_name','expert_LName' => 'e.last_name'));
$stmt = $select->query();
$result = $stmt->fetchAll();
return $result;
 }
 
/*
public function expertAllCommentsSortBy($expert_id,$db, $sort_id){

if($sort_id == 1){
$select = $db->select()->from(array('c' => 'comments_experts'),array('comment_id','first_name','last_name','email','comments','consistency','knowledge','approach','communication','is_comment','date'))->where("c.expert_id = ?", $expert_id)->join(array('e' => 'experts'), 'c.expert_id=e.expert_id', array('expert_FName' => 'e.first_name','expert_LName' => 'e.last_name'))->order("is_comment DESC");
}

if($sort_id == 2){
$select = $db->select()->from(array('c' => 'comments_experts'),array('comment_id','first_name','last_name','email','comments','consistency','knowledge','approach','communication','is_comment','date'))->where("c.expert_id = ?", $expert_id)->join(array('e' => 'experts'), 'c.expert_id=e.expert_id', array('expert_FName' => 'e.first_name','expert_LName' => 'e.last_name'))->order("is_comment ASC");
}

if($sort_id == 3){
$select = $db->select()->from(array('c' => 'comments_experts'),array('comment_id','first_name','last_name','email','comments','consistency','knowledge','approach','communication','is_comment','date'))->where("c.expert_id = ?", $expert_id)->join(array('e' => 'experts'), 'c.expert_id=e.expert_id', array('expert_FName' => 'e.first_name','expert_LName' => 'e.last_name'))->order("consistency DESC");
}

if($sort_id == 4){
$select = $db->select()->from(array('c' => 'comments_experts'),array('comment_id','first_name','last_name','email','comments','consistency','knowledge','approach','communication','is_comment','date'))->where("c.expert_id = ?", $expert_id)->join(array('e' => 'experts'), 'c.expert_id=e.expert_id', array('expert_FName' => 'e.first_name','expert_LName' => 'e.last_name'))->order("knowledge DESC");
}

if($sort_id == 5){
$select = $db->select()->from(array('c' => 'comments_experts'),array('comment_id','first_name','last_name','email','comments','consistency','knowledge','approach','communication','is_comment','date'))->where("c.expert_id = ?", $expert_id)->join(array('e' => 'experts'), 'c.expert_id=e.expert_id', array('expert_FName' => 'e.first_name','expert_LName' => 'e.last_name'))->order("approach DESC");
}

if($sort_id == 6){
$select = $db->select()->from(array('c' => 'comments_experts'),array('comment_id','first_name','last_name','email','comments','consistency','knowledge','approach','communication','is_comment','date'))->where("c.expert_id = ?", $expert_id)->join(array('e' => 'experts'), 'c.expert_id=e.expert_id', array('expert_FName' => 'e.first_name','expert_LName' => 'e.last_name'))->order("communication DESC");
}


$stmt = $select->query();
$result = $stmt->fetchAll();
return $result;
  }

 */
 
 //for update/approved comments
 
  
public function getLastInsertRecord()
{
$select = $this->select();
$select->from($this)->order('comment_id DESC');
$result = $this->fetchRow($select);
return $result;
}	
  
public function addComment($formData) {
  $data = array('name' => $formData['name'],
				'email' => $formData['email'],
				'comment' => $formData['comment'], 
				'page_id' => $formData['page_id'],  
				'comment_date' => date('Y-m-d | h:i:sa')
				);
 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>Your review has been successfully added.</div>" ;
		}  else {
			return "Some error in saving record";
		}
   }
  
   
	public function deleteComment($id){

	$where = "comment_id = " . (int) $id;
    $id = $this->delete($where);
    if($id > 0){
        return true;
    }else{
        return false;
    }  
	}
	
	public function approveComment($db, $id){ 
	$data = array('status'=> 2);
	$result = $this->update($data,$db->quoteInto("comment_id = ?", $id));
	if($result)
		return true;
	else
		return false;
}

	public function rejectComment($db, $id){ 
	$data = array('status'=> 3);
	$result = $this->update($data,$db->quoteInto("comment_id = ?", $id));
	if($result)
		return true;
	else
		return false;
}

	public function updateComment($db, $formData){
$data = array('name' => $formData['name'],
		'email' => $formData['email'],
		'comment' => $formData['comment'],);

$result = $this->update($data, $db->quoteInto("comment_id = ?", $formData['comment_id']));
 
		if($result){
			return  "<div class='alert alert-success'>Comment has been updated successfully.</div>" ;
		}  else {
			return "Some error in saving record";
		}
  }
 

}