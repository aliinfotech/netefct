<?php
class AjaxImagesController extends Zend_Controller_Action{
    
var $member_session = null;
private $authAdapter = null;
private $db = null;
 private $categories = null;
public function init(){
//     $this->_helper->layout()->disableLayout();
		$this->_helper->layout->setLayout('vednor');
              //  $this->view->header = "vendor-header.phtml";	
		$this->member_session = new Zend_Session_Namespace("member_session");
                $this->db = Zend_Db_Table::getDefaultAdapter();
                $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
                ini_set("max_execution_time", 0);//DO NOT EXPIRE SESSOIN
                $this->category_session = new Zend_Session_Namespace("category_session");
				}

	 public function indexAction() {
    
        }
    
//Product change Main image
 public function changeProductImageAction(){
$products = new Application_Model_Admin_Product();
$targetFolder = '/uploads/images/products/originals/'; // Relative to the root
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
$product_id = $_POST['product_id'];
$old_image = $products->getImage($product_id);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];

	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
	move_uploaded_file($tempFile,$targetFile);
	//move_uploaded_file($_FILES['Filedata']['name'], SYSTEM_PATH ."/uploads/images/categories/originals/".$file_name);
        $file_name = $_FILES['Filedata']['name'];
//        $ext =  pathinfo($file_name, PATHINFO_EXTENSION); 
//        $rnd = rand(9,99);
//        $time_stamp = time();
//        $file_name = pathinfo($file_name, PATHINFO_FILENAME); // 
//	$file_name .= "-".$rnd. $time_stamp."." .$ext; 
        
        $thumb = new Application_Model_Admin_Thumbnail(SYSTEM_PATH ."/uploads/images/products/originals/".$file_name);
	$thumb->resize(500,500);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/500X500/'.$file_name);
        $thumb->resize(250,250);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/250X250/'.$file_name);
        $thumb->resize(50,50);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/50X50/'.$file_name);
        $this->deleteProductImage($old_image);
         $id = $products->updateMainImage($product_id, $file_name);
      //  echo "Update";
	        
        } else {
		echo 'Invalid file type.';
	}
 }
    }
    
