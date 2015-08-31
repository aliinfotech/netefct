<?php 

 class Application_Form_UserLoginForm extends Zend_Form
{ 
		
    public function init() 
    	{ 
	 $this->setDisableLoadDefaultDecorators(true);
       $email = new Zend_Form_Element_Text('email',array('disableLoadDefaultDecorators' =>true)); 
        $email->setLabel('email') 
        ->setAttrib('class', 'form-control')
		->setAttrib('required', 'required')
		->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
        ->setErrorMessages(array("Write your email as login ID"))
        ->addValidator('EmailAddress',true)
		->removeDecorator('htmlTag')
		->removeDecorator('Errors')
		;

/* password */
 $password = new Zend_Form_Element_Password('password',array('disableLoadDefaultDecorators' =>true)); 
        $password->setLabel('password')
        ->setAttrib('class', 'form-control')
		->setAttrib('required', 'required')
       ->setRequired(true) 
        ->addFilter('StripTags') 
        ->addFilter('StringTrim')
        ->setErrorMessages(array("Write your password"))
        ->removeDecorator('htmlTag')
		->removeDecorator('Errors')
		;
		
        $submit = new Zend_Form_Element_Submit('submit',array('disableLoadDefaultDecorators' =>true)); 
        $submit->setAttrib('id', 'submitbutton');
        $submit->setLabel("login")
		 ->removeDecorator('htmlTag'); 
        
 		//$this->setAction('login');
		$this->setMethod('Post');  
		
		$this->setElementDecorators(
				array(
                'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))
				),
				array(
                'email','password')
				);
		   $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));

		$this->addElements(array($email, $password, $submit)); 
	} 

} 
