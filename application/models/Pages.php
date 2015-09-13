<?php
 
class Application_Model_Pages extends Zend_Db_Table
{ 
    protected $_name = 'pages';
    protected $_primary = 'page_id';
    protected $result = null;
  
 
  public function getPageByID($id){
	 $select = $this->select();
	 $select->from($this)->where("page_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
  }
 
  // add new page
  public function addPage($formData) {
  $data = array('url_slug' => $formData['url_slug'],
				'user_id' => $formData['user_id'],
				'date_published' => $formData['date_published'],
				'is_in_draft' => $formData['is_in_draft'],
				'date_created' => $formData['date_created'],
				'title' => $formData['title'],
				'image' => $formData['image'],
				'description' => $formData['description']);
				 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>New Page Added Successfully </div>" ;
		}  else {
			return "Some error occurred in Creating a Page";
		}
   }
  
 // add draft page
    public function addDraftPage($formData){
        $data = array('user_id' => $formData['user_id'],
            'url_slug' => $formData['url_slug'],
            'is_in_draft' => $formData['is_in_draft'],
            'date_created' => $formData['date_created'],
            'date_published' => $formData['date_published'],
            'title' => $formData['title'],
            'image' => $formData['image'],
            'description' => $formData['description']);

        $result = $this->insert($data);
        if ($result)
        {
            return "<div class='alert alert-success'>Page save in Draft Successfully. </div>";
        } else
        {
            return "Some error in saving record";
        }
    }
 
	public function getPageByUrl($page)
    {
        $select = $this->select();
        $select->from($this)->where("url_slug = ?", $page);
        $result = $this->fetchRow($select);
        return $result;
    }

	// check for slug name from db
	public function checkPageSlug($slug){
	$select = $this->select();
	$select->from($this)->where('url_slug = ?', $slug);
	$result = $this->fetchRow($select);
	return $result;
	}
 
  // for get all published post
    public function getAllPages($db)
    {
        $select = new Zend_Db_Select($db);
        $cols = array(
            'page_id',
            'user_id',
            'date_created',
            'title',
            'image',
           // 'short_dsc' => 'left(description, 500)',
            'description',
            'is_in_draft',
            'date_published');
        $select->from(array('p' => 'pages'), $cols)->where("is_in_draft = 0")->order('date_published DESC');
        // ->joinLeft(array('u' => 'users'),'u.user_id = p.user_id',array('user_name','role_id'));//->where("p.page_id =?", 'c.page_id' );
        $stmt = $db->query($select);
        $results = $stmt->fetchAll();
        return $results;
    }

    // for get all draft post
    public function getAllDraftPages($db)
    {
        $select = new Zend_Db_Select($db);
        $cols = array(
            'page_id',
            'user_id',
            'date_created',
            'title',
            'image',
            'description',
            'is_in_draft',
            'date_published');
        $select->from(array('p' => 'pages'), $cols)->where("is_in_draft = 1")->order('date_published DESC');
        //->joinLeft(array('u' => 'users'),'u.user_id = p.user_id',array('user_name','role_id'));//->where("p.page_id =?", 'c.page_id' );
        $stmt = $db->query($select);
        $results = $stmt->fetchAll();
        return $results;
    }

	 //this function is used for finding page from admin page list
    public function findPage($name)
    {
        $select = $this->select();
        $cols = array(
            'page_id',
            'user_id',
            'date_created',
            'title',
            'image',
            //'short_dsc' => 'left(description, 500)',
            'description',
            'is_in_draft',
            'date_published');
        $select->from($this, $cols)->where("title like ? ", "%" . $name . "%")->
            orWhere("date_published like ? ", "%" . $name . "%");
        $result = $this->fetchAll($select);
        return $result;
    }
 
	// for update post
    public function updatePage($formData)
    {

        $data = array(
            "title" => $formData['title'],
            "image" => $formData['image'],
            "url_slug" => $formData['url_slug'],
            "is_in_draft" => $formData['is_in_draft'],
            "description" => $formData['description']);
        $where = "page_id = " . (int)$formData["page_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Page Updated Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error in update record</div>";
        }
    }

    // for update draft post
    public function updateDraftPage($formData)
    {
        $data = array(
            "title" => $formData['title'],
            "image" => $formData['image'],
            "url_slug" => $formData['url_slug'],
            "is_in_draft" => $formData['is_in_draft'],
            "description" => $formData['description']);
        $where = "page_id = " . (int)$formData["page_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Page Updated and Save in Draft Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error in update record</div>";
        }
    }

	// get page by slug
	 public function getPostByUrl($page)
    {
        $select = $this->select();
        $select->from($this)->where("url_slug = ?", $page);
        $result = $this->fetchRow($select);
        return $result;
    }

    // for remove page
    public function removePage($db, $id)
    {
        $rowset = $this->fetchAll();
        $rowCount = count($rowset);
        if ($rowCount < 2 || $rowCount == 1)
            return 3;

        $id = $this->delete($db->quoteInto("page_id = ?", $id));
        if ($id > 0)
        {
            return 1;
        } else
        {
            return 2;
        }
    }
	
	public function getLastInsertedRecord()
{
$select = $this->select();
$select->from($this)->where("is_in_draft = 0")->order('page_id DESC');
$result = $this->fetchRow($select);
return $result;
}

}