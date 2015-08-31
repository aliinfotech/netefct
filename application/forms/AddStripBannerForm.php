<?php
class Application_Form_AddStripBannerForm extends Zend_Form
{
public function init() 
	{
				$this->setName('add_strip_banner');
				$this->setMethod('Post');
				$this->setAttrib('enctype', 'multipart/form-data');
				
				$targetUrl = new Zend_Form_Element_Text('target_url',array('disableLoadDefaultDecorators' =>true));
				$targetUrl->setRequired(true)
					->setLabel('* Target Url:')
					->setAttrib('id', 'target_url')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
		
               $banner_img = new Zend_Form_Element_File('banner_img');
				$banner_img->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('ImageSize', false,
                      array('minwidth' => 1400,
                            'maxwidth' => 1600,
                            'minheight' => 50,
                            'maxheight' => 150))
				->addValidator('Size', false, 1000240000 ) 
				->setErrorMessages(array("Upload an image:"))
				->addValidator('Extension', false, 'jpg,png,gif,jpeg');// only JPEG, PNG, and GIFs			
								
				$is_main = new Zend_Form_Element_Checkbox('is_main',array('disableLoadDefaultDecorators' =>true));
				$is_main->setAttrib("id","is_main")
				->setLabel('Mark main Strip Banner:')
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim');
										
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
				array('targetUrl','banner_img','is_main'));
						
				$this->addElements(array($targetUrl,$banner_img,$is_main,$submit));

        }
}
?>