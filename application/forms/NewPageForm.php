<?php
class Application_Form_NewPageForm extends Zend_Form
{
public function init() 
	{
				$this->setName('new_page');
				$this->setMethod('Post');
				$this->setAttrib('encrypt', 'multipart/form-data');
				
				$title = new Zend_Form_Element_Text('title',array('disableLoadDefaultDecorators' =>true));
				$title->setRequired(true)
					->setLabel('* Page Title:')
					->setAttrib('id', 'title')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
				$url_slug = new Zend_Form_Element_Text('url_slug');
				$url_slug->setRequired(true)
					//->setLabel('* Page Url:')
					->setAttrib('id', 'url')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control set-txt")
					->removeDecorator('htmlTag');
				
				$is_comment = new Zend_Form_Element_Checkbox('is_comment',array('disableLoadDefaultDecorators' =>true));
					$is_comment->setAttrib("id","is_comment")
					->setLabel('Mark as No Comments:')
					->setAttrib("class", "form-control")
					->addFilter('StripTags')
					->addFilter('StringTrim');
					
				$description = new Zend_Form_Element_Textarea('description',array('disableLoadDefaultDecorators' =>true));
				$description->setRequired(true)
					->setLabel('* Description:')
					->setAttrib('id', 'description')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
		
		
               $image = new Zend_Form_Element_File('image');
			   $image->addValidator('Count', false, 1)     // ensure only 1 file
				->addValidator('ImageSize', false,
                      array('minwidth' => 700,
                            'maxwidth' => 1000,
                            'minheight' => 500))
				->addValidator('Size', false, 1000240000 ) 
				->setErrorMessages(array("Upload an image:"))
				->addValidator('Extension', false, 'jpg,png,gif,jpeg,jpg');// only JPEG, PNG, and GIFs
				
													
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submit-btn');
				$submit->setAttrib('class', 'btn btn-lg btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Publish");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('title','is_comment','image','url_slug','description'));
						
				$this->addElements(array($title,$is_comment,$url_slug,$image,$description,$submit));

        }
}
?>