<?php

class Application_Form_SliderForm extends Zend_Form
{

    public function init()
    {

        $this->setName('slider');

        $name = new Zend_Form_Element_Text('name', array('disableLoadDefaultDecorators' => true));
        $name->setRequired(true)->setAttrib('id', 'name')->addFilter('StringTrim')->
            addFilter('StripTags')->addValidator('NotEmpty')->setAttrib("class",
            "form-control")->removeDecorator('htmlTag');

        $slide1 = new Zend_Form_Element_File('slide1');
        $slide1->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs


        $link1 = new Zend_Form_Element_Text('link1', array('disableLoadDefaultDecorators' => true));
        $link1->setAttrib('id', 'link1')->addFilter('StringTrim')->addFilter('StripTags')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $slide2 = new Zend_Form_Element_File('slide2');
        $slide2->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs


        $link2 = new Zend_Form_Element_Text('link2', array('disableLoadDefaultDecorators' => true));
        $link2->setAttrib('id', 'link2')->addFilter('StringTrim')->addFilter('StripTags')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $slide3 = new Zend_Form_Element_File('slide3');
        $slide3->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs


        $link3 = new Zend_Form_Element_Text('link3', array('disableLoadDefaultDecorators' => true));
        $link3->setAttrib('id', 'link3')->addFilter('StringTrim')->addFilter('StripTags')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $slide4 = new Zend_Form_Element_File('slide4');
        $slide4->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs


        $link4 = new Zend_Form_Element_Text('link4', array('disableLoadDefaultDecorators' => true));
        $link4->setAttrib('id', 'link4')->addFilter('StringTrim')->addFilter('StripTags')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');
            
        $slide5 = new Zend_Form_Element_File('slide5');
        $slide5->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs


        $link5 = new Zend_Form_Element_Text('link5', array('disableLoadDefaultDecorators' => true));
        $link5->setAttrib('id', 'link5')->addFilter('StringTrim')->addFilter('StripTags')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');

        $slide6 = new Zend_Form_Element_File('slide6');
        $slide6->addValidator('Count', false, 1) // ensure only 1 file
            ->addValidator('FilesSize', false, array('min' => '1kB', 'max' => '5MB'))->
            addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10))->
            addFilter('StringTrim')->setErrorMessages(array("Upload an image"))->
            addValidator('Extension', false, 'jpeg,jpg,png,gif'); // only JPEG, PNG, and GIFs


        $link6 = new Zend_Form_Element_Text('link6', array('disableLoadDefaultDecorators' => true));
        $link6->setAttrib('id', 'link6')->addFilter('StringTrim')->addFilter('StripTags')->
            setAttrib("class", "form-control")->removeDecorator('htmlTag');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setAttrib('class', 'btn btn-middium btn-primary pull-right')->
            removeDecorator('HtmlTag')->removeDecorator('Label')->setLabel("Update");

        $this->setElementDecorators(array(
            'Errors',
            'ViewHelper',
            array('decorator' => array('td' => 'HtmlTag'), 'options' => array('tag' => 'td')),
            array('decorator' => array('tr' => 'HtmlTag'), 'options' => array('tag' => 'tr'))),
            array('name', 'slide1', 'link1', 'slide2', 'link2', 'slide3', 'link3', 'slide4', 'link4', 'slide5', 'link5', 'slide6', 'link6'));

        $this->addElements(array($name,$slide1,$link1,$slide2,$link2,$slide3,$link3,$slide4,$link4,$slide5,$link5,$slide6,$link6,            
            $submit));

    }
}
