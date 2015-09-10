<?php
class Application_Form_GalleryForm extends Zend_Form
{
public function init() 
	{
			  $this->setName('photo_gallery');
					
              $photo_name = new Zend_Form_Element_File('photo_name');
			  $photo_name->setAttrib('multiple', true)
			  ->setAttrib('name', 'photo_name[]')
			  //->addValidator('Count', false, 1)     // ensure only 1 file
	          ->addValidator('FilesSize',false,array('min' => '1kB', 'max' => '10MB'))
	          ->addValidator('ImageSize', false,
                array('minwidth' => 10,
                'minheight' => 10))
                ->addFilter('StringTrim')
				->setErrorMessages(array("Upload an image"))
				->addValidator('Extension', false, 'jpeg,jpg,png,gif');// only JPEG, PNG, and GIFs
				
				$caption = new Zend_Form_Element_Text('caption',array('disableLoadDefaultDecorators' =>true));
				$caption->setAttrib('id', 'caption')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$link = new Zend_Form_Element_Text('link',array('disableLoadDefaultDecorators' =>true));
				$link->setAttrib('id', 'link')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');	
					
				$description = new Zend_Form_Element_Textarea('description',array('disableLoadDefaultDecorators' =>true));
				$description->setAttrib('id', 'editor1')
					->setAttrib('name', 'description')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib('COLS', '10')
    				->setAttrib('ROWS', '8')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag')
					;
										
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submit-btn');
				$submit->setAttrib('class', 'btn btn-md btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Update");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('photo_name','caption','description','link'));
						
				$this->addElements(array($photo_name,$caption,$description,$link,$submit));

        }
}
?>