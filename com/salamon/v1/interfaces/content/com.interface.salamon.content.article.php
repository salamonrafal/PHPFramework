<?php
/**
 * Interface Content Details
 * 
 * @version 1.0.1
 * @author Rafal Salamon
 * @package content
 *
 */
interface com_interface_salamon_content_article
{
	/**
	 * Public Methods
	 */
	
	public function __construct($db, $article_id = 0);
	
	public function loadArticleData();
	
	public function saveArticle($data);
	
	public function updateArticle($data, $orginal_id);
	
	public function hiddenArticle($article_id);
	
	public function showArticle($article_id);
	
	public function deleteArticle($article_id);
	
	public function isOnline ();
	
	public function checkCanComments ();
	
	// Seters
	
	public function setArticleId ($article_id);
	
	public function setArticleUrlName ($article_url_name);
	
	public function setArticleTitle ($article_title);
	
	public function setArticleMetaDescription ($article_meta_description);
	
	public function setArticleFullContent ($article_fullcontent);
	
	public function setArticleOnline ($article_online);
	
	public function setArticleDate ($article_date);
	
	public function setArticleCanComments ($article_can_comments);
	
	public function setAlbumId($album_id);
	
	public function setCategoryId($category_id);
	
	public function setDB($db);
	
	public function setKeywords($keywords);
	
	// Geters
	
	public function getKeywords();
	
	public function getArticleId ();
	
	public function getArticleUrlName ();
	
	public function getArticleTitle ();
	
	public function getArticleMetaDescription ();
	
	public function getArticleFullContent ();
	
	public function getArticleDate ();
	
	public function getArticleOnline ();
	
	public function getArticleCanComments ();
	
	public function getDB();
	
	public function getAlbumId();
	
	public function getCategoryId();
	
}
?>