private function deleteProductImage($image_file){
 try{
unlink(SYSTEM_PATH ."uploads/images/products/50X50/".$image_file);
$this->product_session->image_remove_report .= "<br/> Image ".$image_file . " has been removed successfully";
 }catch(Exception $e){
}
 try{
unlink(SYSTEM_PATH ."uploads/images/products/250X250/".$image_file);
 }catch(Exception $e){

}
 try{
unlink(SYSTEM_PATH ."uploads/images/products/500X500/".$image_file);
 }catch(Exception $e){
}
 try{
unlink(SYSTEM_PATH ."uploads/images/products/originals/".$image_file);
 }catch(Exception $e){
} 
}

        
public function uploadMoreImagesAction(){
$this->_helper->viewRenderer->setNoRender();
  $this->_helper->layout()->disableLayout();
 $result = array();
 
   $product_id = $this->getRequest()->getParam('product_id');
   $product_images = new Application_Model_Admin_ProductImages();
if (isset($_FILES['photoupload']) )
{
	$file = $_FILES['photoupload']['tmp_name'];
	$error = false;
	$size = false;

	if (!is_uploaded_file($file) || ($_FILES['photoupload']['size'] > 2 * 1024 * 1024) )
	{
		$error = 'Please upload only files smaller than 2Mb!';
	}
	if (!$error && !($size = @getimagesize($file) ) )
	{
		$error = 'Please upload only images, no other files are supported.';
	}
	if (!$error && !in_array($size[2], array(1, 2, 3, 7, 8) ) )
	{
		$error = 'Please upload only images of type JPEG.';
	}
	if (!$error && ($size[0] < 25) || ($size[1] < 25))
	{
		$error = 'Please upload an image bigger than 25px.';
	}
	else {
            
	$image_name = $_FILES['photoupload']['name'];
        $random = rand(10,10000);
        $time = time() + (7 * 24 * 60 * 60);
        $image_name = $time . $random . $image_name;
        
        move_uploaded_file($_FILES['photoupload']['tmp_name'], SYSTEM_PATH ."/uploads/images/products/originals/".$image_name);
        $thumb = new Application_Model_Admin_Thumbnail(SYSTEM_PATH ."/uploads//images/products/originals/".$image_name);
        $thumb->resize(500,500);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/500X500/'.$image_name);
//        $thumb->resize(350,350);
//	$thumb->save(SYSTEM_PATH .'/uploads/images/products/350X350/'.$image_name);
//        $thumb->resize(300,300);
//	$thumb->save(SYSTEM_PATH .'/uploads/images/products/300X300/'.$image_name);

        $thumb->resize(250,250);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/250X250/'.$image_name);

//        $thumb->resize(100,100);
//	$thumb->save(SYSTEM_PATH .'/uploads/images/products/100X100/'.$image_name);

	$thumb->resize(50,50);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/50X50/'.$image_name);
                //chmod(SYSTEM_PATH ."/images/products/".$_FILES['photoupload']['name'], 0777);
                $data = array('product_id'=> $product_id,'image_name' => $image_name);
		$product_images->insert($data);
        }
//$addr = gethostbyaddr($_SERVER['REMOTE_ADDR']);
//	$log = fopen('script.log', 'a');
//	fputs($log, ($error ? 'FAILED' : 'SUCCESS') . ' - ' . preg_replace('/^[^.]+/', '***', $addr) . ": {$_FILES['photoupload']['name']} - {$_FILES['photoupload']['size']} byte\n" );
//	fclose($log);
//
////my code
//	$log = fopen(SYSTEM_PATH .'imagelist.log', 'a');
//	fputs($log, "{$_FILES['photoupload']['name']}\n" );
//	fclose($log);

	if ($error)
	{
		$result['result'] = 'failed';
		$result['error'] = $error;
	}
	else
	{
		$result['result'] = 'success';
		$result['size'] = "Uploaded image $image_name ({$size['mime']}) with  {$size[0]}px/{$size[1]}px. " ;
	}

}
else
{
	$result['result'] = 'error';
	$result['error'] = 'Missing file or internal error!';
}

if (!headers_sent() )
{
	header('Content-type: application/json');
}
//echo  json_encode($result);
echo Zend_Json::encode($result);
}
// Banner Image 
public function bannerImagesAction(){
$this->_helper->viewRenderer->setNoRender();
  $this->_helper->layout()->disableLayout();
 $result = array();
 
   $product_id = $this->getRequest()->getParam('product_id');
   $banner_images = new Application_Model_Admin_BannerImages();
if (isset($_FILES['photoupload']) )
{
	$file = $_FILES['photoupload']['tmp_name'];
	$error = false;
	$size = false;

	if (!is_uploaded_file($file) || ($_FILES['photoupload']['size'] > 2 * 1024 * 1024) )
	{
		$error = 'Please upload only files smaller than 2Mb!';
	}
	if (!$error && !($size = @getimagesize($file) ) )
	{
		$error = 'Please upload only images, no other files are supported.';
	}
	if (!$error && !in_array($size[2], array(1, 2, 3, 7, 8) ) )
	{
		$error = 'Please upload only images of type JPEG.';
	}
	if (!$error && ($size[0] < 25) || ($size[1] < 25))
	{
		$error = 'Please upload an image bigger than 25px.';
	}
	else {
            
	$image_name = $_FILES['photoupload']['name'];
        $random = rand(10,10000);
        $time = time() + (7 * 24 * 60 * 60);
        $image_name = $time . $random . $image_name;
        
        move_uploaded_file($_FILES['photoupload']['tmp_name'], SYSTEM_PATH ."/uploads/images/theme/".$image_name);
       // $thumb = new Application_Model_Admin_Thumbnail(SYSTEM_PATH ."/uploads//images/theme/".$image_name);
      
	//chmod(SYSTEM_PATH ."/images/products/".$_FILES['photoupload']['name'], 0777);
                $data = array('banner_image' => $image_name);
		$banner_images->insert($data);
        }
	if ($error)
	{
		$result['result'] = 'failed';
		$result['error'] = $error;
	}
	else
	{
		$result['result'] = 'success';
		$result['size'] = "Uploaded image $image_name ({$size['mime']}) with  {$size[0]}px/{$size[1]}px. " ;
	}

}
else
{
	$result['result'] = 'error';
	$result['error'] = 'Missing file or internal error!';
}

if (!headers_sent() )
{
	header('Content-type: application/json');
}
//echo  json_encode($result);
echo Zend_Json::encode($result);
}



