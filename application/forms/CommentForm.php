<?php
class Application_Form_CommentForm extends Zend_Form
{
public function init()
	{
				$this->setName('comments');
				//$this->setAction('contactExpert');
				$this->setMethod('Post');
				
				$name = new Zend_Form_Element_Text('name',array('disableLoadDefaultDecorators' =>true));
				$name->setRequired(true)
					//->setLabel('*  Name')
					->setAttrib('id', 'first_name')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
					
				$email = new Zend_Form_Element_Text('email',array('disableLoadDefaultDecorators' =>true));
				$email->setRequired(true)
					//->setLabel('* Email')
					->setAttrib('id', 'email')
					->setRequired(true) 
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->addValidator('EmailAddress',true)
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
					
				$comment = new Zend_Form_Element_Textarea('comment',array('disableLoadDefaultDecorators' =>true));
				$comment->setAttrib("id","comment")
				->setRequired(true)
				->setAttrib('rows', '5')
				->setAttrib('PlaceHolder', 'You can enter upto 2000 characters in your comments.')
				//->setLabel(' Comments')
				//->addValidator('StringLength', false, array(2, 2000))
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim');

				
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-default set-post-btn float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("POST COMMENT");
				 
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				//array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td'))
				),
				//array('Label', array('tag' => 'td')),
				//array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('name','email','comment'));
				
				$this->addElement('hash', 'csrf', array('ignore' => true,));
				
				$this->addElements(array($name,$email,$comment,$submit));

        }
}
?>