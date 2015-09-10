<?php

class Admin_SlidersController extends Zend_Controller_Action
{
    protected $user_session = null;
    private $db = null;
    private $baseurl = null;
    private $authAdapter = null;
    private $sliders = null;

    public function init()
    {
        Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . '/admin/layouts',
                'layout' => 'layout'));

        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->user_session = new Zend_Session_Namespace("user_session");

        ini_set("max_execution_time", (60 * 300));
        $this->sliders = new Application_Model_Sliders();

        if (!isset($this->user_session->user_id))
        {
            $this->_redirect("/admin/login/admin-login");
        }
        $auth = Zend_Auth::getInstance();
        //if not loggedin redirect to login page
        if (!$auth->hasIdentity())
        {
            $this->_redirect('/admin/login/admin-login');
        }
    }

    public function indexAction()
    {
        if (isset($this->user_session->msg))
        {
            $this->view->msg = $this->user_session->msg;
            unset($this->user_session->msg);
        }

        $results = $this->sliders->getAllSlides();
        $this->view->slider_data = $results;
        /* if (count($results) > 0) {
        $this->Paginator($results, 10);
        } else {
        $this->view->empty_rec = true;
        } */
    }

    public function editAction()
    {

        $id = $this->_request->getParam('id');
        if (!isset($id))
            $this->_redirect('admin/slider/index');

        $form = new Application_Form_SliderForm();
        
        $result = $this->sliders->getSliderByID($id);
        
        //var_dump($result);return;
        $this->view->id = $result->slider_id;
        $form->name->setValue($result->name);
        $this->view->name = $result->name;
        
        $form->slide1->setValue($result->slide1);
        $this->view->slide1 = $result->slide1;
        $form->link1->setValue($result->link1);
        
        $form->slide2->setValue($result->slide2);
        $this->view->slide2 = $result->slide2;
        $form->link2->setValue($result->link2);
        
        $form->slide3->setValue($result->slide3);
        $this->view->slide3 = $result->slide3;
        $form->link3->setValue($result->link3);
        
        $form->slide4->setValue($result->slide4);
        $this->view->slide4 = $result->slide4;
        $form->link4->setValue($result->link4);
        
        $form->slide5->setValue($result->slide5);
        $this->view->slide5 = $result->slide5;
        $form->link5->setValue($result->link5);
        
        $form->slide6->setValue($result->slide6);
        $this->view->slide6 = $result->slide6;
        $form->link6->setValue($result->link6);
        
        $this->view->form = $form;
        
        if (!$this->_request->isPost())
            return;
            
        $formData = $this->_request->getPost();
        if (!$form->isValid($formData))
            return;

        //var_dump($formData); return;
        
        //For upload slide 1
        $file_name1 = null;
        $image_name1 = $_FILES["slide1"]["name"];

        if (isset($image_name1) && strlen($image_name1) > 0)
        {
            try
            {
                if (isset($result->slide1))
                {
                    $image_file = SYSTEM_PATH . "/images/user/slides/" . $result->slide1;

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/" . $result->slide1);
                    }

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/200X200/" . $result->slide1);
                    }
                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/500X500/" . $result->slide1);
                    }
                }

                $slide = $_FILES['slide1']['name'];
                $random = rand(9, 999999);
                $file_name1 = $random . $slide;
                $formData["slide1"] = $file_name1;

                move_uploaded_file($_FILES["slide1"]['tmp_name'], SYSTEM_PATH .
                    "images/user/slides/" . $file_name1);
                $thumb = new Application_Model_Thumbnail(SYSTEM_PATH .
                    "images/user/slides/" . $file_name1);

                $thumb->resize(500, 500);
                $thumb->save(SYSTEM_PATH . "images/user/slides/500X500/" . $file_name1);

                $thumb->resize(200, 200);
                $thumb->save(SYSTEM_PATH . "images/user/slides/200X200/" . $file_name1);
            }
            catch (Zend_File_Transfer_Exception $e)
            {
                throw new Exception('Bad data: ' . $e->getMessage());
            }
        } else
        {

            $formData['slide1'] = $result->slide1;
        }

        //For upload slide 2
        $file_name2 = null;
        $image_name2 = $_FILES["slide2"]["name"];

        if (isset($image_name2) && strlen($image_name2) > 0)
        {
            try
            {
                if (isset($result->slide2))
                {
                    $image_file = SYSTEM_PATH . "/images/user/slides/" . $result->slide2;

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/" . $result->slide2);
                    }

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/200X200/" . $result->slide2);
                    }
                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/500X500/" . $result->slide2);
                    }
                }

                $slide = $_FILES['slide2']['name'];
                $random = rand(9, 999999);
                $file_name2 = $random . $slide;
                $formData["slide2"] = $file_name2;

                move_uploaded_file($_FILES["slide2"]['tmp_name'], SYSTEM_PATH .
                    "images/user/slides/" . $file_name2);
                $thumb = new Application_Model_Thumbnail(SYSTEM_PATH .
                    "images/user/slides/" . $file_name2);

                $thumb->resize(500, 500);
                $thumb->save(SYSTEM_PATH . "images/user/slides/500X500/" . $file_name2);

                $thumb->resize(200, 200);
                $thumb->save(SYSTEM_PATH . "images/user/slides/200X200/" . $file_name2);
            }
            catch (Zend_File_Transfer_Exception $e)
            {
                throw new Exception('Bad data: ' . $e->getMessage());
            }
        } else
        {

            $formData['slide2'] = $result->slide2;
        }

        //For upload slide 3
        $file_name3 = null;
        $image_name3 = $_FILES["slide3"]["name"];

        if (isset($image_name3) && strlen($image_name3) > 0)
        {
            try
            {
                if (isset($result->slide3))
                {
                    $image_file = SYSTEM_PATH . "/images/user/slides/" . $result->slide3;

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/" . $result->slide3);
                    }

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/200X200/" . $result->slide3);
                    }
                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/500X500/" . $result->slide3);
                    }
                }

                $slide = $_FILES['slide3']['name'];
                $random = rand(9, 999999);
                $file_name3 = $random . $slide;
                $formData["slide3"] = $file_name3;

                move_uploaded_file($_FILES["slide3"]['tmp_name'], SYSTEM_PATH .
                    "images/user/slides/" . $file_name3);
                $thumb = new Application_Model_Thumbnail(SYSTEM_PATH .
                    "images/user/slides/" . $file_name3);

                $thumb->resize(500, 500);
                $thumb->save(SYSTEM_PATH . "images/user/slides/500X500/" . $file_name3);

                $thumb->resize(200, 200);
                $thumb->save(SYSTEM_PATH . "images/user/slides/200X200/" . $file_name3);
            }
            catch (Zend_File_Transfer_Exception $e)
            {
                throw new Exception('Bad data: ' . $e->getMessage());
            }
        } else
        {

            $formData['slide3'] = $result->slide3;
        }
        
        //For upload slide 4
        $file_name4 = null;
        $image_name4 = $_FILES["slide4"]["name"];

        if (isset($image_name4) && strlen($image_name4) > 0)
        {
            try
            {
                if (isset($result->slide4))
                {
                    $image_file = SYSTEM_PATH . "/images/user/slides/" . $result->slide4;

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/" . $result->slide4);
                    }

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/200X200/" . $result->slide4);
                    }
                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/500X500/" . $result->slide4);
                    }
                }

                $slide = $_FILES['slide4']['name'];
                $random = rand(9, 999999);
                $file_name4 = $random . $slide;
                $formData["slide4"] = $file_name4;

                move_uploaded_file($_FILES["slide4"]['tmp_name'], SYSTEM_PATH .
                    "images/user/slides/" . $file_name4);
                $thumb = new Application_Model_Thumbnail(SYSTEM_PATH .
                    "images/user/slides/" . $file_name4);

                $thumb->resize(500, 500);
                $thumb->save(SYSTEM_PATH . "images/user/slides/500X500/" . $file_name4);

                $thumb->resize(200, 200);
                $thumb->save(SYSTEM_PATH . "images/user/slides/200X200/" . $file_name4);
            }
            catch (Zend_File_Transfer_Exception $e)
            {
                throw new Exception('Bad data: ' . $e->getMessage());
            }
        } else
        {

            $formData['slide4'] = $result->slide4;
        }
        
        //For upload slide 5
        $file_name5 = null;
        $image_name5 = $_FILES["slide5"]["name"];

        if (isset($image_name5) && strlen($image_name5) > 0)
        {
            try
            {
                if (isset($result->slide5))
                {
                    $image_file = SYSTEM_PATH . "/images/user/slides/" . $result->slide5;

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/" . $result->slide5);
                    }

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/200X200/" . $result->slide5);
                    }
                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/500X500/" . $result->slide5);
                    }
                }

                $slide = $_FILES['slide5']['name'];
                $random = rand(9, 999999);
                $file_name5 = $random . $slide;
                $formData["slide5"] = $file_name5;

                move_uploaded_file($_FILES["slide5"]['tmp_name'], SYSTEM_PATH .
                    "images/user/slides/" . $file_name5);
                $thumb = new Application_Model_Thumbnail(SYSTEM_PATH .
                    "images/user/slides/" . $file_name5);

                $thumb->resize(500, 500);
                $thumb->save(SYSTEM_PATH . "images/user/slides/500X500/" . $file_name5);

                $thumb->resize(200, 200);
                $thumb->save(SYSTEM_PATH . "images/user/slides/200X200/" . $file_name5);
            }
            catch (Zend_File_Transfer_Exception $e)
            {
                throw new Exception('Bad data: ' . $e->getMessage());
            }
        } else
        {

            $formData['slide5'] = $result->slide5;
        }
        
        //For upload slide 6
        $file_name6 = null;
        $image_name6 = $_FILES["slide6"]["name"];

        if (isset($image_name6) && strlen($image_name6) > 0)
        {
            try
            {
                if (isset($result->slide6))
                {
                    $image_file = SYSTEM_PATH . "/images/user/slides/" . $result->slide6;

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/" . $result->slide6);
                    }

                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/200X200/" . $result->slide6);
                    }
                    if (file_exists($image_file))
                    {
                        unlink(SYSTEM_PATH . "/images/user/slides/500X500/" . $result->slide6);
                    }
                }

                $slide = $_FILES['slide6']['name'];
                $random = rand(9, 999999);
                $file_name6 = $random . $slide;
                $formData["slide6"] = $file_name6;

                move_uploaded_file($_FILES["slide6"]['tmp_name'], SYSTEM_PATH .
                    "images/user/slides/" . $file_name6);
                $thumb = new Application_Model_Thumbnail(SYSTEM_PATH .
                    "images/user/slides/" . $file_name6);

                $thumb->resize(500, 500);
                $thumb->save(SYSTEM_PATH . "images/user/slides/500X500/" . $file_name6);

                $thumb->resize(200, 200);
                $thumb->save(SYSTEM_PATH . "images/user/slides/200X200/" . $file_name6);
            }
            catch (Zend_File_Transfer_Exception $e)
            {
                throw new Exception('Bad data: ' . $e->getMessage());
            }
        } else
        {

            $formData['slide6'] = $result->slide6;
        }

        $formData['id'] = $id;
        $result = $this->sliders->updateSlider($formData);
        $this->view->msg = $result;
        $this->_redirect("/admin/sliders/edit/id/" . $id);
    }

    public function ajaxed()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest())
            return; // if not a ajax request leave function

    }


    // Paginator action
    public function Paginator($results, $records)
    {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage($records);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

    public function __call($method, $args)
    {
        if ('Action' == substr($method, -6))
        {
            // If the action method was not found, forward to the index action
            return $this->_forward('admin/index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "' . $method . '" called', 500);
    }
}