//add Product Photos Action
public function addProPhoAction(){

$this->_helper->viewRenderer->setNoRender();
 $this->_helper->layout()->disableLayout();
 $result = array();

    $limits_tbl = new Application_Model_Limits();
    $product_table = new Application_Model_Admin_Product();
    $product_limit = $limits_tbl->getProductsLimit();
    $count = $this->db->fetchOne( 'SELECT COUNT(*) AS count FROM product' );
    if($product_limit != 0){
     if($count > $product_limit){
         $result['result'] = 'error';
	$result['error'] = 'Sorry you can not add any more product as you have already reached to your products limit which is '. $product_limit  ;
          
if (!headers_sent() )
{
	header('Content-type: application/json');
}
         return;
         }   
        
    }
 


//echo  json_encode($result);
echo Zend_Json::encode($result); 
 
$category_id = $this->getRequest()->getParam('category_id');
$vendor_id = $this->getRequest()->getParam('vendor_id');
$vendor_parent_id = $this->getRequest()->getParam('vendor_parent_id');
$main_cat = $this->getRequest()->getParam('main_cat');
$sub_cat1 = $this->getRequest()->getParam('sub-cat1');
$sub_cat2 = $this->getRequest()->getParam('sub_cat2');
$sub_cat3 = $this->getRequest()->getParam('sub_cat3');
$common_name = $this->getRequest()->getParam('common_name');
$common_price = $this->getRequest()->getParam('common_price');
$category_name = $this->getRequest()->getParam('category_name');
// get all fields data conver date to mysql default date formate  

$new_from_date = '0000-00-00';
$new_to_date = '0000-00-00';
$sale_from_date = '0000-00-00';
$sale_to_date = '0000-00-00';

// $result['result'] = 'success';
// //$result['size'] = $vendor_id . $main_cat. $category_name. $sub_cat1 .$sub_cat2 .$sub_cat3 ;
// $result['size'] = $vendor_id;
// 
// if (!headers_sent() )
//{
//	header('Content-type: application/json');
//}
// echo Zend_Json::encode($result);
//  return;


if(!isset($category_id)) $this->_redirect("/admin/product/add-products");    
 
if (isset($_FILES['photoupload']) )
{
	$file = $_FILES['photoupload']['tmp_name'];
	$error = false;
	$size = false;

	if (!is_uploaded_file($file) || ($_FILES['photoupload']['size'] > 3 * 1024 * 1024) )
	{
		$error = 'Please upload only files smaller than 3 Mb!';
	}
	if (!$error && !($size = @getimagesize($file) ) )
	{
		//$error = 'Please upload only images, no other files are supported.';
	}
	if (!$error && !in_array($size[2], array(1, 2, 3, 7, 8) ) )
	{
		//$error = 'Please upload only images of type JPEG.';
	}
	if (!$error && ($size[0] < 25) || ($size[1] < 25))
	{
		$error = 'Please upload an image bigger than 25px.';
	}
	else {
            

        $image_name = $_FILES['photoupload']['name'];
        $ext =  pathinfo($image_name, PATHINFO_EXTENSION); 
	//$image_name = pathinfo($image_name, PATHINFO_FILENAME); // 
	 $rnd = rand(9,9999);
         $image_name_ext = "-".$rnd."." .$ext; 
//      
        
        if(isset($common_name)){
        $common_name_image = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $common_name);
        $image_name = $common_name_image.$image_name_ext;
        }
        
        move_uploaded_file($_FILES['photoupload']['tmp_name'], SYSTEM_PATH ."/uploads/images/products/originals/".$image_name);
        $thumb = new Application_Model_Admin_Thumbnail(SYSTEM_PATH ."/uploads//images/products/originals/".$image_name);
        $thumb->resize(500,500);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/500X500/'.$image_name);
//        $thumb->resize(350,350);
//	$thumb->save(SYSTEM_PATH .'/uploads/images/products/350X350/'.$image_name);
        $thumb->resize(250,250);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/250X250/'.$image_name);

//        $thumb->resize(150,150);
//	$thumb->save(SYSTEM_PATH .'/uploads/images/products/300X300/'.$image_name);
//
//        $thumb->resize(100,100);
//	$thumb->save(SYSTEM_PATH .'/uploads/images/products/100X100/'.$image_name);

	$thumb->resize(50,50);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/50X50/'.$image_name);
                //chmod(SYSTEM_PATH ."/images/products/".$_FILES['photoupload']['name'], 0777);

        if(isset($common_name)){
            $product_name = $common_name;
        }else{
            $product_name = $category_name;
        }
        if(!isset($common_price))$common_price = "";
        $currency_symbol = 'RM';
               
    $data = array("product_name" => $product_name,"price" => $common_price, "currency_symbol" => $currency_symbol
    ,'main_image'=> $image_name,"category_id"=>$category_id, "top_category_id" => $main_cat, "sub_cat1" => $sub_cat1,
    "sub_cat2" => $sub_cat2,"sub_cat3" => $sub_cat3,'date_added' => date("Y-m-d"),"is_top" => 1,
        "new_from_date"=>$new_from_date,"new_to_date"=>$new_to_date,"sale_from_date"=>$sale_from_date,"sale_to_date"=>$sale_to_date, "meta_title" => $product_name,"meta_description" => $product_name);

 //   $data = array("vendor_id" => $vendor_id,"is_top" => 1);
$product_table = new Application_Model_Admin_Product();
$product_table->addProduct($data);

        }
	if ($error)
	{
		$result['result'] = 'failed';
		$result['error'] = $error;
	}
	else
	{
		$result['result'] = 'success';
		//$result['size'] = "Uploaded image $image_name ({$size['mime']}) with  {$size[0]}px/{$size[1]}px. " ;
	
		$result['size'] = "Uploaded image". $common_name ."({$size['mime']})" ;
                
        }

}
else
{
	$result['result'] = 'error';
	$result['error'] = 'Missing file or internal error!';
}

