<?php
class Application_Form_TextBlockForm extends Zend_Form
{
public function init() 
	{
				$this->setName('text_block');
				$this->setMethod('Post');
				$this->setAttrib('enctype', 'multipart/form-data');
				
				$tb_name = new Zend_Form_Element_Text('tb_name',array('disableLoadDefaultDecorators' =>true));
				$tb_name->setLabel('Text Block Name:')
					->setAttrib('id', 'text_block')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$tb_text = new Zend_Form_Element_Textarea('tb_text',array('disableLoadDefaultDecorators' =>true));
				$tb_text->setLabel('Block Text:')
					->setAttrib('id', 'editor1')
					->setAttrib('name', 'tb_text')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
               

		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submit-btn');
				$submit->setAttrib('class', 'btn btn-lg btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label') 
				->setLabel("Update");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('tb_name','tb_text'));
						
				$this->addElements(array($tb_name,$tb_text,$submit));

        }
}
?>