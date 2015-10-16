<?php
/**
 * @name Content System
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.content.php
 * @package files
 */

//Interface
require_once 'com.salamon.interface.content.php';

// PEARS
require_once('MDB2.php');

// Libs
require_once 'content/com.salamon.content.article.php';

class com_salamon_content implements com_salamon_interface_content
{
	/**
	 * Private Variables
	 */
	/**
	 * DB Connector class
	 * @var MDB2
	 */
	private $_db;
	/**
	 * Search filters
	 * 
	 * @var array
	 */
	private $_search = Array();
	/**
	 * Article object
	 * 
	 * @var com_salamon_content_article
	 */
	private $_obj_article;

	
	/**
	 * Public Methods
	 */
	
	/**
	 * @see com_salamon_interface_content::__construct()
	 */
	public function __construct($db)
	{
		$this -> setDB($db);
		$this -> _obj_article = new com_salamon_content_article($this -> _db);
	}
	
	/**
	 * @see com_salamon_interface_content::getArticle()
	 */
	public function getArticle($id = 0, $name = '')
	{
		if ($name != '')
		{
			$id = $this -> getIdByUrlName($name);
		}
		
		return new com_salamon_content_article($this-> _db, $id);
	}
	
	/**
	 * @see com_salamon_interface_content::getIdByUrlName()
	 */
	public function getIdByUrlName($name)
	{
		$querySearch = "SELECT `Article_ID` FROM `articles` WHERE `Article_URL_Name` = '{$name}'";
		
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		return $data['article_id'];
	}
	
	/**
	 * @see com_salamon_interface_content::getNumberArticles()
	 */
	public function getNumberArticles ($filter = '')
	{
		if ($filter != '')
		{
			$filter = ' WHERE ' . $filter;
		}
		
		$querySearch = "SELECT COUNT(*) AS numbers
						FROM `articles` {$filter}";
		
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		return $data['numbers'];
	}
	
	/**
	 * @see com_salamon_interface_content::contentExists()
	 */
	public function contentExists($searchBy = 'Article_ID', $value) 
	{
		$exists = false;
			
		$querySearch = "SELECT COUNT(*) AS numbers
						FROM `articles` WHERE `{$searchBy}` = '{$value}'";
		
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		if ($data['numbers'] > 0)
		{
			$exists = true;
		}
		
		return $exists;
	}
	
	/**
	 * @see com_salamon_interface_content::getMonth()
	 */
	public function getMonth($name = '')
	{
		$where = '';
		if ($name != '')
		{
			$where = " WHERE `Months_Url_Name` = '{$name}'";
		}
		$querySearch = "SELECT `Months_id`, `Months_name`, `Months_count`, `Months_year`, `Months_Date_Start`, `Months_Date_End`, `Months_Number`, `Months_Url_Name` FROM `article_months` {$where} ORDER BY `Months_id` DESC";
		$querySearchResults = $this -> _db -> query ($querySearch);
		
		if ($name != '')
		{
			$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		} else{
			$data = $querySearchResults -> fetchAll(MDB2_FETCHMODE_ASSOC);
		}
		
		$querySearchResults -> free();
		
		return $data;
	}
	
