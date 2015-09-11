<?php
class Application_Form_TestimonialForm extends Zend_Form
{
public function init() 
	{
				$this->setName('add_testimonial');
				
				$first_name = new Zend_Form_Element_Text('first_name',array('disableLoadDefaultDecorators' =>true));
				$first_name->setRequired(true)
					->setAttrib('id', 'first_name')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$last_name = new Zend_Form_Element_Text('last_name',array('disableLoadDefaultDecorators' =>true));
				$last_name->setRequired(true)
					->setAttrib('id', 'last_name')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$email = new Zend_Form_Element_Text('email',array('disableLoadDefaultDecorators' =>true));
				$email->setRequired(true)
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
					->setAttrib("id","editor1")
					->setAttrib("class", "form-control")
					->setAttrib('name', 'short_description')
					->setErrorMessages(array("Write Description for Testimonial"))
					->addFilter('StringTrim');

				$image1 = new Zend_Form_Element_File('image1');
				$image1->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('FilesSize',false,array('min' => '1kB', 'max' => '5MB'))
				->addValidator('ImageSize', false,
                            array('minwidth' => 10,
                            'minheight' => 10))
                ->addFilter('StringTrim')
				->setErrorMessages(array("Upload an image"))
				->addValidator('Extension', false, 'jpeg,jpg,png,gif');// only JPEG, PNG, and GIFs
                   
				
				$is_featured = new Zend_Form_Element_Checkbox('is_featured',array('disableLoadDefaultDecorators' =>true));
				$is_featured->setAttrib("id","is_featured")
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim');
						
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
				array('first_name','last_name','email','short_description','image1','is_featured'));
				
				$this->addElements(array($first_name,$last_name,$image1,$email,$is_featured,$short_description,$submit));

        }
}
?>