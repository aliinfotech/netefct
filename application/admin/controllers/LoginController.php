<?php
class Admin_LoginController extends Zend_Controller_Action
{
	    var $user_session = null;
		var $xp_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $user = null;
		private $video = null;

	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'single'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
		$this->xp_session = new Zend_Session_Namespace("xp_session");
		ini_set("max_execution_time",(60*300));
		$this->user = new Application_Model_User();
		$this->expert = new Application_Model_Video();
		
	}


	public function indexAction()
{
  $auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
		$this->_redirect('/admin/login/admin-login');
        }
}

 
	public function adminRecoverPassAction() {
        $form = new Application_Form_RecoverPassForm();
        $this->view->form = $form;

        if (!$this->_request->isPost()) {
            $this->view->form = $form;
            return;
        }

        $formData = $this->_request->getPost();
        $email = $formData['email'];

        if (!$form->isValid($formData)) {
            $this->view->form = $form;
            return;
        }

        $user = new Application_Model_User();
        $select = $user->select(array('user_id', 'pwd', 'email', 'user_name'))->where('email = ?', $email);
		
        $row = $user->fetchRow($select);
        if (is_object($row)) {
            $new_pass = rand(111111, 99999999);
            $data = array("user_id" => $row->user_id, "email" => $row->email, "pwd" => md5($new_pass));
            $user->updatePass($data);

			$subject = "JJSmithOnline Password Recovery.";
    $body = '
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>JJSmithOnline</title>
   <style type="text/css">
   body {margin: 0; padding: 0; min-width: 100%!important;}
   </style>
  </head>
  <body><table><tr><td align="right" style="background-color:#E4308B;"><img src="http://aliinfotech.com/jjs/images/logo/logo.png"/><td>
   </tr>
   <tr><td>Hi,'.trim($row->user_name).'</td></tr>
   <tr><td><h2>Your Password has been recover succussfully.</h2><td><tr>
 <tr><td>You can use the below code as your new password for login at JJSmithOnline</td><tr>
 <tr><td>&nbsp;</td><tr>
 <tr><td>Your new password for JJSmithOnline is '.$new_pass.'.</td></tr>
 <tr><td>&nbsp;</td><tr>
 <tr><td><strong>Note : You are requested to change your password immediately for better security.</strong></td></tr>
<tr><td>&nbsp;</td><tr>
<tr><td>Best Regards </td><tr>
 <tr><td>JJSmithOnline
  </td><tr>
 </table>
  </body>
 </html>';
 $mail = new Zend_Mail();
   $mail->setFrom('jjsmith@jjsmithonline.com', 'jjsmithonline.com');
   $mail->addTo(trim($formData['email']), trim($row->user_name));
   $mail->setSubject($subject);
   $mail->setBodyHtml($body);
   $mail->send();
			/* $this->SendMail("JJ SMith",$email,"JJSmithOnline Password Recovery","<Strong>Password Reset.</Strong><br>
			Your new password for JJSmithOnline is ".$new_pass.".<br>
			Once you login with this temporary password, please update your password.<br>Thank you<br>JJSmithOnline."); */
         	
			$this->view->msg = "<div class='alert alert-success'>A new password has been sent to your email, please check your inbox and also check spam and other folders.</div>";
		
        } else {
            $this->view->msg = "<div class='alert alert-danger'>Sorry wrong email address.</div>";
        }
		
    }
	
	public function xpRecoverPassAction() {
        $form = new Application_Form_RecoverPassForm();
        $this->view->form = $form;

        if (!$this->_request->isPost()) {
            $this->view->form = $form;
            return;
        }

        $formData = $this->_request->getPost();
        $email = $formData['email'];

        if (!$form->isValid($formData)) {
            $this->view->form = $form;
            return;
        }

        $expert = new Application_Model_Expert();
        $select = $expert->select(array('expert_id', 'password', 'email', 'first_name'))->where('email = ?', $email);
		
        $row = $expert->fetchRow($select);
        if (is_object($row)) {
            $new_pass = rand(111111, 99999999);
            //$data = array("expert_id" => $row->user_id, "email" => $row->email, "password" => md5($new_pass));
            $result = $expert->updatePassword($this->db, $row->expert_id, $new_pass);
		
		 $subject = "JJSmithOnline Password Recovery.";
    $body = '
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>JJSmithOnline</title>
   <style type="text/css">
   body {margin: 0; padding: 0; min-width: 100%!important;}
   </style>
  </head>
  <body><table><tr><td align="right" style="background-color:#E4308B;"><img src="http://aliinfotech.com/jjs/images/logo/logo.png"/><td>
   </tr>
   <tr><td>Hi,'.trim($row->first_name).'</td></tr>
   <tr><td><h2>Your Password has been recover succussfully.</h2><td><tr>
 <tr><td>You can use the below code as your new password for login at JJSmithOnline</td><tr>
 <tr><td>&nbsp;</td><tr>
 <tr><td>Your new password for JJSmithOnline is '.$new_pass.'.</td></tr>
 <tr><td>&nbsp;</td><tr>
 <tr><td><strong>Note : You are requested to change your password immediately for better security.</strong></td></tr>
 <tr><td>&nbsp;</td><tr>
<tr><td>Best Regards </td><tr>
 <tr><td>JJSmithOnline
  </td><tr>
 </table>
  </body>
 </html>';
 $mail = new Zend_Mail();
   $mail->setFrom('jjsmith@jjsmithonline.com', 'jjsmithonline.com');
   $mail->addTo(trim($formData['email']), trim($row->first_name));
   $mail->setSubject($subject);
   $mail->setBodyHtml($body);
   $mail->send();
			/* $this->SendMail("JJ SMith",$email,"JJSmithOnline Password Recovery","<Strong>Password Reset.</Strong><br>
			Your new password for JJSmithOnline is ".$new_pass.".<br>
			Once you login with this temporary password, please update your password.<br>Thank you<br>JJSmithOnline."); */
         	
			$this->view->msg = "<div class='alert alert-success'>A new password has been sent to your email, please check your inbox and also check spam and other folders.</div>";
		
        } else {
            $this->view->msg = "<div class='alert alert-danger'>Sorry wrong email address.</div>";
        }
		
    }
	
	function adminLoginAction(){
		$this->view->title = "Login";
		$form = new Application_Form_UserLoginForm();
        $this->view->form = $form;
		
		// Post and validation section 
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		if (!$form->isValid($formData)) return;
		
		$email = $formData['email'];
		$password = $formData['password'];
		//$password  = md5($formData['password']);
	
		$this->authAdapter->setTableName('users')
		->setIdentityColumn('email')
		->setCredentialColumn('pwd')
		->setIdentity($email)
		->setCredential($password);
		$auth = Zend_Auth::getInstance();
		$result = $this->authAdapter->authenticate();
		if ($result->isValid()){
			$data = $this->authAdapter->getResultRowObject(null,'pwd');
			$auth->getStorage()->write($data);
			//fetch user info
			$user = new Application_Model_User();
			$select = $user->select(array('user_id', 'user_name'))->where('email = ?',$email);
			$row = $user->fetchRow($select);
			$this->user_session = new Zend_Session_Namespace('user_session'); // default namespace
			$this->user_session->user_name = $row->user_name;
			$this->user_session->user_id = $row->user_id;
		    $this->_redirect('/admin/index');
		} 

		else{
			$this->view->msg = "<div class='alert alert-danger'> Invalid User Name or Passowrd </div>";
			$this->view->form = $form;
		}

	}// login action ends

	public function adminLogoutAction(){
		$this->_helper->viewRenderer->setNoRender();
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity(); #1
		unset($this->user_session->user_id); 
		$this->_redirect('/admin/login/admin-login');
	}
	
	function xpLoginAction(){
		
		$this->_helper->layout->setLayout('single');
		$this->view->title = "Login";
		$form = new Application_Form_UserLoginForm();
        $this->view->form = $form;
		
		// Post and validation section 
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		if (!$form->isValid($formData)) return;
		
		$email = $formData['email'];
		//$password = $formData['password'];
		$password  = md5($formData['password']);
	
		$this->authAdapter->setTableName('experts')
		->setIdentityColumn('email')
		->setCredentialColumn('password')
		->setIdentity($email)
		->setCredential($password);
		$auth = Zend_Auth::getInstance();
		$result = $this->authAdapter->authenticate();
		if ($result->isValid()){
			$data = $this->authAdapter->getResultRowObject(null,'password');
			$auth->getStorage()->write($data);
			//fetch user info
			$user = new Application_Model_Expert();
			$select = $user->select(array('expert_id', 'first_name'))->where('email = ?',$email);
			$row = $user->fetchRow($select);
			$this->xp_session = new Zend_Session_Namespace('xp_session'); // default namespace
			$this->xp_session->user_name = $row->first_name;
			$this->xp_session->user_id = $row->expert_id;
		    $this->_redirect('/admin/xp/index');
		} 

		else{
			$this->view->msg = "<div class='alert alert-danger'> Invalid User Name or Passowrd </div>";
			$this->view->form = $form;
		}

	}// login action ends

	public function xpLogoutAction(){
		$this->_helper->viewRenderer->setNoRender();
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity(); #1
		unset($this->xp_session->user_id); 
		$this->_redirect('/admin/login/xp-login');
	}//
	
	public function SendMail($first_name, $to, $subject, $body)
	{
	$mail = new Zend_Mail();
			$mail->setFrom('snk90.biz@gmail.com', 'http://e2.my/byronthomas/index/home');
			$mail->addTo($to,$first_name);
			$mail->setSubject($subject);
			$mail->setBodyHtml($body);
			$mail->send();
	} 

    public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }
}