	/**
	 * @see com_salamon_interface_content::refreshMonths()
	 */
	public function refreshMonths ($nameMonth = Array ('styczeń', 'luty', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik', 'listopad', 'grudzień'), $nameMonth2 = Array ('styczen', 'luty', 'marzec', 'kwiecien', 'maj', 'czerwiec', 'lipiec', 'sierpien', 'wrzesien', 'pazdziernik', 'listopad', 'grudzien'))
	{
		$querySearch = "(SELECT  '' AS `firstdate`, `Article_Date` AS `lastdate`  FROM `articles` ORDER BY `Article_Date` DESC LIMIT 0, 1) UNION (SELECT `Article_Date` AS `firstdate`, '' AS `lastdate`  FROM `articles` ORDER BY `Article_Date` ASC LIMIT 0, 1)";
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchAll(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		$firstdateObj = '';
		$lastdateObj = '';
		$firstdatStr = '';
		$lastdateStr = '';
		$numbermonths = 0;
		$dateData = Array();
		
		
		for ($i = 0; $i < count($data); $i++)
		{
			if ($data[$i]['firstdate'] != '')
			{
				
				$pagetime = strtotime($data[$i]['firstdate']);
				$date = date("j",$pagetime);
				$month = date("n",$pagetime);
				$year = date("Y",$pagetime);
				
				$firstdate = new DateTime();
				$firstdate -> setDate($year, $month, 01);
				$firstdatStr = $data[$i]['firstdate'];
			}
			
			if ($data[$i]['lastdate'] != '')
			{
				$pagetime = strtotime($data[$i]['lastdate']);
				$date = date("j",$pagetime);
				$month = date("n",$pagetime);
				$year = date("Y",$pagetime);
				
				$lastdate = new DateTime();
				$lastdate -> setDate($year, $month, $date);
				$lastdateStr = $data[$i]['lastdate'];
			}
		}
		$firstDay = $firstdate -> format('Y-m-01 00:00:00');
		$numbermonths = $this -> date_diff_array($firstdate, $lastdate);
		
		for ($i=1; $i <= $numbermonths['month']+1; $i++)
		{
			$pagetime = strtotime($firstDay);
			$date = date("j",$pagetime);
			$month = date("n",$pagetime);
			$year = date("Y",$pagetime);
			$thisDate = new DateTime();
			$thisDate -> setDate($year, $month, $date);
			
			$thisDateCounted = new DateTime();
			$thisDateCounted -> setDate($year, $month, $date);
			$thisDateCounted -> modify('+' . ($i-1) . ' month');
			$thisMonthNumber = $thisDateCounted -> format('n');
			$thisYearNumber = $thisDateCounted -> format('Y');
			$thisDateNumber = $thisDateCounted -> format('j');
			
			$dateData[$i]['month'] = $thisMonthNumber;
			$dateData[$i]['date'] = $thisDateCounted -> format('Y-m-d 00:00:00');
			$dateData[$i]['name'] = $nameMonth[$thisMonthNumber - 1];
			
			$dateData[$i]['lastday'] = $thisDate -> format('t');
			$dateData[$i]['dateperiod'] = $thisDateCounted -> format('Y-m-t 23:59:59');
			$dateData[$i]['year'] = $thisYearNumber;
			$dateData[$i]['name2'] = $nameMonth2[$thisMonthNumber - 1] . '_' . $dateData[$i]['year'];
			$dateData[$i]['count'] = $this -> countArticlesPeriod($dateData[$i]['date'], $dateData[$i]['dateperiod']);
			
			if (!$this -> monthExists($dateData[$i]['month'], $dateData[$i]['year']))
			{
				$dataSave = Array();
			
				$dataSave[0] = $dateData[$i]['name'];
				$dataSave[1] = $dateData[$i]['count'];
				$dataSave[2] = $dateData[$i]['year'];
				$dataSave[3] = $dateData[$i]['date'];
				$dataSave[4] = $dateData[$i]['dateperiod'];
				$dataSave[5] = $dateData[$i]['month'];
				$dataSave[6] = $dateData[$i]['name2'];
				$this -> saveMonth($dataSave);
				
				$dateData[$i]['action'] = 'inserted';
			} else {
				
				if ($this -> checkCountMonth($dateData[$i]['month'], $dateData[$i]['year']) != $dateData[$i]['count'])
				{
					$dataSave = Array();
					$dataSave[0] = $dateData[$i]['count'];
					$dataSave[1] = $dateData[$i]['year'];
					$dataSave[2] = $dateData[$i]['month'];
					$this -> updateMonth($dataSave);
					
					$dateData[$i]['action'] = 'updated';
				} else {
					$dateData[$i]['action'] = 'no';
				}
				
			}
		}
		
		return $dateData;
	}
	
	/**
	 * @see com_salamon_interface_content::getLastMonth()
	 */
	public function getLastMonth()
	{
		$querySearch = "SELECT `Months_id`, `Months_name`, `Months_count`, `Months_year`, `Months_Date_Start`, `Months_Date_End`, `Months_Number`, `Months_Url_Name` FROM `article_months` WHERE `Months_count` > 0 ORDER BY `Months_id` DESC LIMIT 0, 1";
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		return $data;
	}
	
	/**
	 * @see com_salamon_interface_content::getArticles()
	 */
	public function getArticles($start = 0, $max = 10, $order_field = "date", $order_dir = "desc", $filter = '')
	{
		$data = $this -> dataArticles($start, $max, $order_field, $order_dir, $filter);
		$returnData = Array();
		
		for ($i = 0; $i < count($data); $i++)
		{
			$returnData[$i] = new com_salamon_content_article($this-> _db, $data[$i]['article_id']);
		}
		
		
		return $returnData;
	}
	
	/**
	 * @see com_salamon_interface_content::getIDsFromResults()
	 */
	public function getIDsFromResults ($arrData)
	{
		// article_id
		$article_id = Array();
		$article_id_list = '';
		
		for ($i = 0; $i < count($arrData); $i++)
		{
			$article_id[] = $arrData[$i] -> getArticleId ();
		}
		
		$article_id_list = implode(',', $article_id);
		
		return $article_id_list;
	}
	
	/**
	 * @see com_salamon_interface_content::saveArticle()
	 */
	public function saveArticle($data)
	{
		$this -> _obj_article -> saveArticle($data);
	}
	
	/**
	 * @see com_salamon_interface_content::updateArticle()
	 */
	public function updateArticle($data, $orginal_id)
	{
		$this -> _obj_article -> updateArticle($data, $orginal_id);
	}
	
	/**
	 * @see com_salamon_interface_content::deleteArticle()
	 */
	public function deleteArticle($article_id)
	{
		$this -> _obj_article -> deleteArticle($article_id);
	}
	
	/**
	 * @see com_salamon_interface_content::hiddenArticle()
	 */
	public function hiddenArticle($article_id)
	{
		$this -> _obj_article -> hiddenArticle($article_id);
	}
	
	/**
	 * @see com_salamon_interface_content::showArticle()
	 */
	public function showArticle($article_id)
	{
		$this -> _obj_article -> showArticle($article_id);
	}
	
	/**
	 * @see com_salamon_interface_content::categories()
	 */
	public function categories()
	{
		$querySearch = "SELECT `Category_id`, `Category_name`, `Category_hidden` FROM `article_categories`";
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchAll(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		return $data;
	}
	
	// Seters
	
	
	/**
	 * @see com_salamon_interface_content::setDB()
	 */
	public function setDB($db)
	{
		$this -> _db = $db;
	}
	
	// Geters
	
	
	/**
	 * @see com_salamon_interface_content::getDB()
	 */
	public function getDB()
	{
		return $this -> _db;
	}
	
	/**
	 * Private Methods
	 */
	
	/**
	 * Count intervals betweean two dates
	 *  
	 * @param DateTime $oDate1
	 * @param DateTime $oDate2
	 * 
	 * @return integer
	 */
	private function date_diff_array(DateTime $oDate1, DateTime $oDate2) { 
	    $aIntervals = array( 
	        'month'  => 0, 
	    ); 
	 
	    foreach($aIntervals as $sInterval => &$iInterval) { 
	        while($oDate1 <= $oDate2){  
	            $oDate1->modify('+1 ' . $sInterval); 
	            if ($oDate1 > $oDate2) { 
	                $oDate1->modify('-1 ' . $sInterval); 
	                break; 
	            } else { 
	                $iInterval++; 
	            } 
	        } 
	    } 
	 
	    return $aIntervals; 
	}
	
	/**
	 * Save month to DB
	 * 
	 * @param array $data
	 * 
	 * @return boolean
	 */
	private function saveMonth($data)
	{
		$stmt = $this -> _db -> prepare('INSERT INTO `article_months` (Months_name,Months_count,Months_year,Months_Date_Start,Months_Date_End,Months_Number,Months_Url_Name) VALUES(?,?,?,?,?,?,?)', array('text','integer','integer','timestamp','timestamp','integer','text'), MDB2_PREPARE_MANIP);
		return $affectedRows = $stmt -> execute($data);
	}
	
	/**
	 * Update month data
	 * 
	 * @param array $data
	 * 
	 * @return boolean
	 */
	private function updateMonth($data)
	{
		$stmt = $this -> _db -> prepare("UPDATE `article_months` SET `Months_count` = ? WHERE `Months_year` = ? AND `Months_Number` = ?", array('integer', 'integer', 'integer'), MDB2_PREPARE_MANIP);
		return $affectedRows = $stmt -> execute($data);
	}
	
	/**
	 * Counts articles in specific period
	 * 
	 * @param string $datestart
	 * @param string $dateend
	 * 
	 * @return integer
	 */
	private function countArticlesPeriod($datestart, $dateend)
	{
		$querySearch = "SELECT COUNT(*) AS Numbers FROM `articles` WHERE (`Article_Date` BETWEEN '{$datestart}' AND '{$dateend}') AND `Article_Online` = 1";
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		return $data['numbers'];
	}
	
	/**
	 * Check if month exists
	 * 
	 * @param integer $month
	 * @param integer $year
	 */
	private function monthExists($month, $year)
	{
		$exists = false;
		
		$querySearch = "SELECT COUNT(*) AS Numbers FROM `article_months` WHERE `Months_year` = {$year} AND `Months_Number` = {$month}";
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		if ($data['numbers'] > 0)
		{
			$exists = true;
		}
		
		return $exists;
	}
	
	/**
	 * Checking months count
	 * 
	 * @param integer $month
	 * @param integer $year
	 */
	private function checkCountMonth($month, $year)
	{
		$querySearch = "SELECT `Months_count` FROM `article_months` WHERE `Months_year` = {$year} AND `Months_Number` = {$month}";
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		return $data['months_count'];
	}
	
	/**
	 * Return list of articles
	 * 
	 * @param integer $start
	 * @param integer $max
	 * @param string $order_field
	 * @param string $order_dir
	 * @param string $filter
	 */
	private function dataArticles ($start = 0, $max = 10, $order_field = "date", $order_dir = "desc", $filter = '')
	{
		
		$order_by = " ORDER BY";
		
		if ($filter != '')
		{
			$filter = ' WHERE ' . $filter;
		}
		
		switch ($order_field)
		{
			case 'id':
				$order_by = $order_by . " Article_ID";
			break;
			
			case 'url':
				$order_by = $order_by . " Article_URL_Name";
			break;
			
			case 'date':
			default:
				$order_by = $order_by . " Article_Date";
			break;
		}
		
		switch ($order_dir)
		{
			case 'asc':
				$order_by = $order_by . " ASC";
			break;
			
			case 'desc':
			default:
				$order_by = $order_by . " DESC";
			break;
		}
		
		if ($max != 0) 
		{
			$limit = "LIMIT {$start}, {$max}";
		} else {
			$limit = "";
		}
		
		
		$querySearch = "SELECT `article_id` FROM articles {$filter} {$order_by} {$limit}";
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchAll(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		return $data;
	}
}
?>