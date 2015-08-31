<?php
class Application_Form_FilterPostsForm extends Zend_Form
{
public function init()
	{
				$this->setName('filter_post');
				$this->setMethod('Post');
				
                
				$Search_Type = new Zend_Form_Element_Select('search_type',array('disableLoadDefaultDecorators' =>true));
				$Search_Type->setAttrib("id","search_type")
				->setAttrib("class","form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')                
                
                ->addMultiOption($value = '1', 'Published Posts') 
                ->addMultiOption($value = '2', 'Draft Posts');
                
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper'),array('search_type'));
				
				//$this->addElement('hash', 'csrf', array('ignore' => true,));  
				
				$this->addElements(array($Search_Type));
  
        }
}
?>