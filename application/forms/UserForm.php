<?php
class Application_Form_UserForm extends Zend_Form
{
public function init()
	{
		$this->setName('user');
		$this->setAttrib('enctype', 'multipart/form-data');

                $user_id = new Zend_Form_Element_Hidden('user_id');
                
                $email = new Zend_Form_Element_Text('email',array('disableLoadDefaultDecorators' =>true));
		                 $email->setRequired(true)
						 ->setLabel('* Email')
						->setAttrib("class", "form-control")
						->addFilter('StripTags')
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->addValidator('EmailAddress')
						->removeDecorator('HtmlTag')
						->removeDecorator('Label');
				
				$password = new Zend_Form_Element_Text('password',array('disableLoadDefaultDecorators' =>true));
		                $password->setRequired(true)
						->setLabel('* Password')
						->addFilter('StripTags')
						->setAttrib("class", "form-control")
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->addValidator('NotEmpty')
						->removeDecorator('HtmlTag')
						->removeDecorator('Label');


                $user_name = new Zend_Form_Element_Text('user_name',array('disableLoadDefaultDecorators' =>true));
		                 $user_name->setRequired(true)
						 ->setLabel('* User Name')
						->setAttrib("class", "form-control")
						->addFilter('StripTags')
						->addFilter('StringTrim')
						->addValidator('NotEmpty')
						->removeDecorator('HtmlTag')
						->removeDecorator('Label');
                   
                /* $contact_number = new Zend_Form_Element_Text('contact_number',array('disableLoadDefaultDecorators' =>true));
		       	$contact_number->setRequired(true)
				->setLabel('* Contact Number')
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label');
				
				
				$address = new Zend_Form_Element_Textarea('address',array('disableLoadDefaultDecorators' =>true));
		                 $address->setLabel('Address')
				->setAttrib('COLS', '31')
  				->setAttrib('ROWS', '5')
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label'); */
				
				
				/*$role = new Zend_Form_Element_Radio('role',array('disableLoadDefaultDecorators' =>true));
				$role->addMultiOptions(array(
				'1' => 'Admin',
				'2' => 'Manager'));
				
				$role->setLabel('Select Role')
					->setAttrib('id','role')
					->setRequired(true);*/
	                
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Save");
				
				$this->setElementDecorators(array(
							'Errors',
							'ViewHelper',
							array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
							array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
							array('Label', array('tag' => 'td')),
							
							array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
							array('email','user_name','password'));
				$this->addElements(array($user_id,$email,$password,$user_name,$submit));

        }
}
?>