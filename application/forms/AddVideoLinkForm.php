<?php
class Application_Form_AddVideoLinkForm extends Zend_Form
{
public function init() 
	{
				$this->setName('add_video_link');
				//$this->setAction('newExpert');
				$this->setMethod('Post');
				$this->setAttrib('enctype', 'multipart/form-data');
				
				$title = new Zend_Form_Element_Text('title',array('disableLoadDefaultDecorators' =>true));
				$title->setRequired(true)
					->setLabel('* Video Title:')
					->setAttrib('id', 'video_title')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
		
            $url_video = new Zend_Form_Element_Text('url_video',array('disableLoadDefaultDecorators' =>true));
				$url_video->setRequired(true)
					->setLabel('* Video URL:')
					->setAttrib('id', 'url_video')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator('htmlTag'); 
				
				$short_description= new Zend_Form_Element_Textarea('short_description',array('disableLoadDefaultDecorators' =>true));
				$short_description->setRequired(true)
				->setAttrib("id","short_description")
				->setLabel(' *Short Description:')
				->setAttrib("class", "form-control")
				->setAttrib('ROWS', '5')
				->setAttrib('COLS', '3')
				->addFilter('StringTrim');

				$video_img = new Zend_Form_Element_File('video_img');
				//$video_img->setRequired(true)
				$video_img->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('ImageSize', false,
                      array('minwidth' => 100,
                            'maxwidth' => 1000,
                            'minheight' => 100,
                            'maxheight' => 1000))
				->addValidator('Size', false, 1000240000 ) 
				->setErrorMessages(array("Upload an image:"))
				->addValidator('Extension', false, 'jpg,png,gif,jpeg');// only JPEG, PNG, and GIFs
						
				$is_featured = new Zend_Form_Element_Checkbox('is_featured',array('disableLoadDefaultDecorators' =>true));
				$is_featured->setAttrib("id","is_featured")
				->setLabel('Featured:')
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim');
				
				$is_main = new Zend_Form_Element_Checkbox('is_main',array('disableLoadDefaultDecorators' =>true));
				$is_main->setAttrib("id","is_main")
				->setLabel('Mark main video:')
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
				array('title','short_description','url_video','video_img','is_featured', 'is_main'));
				
				//$this->addElement('hash', 'csrf', array('ignore' => true,));
				
				$this->addElements(array($title,$short_description,$video_img,$url_video,$is_featured,$is_main,$submit));

        }
}
?>