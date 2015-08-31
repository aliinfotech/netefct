<?php 

 class Application_Form_UrlsForm extends Zend_Form
{ 
		
    public function init() 
    	{ 
	 	  
	    $page_url = new Zend_Form_Element_Text('page_url',array('disableLoadDefaultDecorators' =>true));
				$page_url->setRequired(true)
					->setLabel('* Page Url:')
					->setAttrib('id', 'url')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
		
		$post_url = new Zend_Form_Element_Text('post_url',array('disableLoadDefaultDecorators' =>true));
				$post_url->setRequired(true)
					->setLabel('* Post Url:')
					->setAttrib('id', 'url')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->addValidator('NotEmpty')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
		
        $submit = new Zend_Form_Element_Submit('submit',array('disableLoadDefaultDecorators' =>true)); 
        $submit->setAttrib('id', 'submitbutton');
        
 		$this->setMethod('Post');  
		
		$this->setElementDecorators(
				array(
                'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))
				),
				array(
                'page_url','post_url')
				);
		   $this->addElement('hash', 'csrf', array('ignore' => true));

		$this->addElements(array($page_url,$post_url, $submit)); 
	} 

} 
