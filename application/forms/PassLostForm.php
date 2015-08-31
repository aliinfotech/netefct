<?php

class PassLostForm extends Zend_Form
{
	//private $loc = 'en';
	private $lang_file;
	private $lang;
	private $translator;


	public function __construct($options = null)
	{
		$fc = Zend_Controller_Front::getInstance();
		$this->lang_file = Zend_Registry::get('lang_file');
		$this->lang = Zend_Registry::get('lang');
		$this->translator = new Zend_Translate('Zend_Translate_Adapter_Tmx', 'application/language/' . $this->lang_file, $this->lang);

		parent::__construct($options);
		$this->setName('UserRegistration');
		/*customer_id */
		$customer_id = new Zend_Form_Element_Text('customer_id');
		$customer_id->setLabel('customer_id')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('EmailAddress', true)
		->addErrorMessage("Not a valid email")
		->setDescription('');

		$captcha = new Zend_Form_Element_Captcha('captcha', array(
            'label' => "Type letters form the image",
            'captcha' => 'Image',
            'captchaOptions' => array(
                'captcha' => 'Image',
                'wordLen' => 6,
                'timeout' => 300,
                'imgDir' => 'imgs/captcha/', 
                'width' => 200,
                'height' => 60,
                'font' => 'imgs/verdanab.ttf',
				'imgUrl' => ''. $fc->getBaseUrl().'/imgs/captcha/'  
				),
				))	;
				
				

				$submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setDecorators(array(
 		   'ViewHelper',
				array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
				array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
				array(array('row' => 'HtmlTag'), array('tag' => 'tr')),));
					
				$this->setAction('forgot');
				$this->setMethod('Post');
				$this->addElements(array($customer_id,$captcha, $submit));
				
				
		$this->setElementDecorators(
				array(
                'Errors',
				'ViewHelper',
				array('Description',array('tag' => 'td' + '&nbsp;&nbsp;')),
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))
				),
				array(
                'customer_id')
				);
	}
}
