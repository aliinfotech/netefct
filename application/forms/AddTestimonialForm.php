<?php
class Application_Form_AddTestimonialForm extends Zend_Form
{
public function init() 
	{
				$this->setName('add_testimonial');
				//$this->setAction('newExpert');
				$this->setMethod('Post');
				$this->setAttrib('enctype', 'multipart/form-data');
				
				$first_name = new Zend_Form_Element_Text('first_name',array('disableLoadDefaultDecorators' =>true));
				$first_name->setRequired(true)
					->setLabel('* First Name:')
					->setAttrib('id', 'video_title')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$last_name = new Zend_Form_Element_Text('last_name',array('disableLoadDefaultDecorators' =>true));
				$last_name->setRequired(true)
					->setLabel('* Last Name:')
					->setAttrib('id', 'url_video')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$email = new Zend_Form_Element_Text('email',array('disableLoadDefaultDecorators' =>true));
				$email->setRequired(true)
					->setLabel('* Email:')
					->setAttrib('id', 'email')
					->setAttrib('size', '30')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setErrorMessages(array("Write Email"))
					->addValidator('EmailAddress',true)
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
			   
				
				$short_description= new Zend_Form_Element_Textarea('short_description',array('disableLoadDefaultDecorators' =>true));
				$short_description->setRequired(true)
					->setAttrib("id","short_description")
					->setLabel(' *Testimonial:')
					->setAttrib("class", "form-control")
					->setAttrib('ROWS', '5')
					->setAttrib('COLS', '3')
					->setErrorMessages(array("Write Description for Testimonial"))
					->addFilter('StringTrim');

				$image1 = new Zend_Form_Element_File('image1');
				//$image1->setRequired(true)
				$image1->addValidator('Count', false, 1)     // ensure only 1 file
				//->addValidator('FilesSize',false,array('min' => '10kB', 'max' => '1MB'))
				->addValidator('ImageSize', false,
                      array('minwidth' => 100,
                            'maxwidth' => 400,
                            'minheight' => 100,
                            'maxheight' => 400))
				->addValidator('Size', false, 1000240000 ) 
				->setErrorMessages(array("*Upload an image:"))
				->addValidator('Extension', false, 'jpg,png,gif');// only JPEG, PNG, and GIFs
                   
				
				$is_featured = new Zend_Form_Element_Checkbox('is_featured',array('disableLoadDefaultDecorators' =>true));
				$is_featured->setAttrib("id","is_featured")
				->setLabel('Featured:')
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim');
						
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
				array('first_name','last_name','email','short_description','image1','is_featured'));
				
				//$this->addElement('hash', 'csrf', array('ignore' => true,));
				
				$this->addElements(array($first_name,$last_name,$image1,$email,$is_featured,$short_description,$submit));

        }
}
?>