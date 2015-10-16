<?php
/**
 * Interface Content
 * 
 * @version 1.0.1
 * @author Rafal Salamon
 * @package content
 *
 */
interface com_salamon_interface_content
{
	/**
	 * Constructor of content class
	 * 
	 * @param MDB2 $db
	 * 
	 * @return void
	 */
	public function __construct($db);
	
	/**
	 * Get article content
	 * 
	 * @param integer $id
	 * @param string $name
	 * 
	 * @return com_salamon_content_article
	 */
	public function getArticle($id = 0, $name = '');
	
	/**
	 * Convert url name to id
	 * 
	 * @param string $name
	 * 
	 * @return integer
	 */
	public function getIdByUrlName($name);
	
	/**
	 * Get number articles
	 * 
	 * @param string $filter
	 * 
	 * @return integer
	 */
	public function getNumberArticles ($filter = '');
	
	/**
	 * Check is article exist in database
	 * 
	 * @param string $searchBy
	 * @param string $value
	 * 
	 * @return boolean
	 */
	public function contentExists($searchBy = 'Article_ID', $value);
	
	/**
	 * Get list moths 
	 * 
	 * @param string $name
	 * 
	 * @return array
	 */
	public function getMonth($name = '');
	
	/**
	 * Refresh moths activity list
	 * 
	 * @param array $nameMonth
	 * @param array $nameMonth2
	 * 
	 * @return array
	 */
	public function refreshMonths ($nameMonth = Array ('styczeń', 'luty', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik', 'listopad', 'grudzień'), $nameMonth2 = Array ('styczen', 'luty', 'marzec', 'kwiecien', 'maj', 'czerwiec', 'lipiec', 'sierpien', 'wrzesien', 'pazdziernik', 'listopad', 'grudzien'));
	
	/**
	 * return last month activity
	 * 
	 * @return array
	 */
	public function getLastMonth();
	
	/**
	 * Return articles list
	 * 
	 * @param integer $start
	 * @param integer $max
	 * @param string $order_field
	 * @param string $order_dir
	 * @param string $filter
	 * 
	 * @return array
	 */
	public function getArticles($start = 0, $max = 10, $order_field = "date", $order_dir = "desc", $filter = '');
	
	/**
	 * Return list of articles IDs
	 * 
	 * @param array $arrData
	 * 
	 * @return string
	 */
	public function getIDsFromResults ($arrData);
	
	/**
	 * Save article to database
	 * 
	 * @param array $data
	 * 
	 * @return void
	 */
	public function saveArticle($data);
	
	/**
	 * Update article data
	 * 
	 * @param array $data
	 * @param integer $orginal_id
	 * 
	 * @return void
	 */
	public function updateArticle($data, $orginal_id);
	
	/**
	 * Delete article from database
	 * 
	 * @param integer $article_id
	 * 
	 * @return void
	 */
	public function deleteArticle($article_id);
	
	/**
	 * Change visibilty of article [hide]
	 * 
	 * @param integer $article_id
	 * 
	 * @return void
	 */
	public function hiddenArticle($article_id);
	
	/**
	 * Change visibilty of article [show]
	 * 
	 * @param integer $article_id
	 * 
	 * @return void
	 */
	public function showArticle($article_id);
	
	/**
	 * Return Article categories
	 * 
	 * @return array
	 */
	public function categories();
	
	// Seters
	
	
	/**
	 * Set DB connection component
	 * 
	 * @param MDB2 $db
	 * 
	 * @return void
	 */
	public function setDB($db);
	
	// Geters
	
	
	/**
	 * Return DB connection component
	 * 
	 * @return MDB2
	 */
	public function getDB();
}
?>