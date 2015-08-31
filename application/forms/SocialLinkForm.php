<?php
class Application_Form_SocialLinkForm extends Zend_Form
{
public function init()
	{
				$this->setName('social_link');
				//$this->setAction('socialMedia');
				$this->setMethod('Post');
				
				
				$facebook = new Zend_Form_Element_Text('facebook',array('disableLoadDefaultDecorators' =>true));
				$facebook->setLabel(' Facebook:')
					->setAttrib('id', 'facebook')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
				$twitter = new Zend_Form_Element_Text('twitter',array('disableLoadDefaultDecorators' =>true));
				$twitter->setLabel(' Twitter:')
					->setAttrib('id', 'twitter')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
		
				$youtube = new Zend_Form_Element_Text('youtube',array('disableLoadDefaultDecorators' =>true));
				$youtube->setLabel(' Youtube:')
					->setAttrib('id', 'youtube')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
				$instagram = new Zend_Form_Element_Text('instagram',array('disableLoadDefaultDecorators' =>true));
				$instagram->setLabel(' Instagram:')
					->setAttrib('id', 'instagram')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$tumblr = new Zend_Form_Element_Text('tumblr',array('disableLoadDefaultDecorators' =>true));
				$tumblr->setLabel(' Tumblr:')
					->setAttrib('id', 'tumblr')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				$google_plus = new Zend_Form_Element_Text('google_plus',array('disableLoadDefaultDecorators' =>true));
				$google_plus->setLabel(' Google Plus:')
					->setAttrib('id', 'google_plus')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
				
				
				$linkedin = new Zend_Form_Element_Text('linkedin',array('disableLoadDefaultDecorators' =>true));
				$linkedin->setLabel(' LinkedIn:')
					->setAttrib('id', 'linkedIn')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->setAttrib("class", "form-control")
					->removeDecorator('htmlTag');
					
		        $submit = new Zend_Form_Element_Submit('submit');
				$submit->setAttrib('id', 'submitbutton');
				$submit->setAttrib('class', 'btn btn-lg btn-primary float-right')
				->removeDecorator('HtmlTag')
				->removeDecorator('Label')
				->setLabel("Update");
				
				$this->setElementDecorators(array(
				'Errors',
				'ViewHelper',
				array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
				array('Label', array('tag' => 'td')),
				array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
				array('twitter','facebook','tumblr','google_plus','youtube','linkedin','instagram'));
				
				///$this->addElement('hash', 'csrf', array('ignore' => true,));
				
				$this->addElements(array($twitter,$google_plus,$tumblr,$facebook,$youtube,$instagram,$linkedin,$submit));

        }
}
?>