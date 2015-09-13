<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_User extends Zend_Db_Table
{ 
    protected $_name = 'users';
    protected $_primary = 'user_id';
    protected $result = null;


public function getUser($id){
$select = $this->select();
$select->from($this)->where("user_id = ?", $id);
$result = $this->fetchRow($select);
return $result;
 }
 
 /* Specially for user change password */
 public function passUpdate($user_id, $password){
 $where = "user_id= ".$user_id;
 $data = array('pwd' => md5($password));
 $result = $this->update($data,$where);
 if ($result > 0) {
	 	 return true; 
 }else{
 	 return false; 
	 }
	  }
  
 // for get all users
 	public function allUsers(){
	$select = $this->select();
	$select->from($this);
	$this->result = $this->fetchAll($select);
     return count($this->result);
	}
 
 
 //this function is used for finding user
 public function findUser($name){
$select = $this->select();
$select->from($this)->where("email like ? ", "%" .$name . "%")->orWhere("user_name like ? ", "%" .$name . "%");
$result = $this->fetchAll($select);
return $result;
 }
 
 
 //this function is used for checking email
public function checkEmail($email){
$select = $this->select();
$select->from($this, array('email'))->where("email = ?", $email);
//echo $select; die;
$result = $this->fetchRow($select);
if(is_object($result)) return true;
else return false;
 }
 
public function getAllAdmin()
{
$select = $this->select();
$select->from($this, array('user_id','email','user_name','role'))->where("role = 1");
$result = $this->fetchAll($select);
return $result;
}	
 
public function getUsers(){
$select = $this->select();
$select->from($this, array('user_id','email','user_name'));
$result = $this->fetchAll($select);
return $result;
 } 
 
 
public function updateUser($formData){
$data = array('email' => $formData['email'],
			'user_name' =>  $formData['user_name']);
$where = "user_id= ". $formData['user_id'];
$result = $this->update($data, $where);
return $result;
  }
  

public function updatePassword($formData){
$password = md5($formData['password']);
$data = array('password' => $password);
$result = $this->update($data,null);
return $result;
  }
  
 public function updatePass($formData){
 $where = "user_id=".$formData['user_id'];
 $email = $formData['email'];
 $password = $formData['pwd'];
 $data = array('email' => $email, 'pwd' => $password);
 $result = $this->update($data,$where);
 return $result;
 }
  
public function block($formData){
$data = array('block' => $formData['block']);
$result = $this->update($data,null);
return $result;
  }


//this function is called when a password recover page runs
public function recoverPassword($data){
$where = "email= '". $data['email']."'";
$result = $this->update($data, $where);
if($result){
    return true;
};
  }
  
  
  //for add user
public function addUser($formData) {
 $data = array('email' => $formData['email'],
				
				'date_added' => $formData['date_added'],
				'pwd' => md5($formData['password']),
				'user_name' => $formData['user_name']);
 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>User ". $formData['user_name'] ." Added Successfully </div>" ;
		}  else {
			return "Some error in saving record";
		}
   }
  
/* public function addUser($formData) {
 $data = array('email' => $formData['email'],
				'pwd' => md5($formData['password']),
				'user_name' => $formData['first_name']);
 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>User ". $formData['first_name'] ." Added Successfully </div>" ;
		}  else {
			return "Some error in saving record";
		}
   } */
  
  
    //for delete user
  public function deleteUsers($user_id){
        $where = "user_id = " . (int) $user_id;
    $id = $this->delete($where);
    if($id > 0){
        return true;
    }else{
        return false;
    }
 }
}