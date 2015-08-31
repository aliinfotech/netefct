<?php

class Application_Model_Posts extends Zend_Db_Table
{
    protected $_name = 'posts';
    protected $_primary = 'post_id';
    protected $result = null;


    public function getPostByID($id)
    {
        $select = $this->select();
        $select->from($this)->where("post_id = ?", $id);
        $result = $this->fetchRow($select);
        return $result;
    }

    public function getPostByUrl($page)
    {
        $select = $this->select();
        $select->from($this)->where("url = ?", $page);
        $result = $this->fetchRow($select);
        return $result;
    }

	// check for slug name from db
	public function checkPostSlug($slug){
	$select = $this->select();
	$select->from($this)->where('url = ?', $slug);
	$result = $this->fetchRow($select);
	return $result;
	}
	
    // add new post
    public function addPost($formData)
    {
        $data = array(
            'user_id' => $formData['user_id'],
            'url' => $formData['url'],
            'date_created' => $formData['date_created'],
            'date_published' => $formData['date_published'],
            'heading' => $formData['heading'],
            "is_in_draft" => $formData['is_in_draft'],
            'image' => $formData['image'],
            'description' => $formData['description'],
            'is_comment' => $formData['is_comment'],
            'tags' => $formData['tags']);

        $result = $this->insert($data);
        if ($result)
        {
            return "<div class='alert alert-success'>New POST Added Successfully </div>";
        } else
        {
            return "Some error in saving record";
        }
    }

    // add draft post
    public function addDraftPost($formData)
    {


        $data = array(
            'user_id' => $formData['user_id'],
            'url' => $formData['url'],
            "is_in_draft" => $formData['is_in_draft'],
            'date_created' => $formData['date_created'],
            'date_published' => $formData['date_published'],
            'heading' => $formData['heading'],
            'image' => $formData['image'],
            'is_comment' => $formData['is_comment'],
            'description' => $formData['description'],
            'tags' => $formData['tags']);

        $result = $this->insert($data);
        if ($result)
        {
            return "<div class='alert alert-success'>POST save in Draft Successfully. </div>";
        } else
        {
            return "Some error in saving record";
        }
    }
    // for get all published post
    public function getAllPosts($db)
    {
        $select = new Zend_Db_Select($db);
        $cols = array(
            'post_id',
            'user_id',
            'date_created',
            'heading',
            'image',
            'short_dsc' => 'left(description, 500)',
            'description',
            'tags',
            'is_comment',
            'is_in_draft',
            'date_published');
        $select->from(array('p' => 'posts'), $cols)->where("is_in_draft = 0")->order('date_published DESC');
        // ->joinLeft(array('u' => 'users'),'u.user_id = p.user_id',array('user_name','role_id'));//->where("p.page_id =?", 'c.page_id' );
        //->joinLeft(array('pc' => 'post_comments'),'pc.post_id = p.post_id',array('comment_date','comment','pc_id'));//->where("p.page_id =?", 'c.page_id' );
        $stmt = $db->query($select);
        $results = $stmt->fetchAll();
        return $results;
    }

    // for get all draft post
    public function getAllDraftPosts($db)
    {
        $select = new Zend_Db_Select($db);
        $cols = array(
            'post_id',
            'user_id',
            'date_created',
            'heading',
            'image',
            'description',
            'is_comment',
            'tags',
            'is_in_draft',
            'date_published');
        $select->from(array('p' => 'posts'), $cols)->where("is_in_draft = 1")->order('date_published DESC');
        //->joinLeft(array('u' => 'users'),'u.user_id = p.user_id',array('user_name','role_id'));//->where("p.page_id =?", 'c.page_id' );
        $stmt = $db->query($select);
        $results = $stmt->fetchAll();
        return $results;
    }


    // for get recent posts
    public function getRecentPosts()
    {
        $select = $this->select();
        $cols = array(
            'post_id',
            'user_id',
            'date_created',
            'heading',
            'image',
            'short_dsc' => 'left(description, 500)',
            'description',
            'tags',
            'is_comment',
            'is_in_draft',
            'date_published');
        $select->from($this,$cols)->where("is_in_draft = 0")->order('date_published DESC');
        $result = $this->fetchAll($select);
        return $result;
    }

    //this function is used for finding post from admin posts list
    public function findPost($name)
    {
        $select = $this->select();
        $cols = array(
            'post_id',
            'user_id',
            'date_created',
            'heading',
            'image',
            'short_dsc' => 'left(description, 500)',
            'description',
            'is_comment',
            'tags',
            'is_in_draft',
            'date_published');
        $select->from($this, $cols)->where("heading like ? ", "%" . $name . "%")->
            orWhere("date_published like ? ", "%" . $name . "%")->orWhere("tags like ? ",
            "%" . $name . "%");
        $result = $this->fetchAll($select);
        return $result;
    }

    // for update post
    public function updatePost($formData)
    {

        $data = array(
            "heading" => $formData['heading'],
            "image" => $formData['image'],
            "url" => $formData['url'],
            "is_in_draft" => $formData['is_in_draft'],
            "description" => $formData['description'],
            "is_comment" => $formData['is_comment'],
            "tags" => $formData['tags']);
        $where = "post_id = " . (int)$formData["post_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Post Updated Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error in update record</div>";
        }
    }

    // for update draft post
    public function updateDraftPost($formData)
    {

        $data = array(
            "heading" => $formData['heading'],
            "image" => $formData['image'],
            "url" => $formData['url'],
            "is_in_draft" => $formData['is_in_draft'],
            "description" => $formData['description'],
            "is_comment" => $formData['is_comment'],
            "tags" => $formData['tags']);
        $where = "post_id = " . (int)$formData["post_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Post Updated and Save in Draft Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error in update record</div>";
        }
    }


    // for remove post
    public function removePost($db, $id)
    {

        $rowset = $this->fetchAll();
        $rowCount = count($rowset);
        if ($rowCount < 2 || $rowCount == 1)
            return 3;

        $id = $this->delete($db->quoteInto("post_id = ?", $id));
        if ($id > 0)
        {
            return 1;
        } else
        {
            return 2;
        }
    }

}

?>
