<?php
class Application_Form_FilterPagesForm extends Zend_Form
{
public function init()
	{
				$this->setName('filter_page');
				$this->setMethod('Post');
				
                
				$Search_Type = new Zend_Form_Element_Select('search_type',array('disableLoadDefaultDecorators' =>true));
				$Search_Type->setAttrib("id","search_type")
				->setAttrib("class","form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')                
                
                ->addMultiOption($value = '1', 'Published Pages') 
                ->addMultiOption($value = '2', 'Draft Pages');
                
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper'),array('search_type'));
				
				//$this->addElement('hash', 'csrf', array('ignore' => true,));  
				
				$this->addElements(array($Search_Type));
  
        }
}
?>