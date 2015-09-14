<?php
class Application_Form_PostForm extends Zend_Form
{
public function init() 
	{
				$this->setName('add_new_post');
				$this->setMethod('Post');
				$this->setAttrib('encrypt', 'multipart/form-data');
				
				$heading = new Zend_Form_Element_Text('heading',array('disableLoadDefaultDecorators' =>true));
				$heading->setRequired(true)
					->setLabel('* Blog Post Title:')
					->setAttrib('id', 'heading')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$url = new Zend_Form_Element_Text('url');
				$url->setRequired(true)
					//->setLabel('* Post Url:')
					->setAttrib('id', 'url')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty') 
					//->setAttrib("class", "form-control set-txt")
					->removeDecorator('htmlTag');
				
				$description = new Zend_Form_Element_Textarea('description',array('disableLoadDefaultDecorators' =>true));
				$description->setRequired(true)
					->setLabel('* Description:')
					->setAttrib('id', 'description')
                    ->setAttrib('contenteditable', 'true')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
		
		        /*$categories = new Zend_Form_Element_Text('categories',array('disableLoadDefaultDecorators' =>true));
				$categories->setLabel(' Category:')
					->setAttrib('id', 'categories')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');*/
		
		$is_comment = new Zend_Form_Element_Checkbox('is_comment',array('disableLoadDefaultDecorators' =>true));
				$is_comment->setAttrib("id","is_comment")
				->setLabel('Mark as No Comments:')
				->setAttrib("class", "form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim');
										
		
		        $tags = new Zend_Form_Element_Text('tags',array('disableLoadDefaultDecorators' =>true));
				$tags->setLabel('Tags:')
					->setAttrib('id', 'tags')
					->setAttrib('placeholder', 'Separate tags with comma,')
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
				array('heading','is_comment','url','image','description','tags'));
						
				$this->addElements(array($heading,$url,$is_comment,$image,$description,$tags,$submit));

        }
}
?>