if (!headers_sent() )
{
	header('Content-type: application/json');
}
//echo  json_encode($result);
echo Zend_Json::encode($result);
}
   
        
    public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }
    
    //gallery images section
    // Banner Image 
public function galleryPhotosAction(){
$this->_helper->viewRenderer->setNoRender();
  $this->_helper->layout()->disableLayout();
 $result = array();
$gallery = new Application_Model_Admin_Gallery();
if (isset($_FILES['photoupload']) )
{
	$file = $_FILES['photoupload']['tmp_name'];
	$error = false;
	$size = false;

	if (!is_uploaded_file($file) || ($_FILES['photoupload']['size'] > 2 * 1024 * 1024) )
	{
		$error = 'Please upload only files smaller than 2Mb!';
	}
	if (!$error && !($size = @getimagesize($file) ) )
	{
		$error = 'Please upload only images, no other files are supported.';
	}
	if (!$error && !in_array($size[2], array(1, 2, 3, 7, 8) ) )
	{
		$error = 'Please upload only images of type JPEG.';
	}
	if (!$error && ($size[0] < 25) || ($size[1] < 25))
	{
		$error = 'Please upload an image bigger than 25px.';
	}
	else {
            
	$image_name = $_FILES['photoupload']['name'];
//        $random = rand(10,100);
//        $time = time() + (7 * 24 * 60 * 60);
//        $image_name = $time . $random . $image_name;
//        
        move_uploaded_file($_FILES['photoupload']['tmp_name'], SYSTEM_PATH ."/uploads/images/gallery/".$image_name);
       // $thumb = new Application_Model_Admin_Thumbnail(SYSTEM_PATH ."/uploads//images/theme/".$image_name);
      
	//chmod(SYSTEM_PATH ."/images/products/".$_FILES['photoupload']['name'], 0777);
                $data = array('image_name' => $image_name);
		$gallery->insert($data);
        }
	if ($error)
	{
		$result['result'] = 'failed';
		$result['error'] = $error;
	}
	else
	{
		$result['result'] = 'success';
		$result['size'] = "Uploaded image $image_name ({$size['mime']}) with  {$size[0]}px/{$size[1]}px. " ;
	}

}
else
{
	$result['result'] = 'error';
	$result['error'] = 'Missing file or internal error!';
}

if (!headers_sent() )
{
	header('Content-type: application/json');
}
//echo  json_encode($result);
echo Zend_Json::encode($result);
}

