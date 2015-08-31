<?php 

 class Application_Form_MpTextForm extends Zend_Form
{ 
		
    public function init() 
    	{ 
	 	 $this->setDisableLoadDefaultDecorators(true);
       $row_text = new Zend_Form_Element_Textarea('row_text',array('disableLoadDefaultDecorators' =>true)); 
        $row_text->setAttrib('class', 'form-control')
		->setAttrib('required', 'required')
		->setAttrib('cols', '40')
		->setAttrib('rows', '10')
		->setRequired(true) 
        ->addFilter('StringTrim') 
		->removeDecorator('htmlTag')
		->removeDecorator('Errors')
		->removeDecorator('label')
		
		;
        $submit = new Zend_Form_Element_Submit('submit',array('disableLoadDefaultDecorators' =>true)); 
        $submit->setAttrib('id', 'submitbutton');
        $submit->setLabel("login")
		 ->removeDecorator('htmlTag'); 
        
 		//$this->setAction('login');
		$this->setMethod('Post');  
		
		$this->setElementDecorators(
				array(
                'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))
				),
				array(
                'row_text')
				);
		   $this->addElement('hash', 'csrf', array('ignore' => true));

		$this->addElements(array($row_text, $submit)); 
	} 

} 
