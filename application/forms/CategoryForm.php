<?php
class Application_Form_CategoryForm extends Zend_Form
{
public function init()
	{
				$this->setName('category');
				
				$this->setMethod('Post');
				
				
				$category = new Zend_Form_Element_Text('category',array('disableLoadDefaultDecorators' =>true));
				$category->setRequired(true)
				    ->setLabel(' *Category Name:')
					->setAttrib('id', 'category')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
					
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Save");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('category'));
				
				///$this->addElement('hash', 'csrf', array('ignore' => true,));
				
				$this->addElements(array($category,$submit));

        }
}
?>