//category change image
    public function changeImageAction(){
   $targetFolder = '/uploads/images/categories/originals/'; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
	//move_uploaded_file($_FILES['Filedata']['name'], SYSTEM_PATH ."/uploads/images/categories/originals/".$file_name);
        $file_name = $_FILES['Filedata']['name'];
        $thumb = new Application_Model_Admin_Thumbnail(SYSTEM_PATH ."/uploads/images/categories/originals/".$file_name);
	$thumb->resize(250,250);
	$thumb->save(SYSTEM_PATH .'/uploads/images/categories/250X250/'.$file_name);
        $thumb->resize(50,50);
	$thumb->save(SYSTEM_PATH .'/uploads/images/categories/50X50/'.$file_name);
        $categories = new Application_Model_Admin_Category();
        $this->deleteCategoryImages($this->category_session->parent_id);
        $categories->updateImage($this->category_session->parent_id, $file_name);
       
        echo '1';
	        
        } else {
		echo 'Invalid file type.';
	}
 }
    }
 
public function caAction(){
    $this->ajaxed();
echo "here";
}
 
public function deleteCategoryAction(){
    $this->ajaxed();
      $categories = new Application_Model_Admin_Category();
      if($categories->isParent($this->db, $this->category_session->parent_id)){
        echo "parent"; //this is a flag for showing message in view
      }else{
      $this->deleteCategoryImages($this->category_session->parent_id);
      $categories->deleteRecord($this->category_session->parent_id);
        $this->category_session->parent_id = $this->category_session->parent_id - 1;
       echo $this->category_session->parent_id;
       $this->category_session->added = true;  
      }
       
}    
    
private function deleteCategoryImages($category_id){
 $category_table = new Application_Model_Admin_Category();
  //Get image for this category and remove all images from category folders 
 $image_file = $category_table->fetchCategoryImage($category_id);
//delete image file 
 if(isset ($image_file)){
 try{
unlink(SYSTEM_PATH ."uploads/images/categories/50X50/".$image_file);
 }catch(Exception $e){

}
 try{
unlink(SYSTEM_PATH ."uploads/images/categories/250X250/".$image_file);
 }catch(Exception $e){

}
 try{
unlink(SYSTEM_PATH ."uploads/images/categories/originals/".$image_file);
 }catch(Exception $e){

}
 }
}



//Add Extra Product Images
 public function updateImage1Action(){
   $this->ajaxed();
   echo 'working';
   return;
  $targetFolder = '/uploads'; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
	//	move_uploaded_file($tempFile,$targetFile);
		echo 'working';
	} else {
		echo 'Invalid file type.';
	}
} 
   
$product_images = new Application_Model_Admin_ProductImages();
$targetFolder = '/uploads/images/products/originals/'; // Relative to the root
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
$product_id = $_POST['product_id'];

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];

	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
        
	if (in_array($fileParts['extension'],$fileTypes)) {
	move_uploaded_file($tempFile,$targetFile);
        $file_name = $_FILES['Filedata']['name'];
        
        $data = array('product_id'=> $product_id,'image_name' => $file_name);
	$product_images->insert($data);
        
        $thumb = new Application_Model_Admin_Thumbnail(SYSTEM_PATH ."/uploads/images/products/originals/".$file_name);
	$thumb->resize(500,500);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/500X500/'.$file_name);
        $thumb->resize(250,250);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/250X250/'.$file_name);
        $thumb->resize(50,50);
	$thumb->save(SYSTEM_PATH .'/uploads/images/products/50X50/'.$file_name);
       
        echo "Added";
	        
        } else {
		echo 'Invalid file type.';
	}
 }
    }
public function deleteExtImageAction(){
  $this->ajaxed();
  $image_id = $this->getRequest()->getParam('image_id');
  $image_file = $this->getRequest()->getParam('image_name');
    $product_images = new Application_Model_Admin_ProductImages();
    $product_images->removeByImageID($image_id);
    
 try{
unlink(SYSTEM_PATH ."uploads/images/products/50X50/".$image_file);
 }catch(Exception $e){
}
 try{
unlink(SYSTEM_PATH ."uploads/images/products/250X250/".$image_file);
 }catch(Exception $e){

}
 try{
unlink(SYSTEM_PATH ."uploads/images/products/500X500/".$image_file);
 }catch(Exception $e){
}
 try{
unlink(SYSTEM_PATH ."uploads/images/products/originals/".$image_file);
 }catch(Exception $e){
} 
echo "Image ". $image_file . " is deleted";
}


//this function is used for every function that recieves a ajax call
    public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()
            )return; // if not a ajax request leave function

    }
}//class ends 