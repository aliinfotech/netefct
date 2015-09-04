<?php
class Application_Form_ImageBlockForm extends Zend_Form
{

public function init()
	{
				$this->setName('image_block');
				$this->setAttrib('enctype', 'multipart/form-data');
			
			$name1 = new Zend_Form_Element_File('name1');
			$name1->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('FilesSize',false,array('min' => '10kB', 'max' => '5MB'))
				->addValidator('ImageSize', false,
                            array('minwidth' => 200,
                            'minheight' => 500)
                )
				->setErrorMessages(array("Upload an image"))
				->addValidator('Extension', false, 'jpg,png,gif');// only JPEG, PNG, and GIFs
				
			$caption1 = new Zend_Form_Element_Text('caption1',array('disableLoadDefaultDecorators' =>false));
			$caption1->setRequired(true)
				->setAttrib('id', 'caption')
				->addFilter('StringTrim')
				->setAttrib("class", "form-control")
				->removeDecorator('htmlTag');
				
			$link1 = new Zend_Form_Element_Text('link1',array('disableLoadDefaultDecorators' =>false));
			$link1->setRequired(true)
				->setAttrib('id', 'link')
				->addFilter('StringTrim')
				->setAttrib("class", "form-control")
				->removeDecorator('htmlTag');
			
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-primary pull-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Update");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('name1','link1','caption1')); 
				
				$this->addElements(array( $name1,$link1,$caption1,$submit));

        }
}