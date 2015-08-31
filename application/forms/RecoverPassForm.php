<?php

class Application_Form_RecoverPassForm extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
		$this->setName('recover-password');

        // Add an email element 
        $email = new Zend_Form_Element_Text('email'); 
        $email->setRequired(true) 
        ->setAttrib('size', '30')
		->setLabel('* Your Email Address:')
        ->addFilter('StripTags') 
        ->addFilter('StringTrim') 
		->setAttrib('class','email1')
        ->setErrorMessages(array("Write your email"))
        ->addValidator('EmailAddress',true)
		//->removeDecorator('HtmlTag')
		->setAttrib('class','form-control')
		->setAttrib('place-holder','Email Address as ID');
		
		   
			  $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-md btn-primary')
				//->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Recover Now");

			
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							array('Label', array('tag' => 'td')),
							
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('email'));
							
				$this->addElements(array($email,$submit));
	}
		
}