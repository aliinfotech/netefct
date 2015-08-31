<?php

class Application_Form_ChangePasswordForm extends Zend_Form
{
	public function init()
	{
		$this->setName('pass-update');
			    $pwd_current = new Zend_Form_Element_Password('pwd_current',array('disableLoadDefaultDecorators' =>true));
		        $pwd_current->setRequired(true)
						 ->setLabel('Current Password')
						->setAttrib("class", "form-control")
						->addValidator('StringLength', false, array(1, 20))
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->removeDecorator('HtmlTag')
						->removeDecorator('Label');
			   
				
				$pwd = new Zend_Form_Element_Password('pwd',array('disableLoadDefaultDecorators' =>true));
		                $pwd->setRequired(true)
						->setLabel('New Password')
						->addFilter('StripTags')
						
						->setAttrib("class", "form-control")
						->addValidator('StringLength', false, array(1, 20))
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->removeDecorator('HtmlTag')
						->removeDecorator('Label');
						
				$pwd_confirm = new Zend_Form_Element_Password('pwd_confirm',array('disableLoadDefaultDecorators' =>true));
		                //$pwd_confirm->setRequired(true)
						
						 $pwd_confirm->addFilter('StripTags')
					
						->setAttrib("class", "form-control")
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->addValidator('Identical', false, array('token' => 'pwd'))
						->removeDecorator('HtmlTag')
						->setLabel('Confirm Password')
						->removeDecorator('Label');


               
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Update Password");
				
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							array('Label', array('tag' => 'td')),
							
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('pwd_current','pwd','pwd_confirm'));
							
			$this->addElement('hash', 'csrf', array('ignore' => true,));
				
			$this->addElements(array($pwd_current, $pwd, $pwd_confirm, $submit));				
				

        }
}