<?php
class Application_Form_VForm extends Zend_Form
{
public function init() 
	{
			
				$url_video = new Zend_Form_Element_Textarea('url_video',array('disableLoadDefaultDecorators' =>true));
				$url_video->setRequired(true)
					->setLabel('* Video URL:')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->addFilter('StringTrim')
					->removeDecorator('htmlTag');
				
			
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
				array('title','short_description','video_img','is_featured'));
				
				//$this->addElement('hash', 'csrf', array('ignore' => true,));
				
//				$this->addElements(array($title,$short_description,$video_img,$url_video,$is_featured,$submit));
	$this->addElements(array($url_video,$submit));


        }
}