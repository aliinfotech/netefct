<?php
class Application_Form_ImageBlockForm extends Zend_Form
{

public function init()
	{
				$this->setName('image_block');
				$this->setAttrib('enctype', 'multipart/form-data');
			
			$block = new Zend_Form_Element_File('block');
			$block->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('FilesSize',false,array('min' => '10kB', 'max' => '5MB'))
				->addValidator('ImageSize', false,
                            array('minwidth' => 200,
                            'minheight' => 500)
                )
                ->addFilter('StringTrim')
				->setErrorMessages(array("Upload an image"))
				->addValidator('Extension', false, 'jpg,png,gif');// only JPEG, PNG, and GIFs
				
			$name = new Zend_Form_Element_Text('name',array('disableLoadDefaultDecorators' =>true));
			$name->setRequired(true)
				->setAttrib('id', 'image-name')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
				->setAttrib("class", "form-control")
				->removeDecorator('htmlTag');

			$caption = new Zend_Form_Element_Text('caption',array('disableLoadDefaultDecorators' =>true));
			$caption->setRequired(true)
				->setAttrib('id', 'caption')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
				->setAttrib("class", "form-control")
				->removeDecorator('htmlTag');
			
			$disable_link = new Zend_Form_Element_Checkbox('disable_link',array('disableLoadDefaultDecorators' =>true));
				$disable_link->setAttrib("id","disable_link")
				->setAttrib("class", "form-control")
				->addFilter('StringTrim')
				->removeDecorator('htmlTag');

			$link = new Zend_Form_Element_Text('link',array('disableLoadDefaultDecorators' =>true));
			$link->setAttrib('id', 'link')
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
				array('block','name','link','caption','disable_link')); 
				
				$this->addElements(array( $block,$name,$link,$caption,$disable_link,$submit));

        }
}