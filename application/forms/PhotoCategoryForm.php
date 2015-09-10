<?php
class Application_Form_PhotoCategoryForm extends Zend_Form
{
public function init()
	{
				$this->setName('photo-category');
		
				$category_name = new Zend_Form_Element_Text('category_name',array('disableLoadDefaultDecorators' =>true));
				$category_name->setRequired(true)
				  	->setAttrib('id', 'category')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$banner = new Zend_Form_Element_File('banner');
				$banner->addValidator('Count', false, 1)     // ensure only 1 file
				   ->addValidator('FilesSize',false,array('min' => '1kB', 'max' => '5MB'))
				   ->addValidator('ImageSize', false,
                     array('minwidth' => 10,
                     'minheight' => 10))
                ->addFilter('StringTrim')
				->setErrorMessages(array("Upload an image"))
				->addValidator('Extension', false, 'jpeg,jpg,png,gif');// only JPEG, PNG, and GIFs	
					

		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-md btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Save");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('category_name','banner'));
											
				$this->addElements(array($category,$banner,$submit));

        }
}
?>