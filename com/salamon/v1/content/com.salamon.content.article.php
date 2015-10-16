<?php
// Interface
require_once 'content/com.interface.salamon.content.article.php';

// PEARS
require_once('MDB2.php');

// Salamon Framework
include_once 'com.salamon.keywords.php';

/**
 * @name Content System
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.content.php
 * @package content
 */
class com_salamon_content_article implements com_interface_salamon_content_article
{
	/**
	 * Private Variables
	 */
	private $_data = Array();
	private $_db;
	private $_search = Array();
	private $_obj_keywords;
	private $_keywords  = Array();

	
	/**
	 * Public Methods
	 */
	
	public function __construct($db, $article_id = 0)
	{
		$this -> setDB($db);
		$this -> _obj_keywords = new com_salamon_keywords($this-> getDB());
		
		if ($article_id != 0)
		{
			$this -> setArticleId($article_id);
			$this -> loadArticleData();
			
			$selectedKeywords = $this -> _obj_keywords -> searchKeyword('', $article_id, 'article', '');
			$this -> setKeywords($selectedKeywords);
		}
	}
	
	public function loadArticleData()
	{
		$querySearch = "SELECT `Article_ID`, `Article_URL_Name`, `Article_Title`, `Article_Meta_Description`, `Article_FullContent`, `Article_Online`, `Article_Date`, `Article_Can_Comments`, `Category_id`, `Album_ID` FROM `articles` WHERE `Article_ID` = {$this -> getArticleId()}";
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		$this -> setArticleUrlName($data['article_url_name']);
		$this -> setArticleTitle($data['article_title']);
		$this -> setArticleMetaDescription($data['article_meta_description']);
		$this -> setArticleFullContent($data['article_fullcontent']);
		$this -> setArticleOnline($data['article_online']);
		$this -> setArticleDate($data['article_date']);
		$this -> setArticleCanComments($data['article_can_comments']);
		$this -> setCategoryId($data['category_id']);
		$this -> setAlbumId($data['album_id']);
	}
	
	public function saveArticle($data)
	{
		$data[4] = str_replace('\"', '"', $data[4]);
		$stmt = $this -> _db -> prepare('INSERT INTO articles (Article_ID,Article_URL_Name,Article_Title,Article_Meta_Description,Article_FullContent,Article_Online,Article_Can_Comments,Article_Date,Category_id,Album_ID) VALUES(?,?,?,?,?,?,?,?,?,?)', array('integer','text','text','text','text','integer','integer','timestamp','integer','integer'), false);
		return $affectedRows = $stmt -> execute($data);
	}
	
	public function updateArticle($data, $orginal_id)
	{
		$data[4] = str_replace('\"', '"', $data[4]);
		$stmt = $this -> _db -> prepare('UPDATE `articles` SET `Article_ID` = ?, `Article_URL_Name` = ?, `Article_Title` = ?, `Article_Meta_Description` = ?, `Article_FullContent` = ?, `Article_Online` = ?, `Article_Can_Comments` = ?, `Article_Date` = ?, `Category_id` = ?, `Album_ID` = ? WHERE `Article_ID` = '. $orginal_id, array('integer','text','text','text','text','integer','integer','timestamp','integer','integer'), MDB2_PREPARE_MANIP);
		return $affectedRows = $stmt -> execute($data);
	}
	
	public function hiddenArticle($article_id)
	{
		$stmt = $this -> _db -> prepare('UPDATE `articles` SET `Article_Online` = 0 WHERE `Article_ID` = ' . $article_id, array(), MDB2_PREPARE_MANIP);
		return $affectedRows = $stmt -> execute(Array());
	}
	
	public function showArticle($article_id)
	{
		$stmt = $this -> _db -> prepare('UPDATE `articles` SET `Article_Online` = 1 WHERE `Article_ID` = ' . $article_id, array(), MDB2_PREPARE_MANIP);
		return $affectedRows = $stmt -> execute(Array());
	}
	
	public function deleteArticle($article_id)
	{
		$data = Array (
			0 => $article_id
		);
		
		$stmt = $this -> _db -> prepare('DELETE FROM `articles` WHERE `Article_ID` = ?', array('integer'), MDB2_PREPARE_MANIP);
		return $stmt -> execute($data);
	}
	
	public function isOnline ()
	{
		$article_online = $this -> getArticleOnline();
		$is_online = false;
		
		if ($article_online == 1)
			$is_online = true;
			
		return $is_online;
	}
	
	public function checkCanComments ()
	{
		$article_online = $this -> getArticleCanComments();
		$can_comments = false;
		
		if ($article_online == 1)
			$can_comments = true;
			
		return $can_comments;
	}
	
	// Seters
	
	public function setArticleId ($article_id)
	{
		$this -> _data['article_id'] = $article_id;
	}
	
	public function setArticleUrlName ($article_url_name)
	{
		$this -> _data['article_url_name'] = $article_url_name;
	}
	
	public function setArticleTitle ($article_title)
	{
		$this -> _data['article_title'] = $article_title;
	}
	
	public function setArticleMetaDescription ($article_meta_description)
	{
		$this -> _data['article_meta_description'] = $article_meta_description;
	}
	
	public function setArticleFullContent ($article_fullcontent)
	{
		$this -> _data['article_fullcontent'] = $article_fullcontent;
	}
	
	public function setArticleOnline ($article_online)
	{
		$this -> _data['article_online'] = $article_online;
	}
	
	public function setArticleDate ($article_date)
	{
		$this -> _data['article_date'] = $article_date;
	}
	
	public function setArticleCanComments ($article_can_comments)
	{
		$this -> _data['article_can_comments'] = $article_can_comments;
	}
	
	public function setAlbumId($album_id)
	{
		$this -> _data['album_id'] = $album_id;
	}
	
	public function setCategoryId($category_id)
	{
		$this -> _data['category_id'] = $category_id;
	}
	
	public function setDB($db)
	{
		$this -> _db = $db;
	}
	
	public function setKeywords($keywords)
	{
		$this -> _keywords = $keywords;
	}
	
	// Geters
	
	public function getKeywords()
	{
		return $this -> _keywords;
	}
	
	public function getArticleId ()
	{
		return $this -> _data['article_id'];
	}
	
	public function getArticleUrlName ()
	{
		return $this -> _data['article_url_name'];
	}
	
	public function getArticleTitle ()
	{
		return $this -> _data['article_title'];
	}
	
	public function getArticleMetaDescription ()
	{
		return $this -> _data['article_meta_description'];
	}
	
	public function getArticleFullContent ()
	{
		return $this -> _data['article_fullcontent'];
	}
	
	public function getArticleDate ()
	{
		return $this -> _data['article_date'];
	}
	
	public function getArticleOnline ()
	{
		return $this -> _data['article_online'];
	}
	
	public function getArticleCanComments ()
	{
		return $this -> _data['article_can_comments'];
	}
	
	public function getDB()
	{
		return $this -> _db;
	}
	
	public function getAlbumId()
	{
		return $this -> _data['album_id'];
	}
	
	public function getCategoryId()
	{
		return $this -> _data['category_id'];
	}
	
	/**
	 * Private Methods
	 */
	private function dump ($var)
	{
		print('<pre>');
			var_dump($var);
		print('</pre>');
	}
}
?>