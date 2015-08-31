<?php
class Application_Form_FilterCommentForm extends Zend_Form
{
public function init()
	{
				$this->setName('filter_comment');
				$this->setMethod('Post');
				
                
				$Search_Type = new Zend_Form_Element_Select('search_type',array('disableLoadDefaultDecorators' =>true));
				$Search_Type->setAttrib("id","search_type")
				->setAttrib("class","form-control")
				->addFilter('StripTags')
				->addFilter('StringTrim')                
                
                ->addMultiOption($value = '1', 'Pending Comments') 
                ->addMultiOption($value = '2', 'Approved Comments') 
                ->addMultiOption($value = '3', 'Rejected Comments');
                
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper'),array('search_type'));
				
				//$this->addElement('hash', 'csrf', array('ignore' => true,));  
				
				$this->addElements(array($Search_Type));
  
        }
}
?>