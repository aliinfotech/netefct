<?php
class Application_Form_GalleryForm extends Zend_Form
{
public function init() 
	{
				$this->setName('photo_gallery');
				$this->setMethod('Post');
				$this->setAttrib('enctype', 'multipart/form-data');
				
		
              $photo_name = new Zend_Form_Element_File('photo_name');
			$photo_name->addValidator('Count', false, 1)     // ensure only 1 file
			    
				->addValidator('FilesSize',false,array('min' => '10kB', 'max' => '3MB'))
				/*->addValidator('ImageSize', false,
                            array('minwidth' => 200,
                            'minheight' => 400)
               
				) */
				->setErrorMessages(array("Upload an image"))
				->addValidator('Extension', false, 'jpg,png,gif');// only JPEG, PNG, and GIFs
				
			
										
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submit-btn');
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
				array('photo_name'));
						
				$this->addElements(array($photo_name,$submit));

        }
}
?>