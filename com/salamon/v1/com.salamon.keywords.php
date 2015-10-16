<?php 
/**
 * @name Keywords
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.content.php
 * @package content
 */

// PEARS
require_once('MDB2.php');

class com_salamon_keywords
{
	private $_data = Array ();
	private $_db;
	private $_search = Array();
	
	public function __construct($db)
	{
		$this -> setDB($db);
	}
	
	public function searchKeyword($name = '', $assignedto = '', $type = '', $exclude = '')
	{
		if ($assignedto != '' && $type != '')
		{
			$data = $this -> dataKeywordsAssigned($assignedto, $type);
		} else {
			$data = $this -> dataKeywordsList($name, $exclude);
		}
		return $data;
	}
	
	public function searchElementsByKeyword($type = '', $id = 0, $returntype = 'array')
	{
		$returnData = Array();
		 
		if ($id != 0 && $id != '')
		{
			$data = $this -> dataElementByID ($type, $id);
		}
		
		if ($returntype == 'list')
		{
			$tempData = Array ();
			for($i = 0; $i < count ($data); $i++)
			{
				$tempData[$i] = $data[$i]['element_id'];
			}
			
			$returnData = implode(',', $tempData);
		} else {
			$returnData = $data;
		}
		
		return $returnData;
	}
	
	public function getKeywordIDFromName($name)
	{
		$searchSQL = 'SELECT `Keyword_ID` FROM `keywords_taxonomy` WHERE `Keyword_Name` = \'' . $name . '\'';
		$querySearchResults = $this -> _db -> query ($searchSQL);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		
		return $data['keyword_id'];
	}
	
	public function getKeywordElementsNumber()
	{
		$searchSQL = 'SELECT `kt`.`keyword_id`, `kt`.`Keyword_Name`, COUNT(`keywords_indexing_id`) AS `Number`   FROM `keywords_indexing` `ki` JOIN `keywords_taxonomy` `kt` ON `kt`.`Keyword_ID` = `ki`.`keyword_id`  GROUP BY `keyword_id` ORDER BY Number DESC';
		$querySearchResults = $this -> _db -> query ($searchSQL);
		$data = $querySearchResults -> fetchAll(MDB2_FETCHMODE_ASSOC);
		
		return $data;
	}
	
	public function check($keyword)
	{
		$is = false;
		$searchSQL = "SELECT COUNT(*) AS `is` FROM `keywords_taxonomy` WHERE `Keyword_Name` = '{$keyword}'";
		
		$querySearchResults = $this -> _db -> query ($searchSQL);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		
		if ($data['is'] == 1) 
		{
			$is = true;
		}
		
		return $is;
	}
	
	public function add($data)
	{
		$stmt = $this -> _db -> prepare('INSERT INTO keywords_taxonomy (Keyword_Name) VALUES(?)', array('text'), MDB2_PREPARE_MANIP);
		return $affectedRows = $stmt -> execute($data);
	}
	
	public function delete($id)
	{
		$data = Array();
		$data[0] = $id;
		
		$stmt = $this -> _db -> prepare('DELETE FROM keywords_taxonomy WHERE keyword_id = ?', array('integer'), MDB2_PREPARE_MANIP);
		$affectedRows = $stmt -> execute($data);
		
		$stmt = $this -> _db -> prepare('DELETE FROM keywords_indexing WHERE keyword_id = ?', array('integer'), MDB2_PREPARE_MANIP);
		$affectedRows = $stmt -> execute($data);
	}
	
	public function assign($type, $keyword_id, $id)
	{
		$data[0] = $keyword_id;
		$data[1] = $id;
		$data[2] = $type;
		
		switch($type)
		{
			case 'article':
					$stmt = $this -> _db -> prepare('INSERT INTO keywords_indexing (keyword_id,article_id,keywords_indexing_type) VALUES(?,?,?)', array('integer','integer','text'), MDB2_PREPARE_MANIP);
				break;
			
			case 'image':
					$stmt = $this -> _db -> prepare('INSERT INTO keywords_indexing (keyword_id,image_id,keywords_indexing_type) VALUES(?,?,?)', array('integer','integer','text'), MDB2_PREPARE_MANIP);
				break;
				
			case 'album':
					$stmt = $this -> _db -> prepare('INSERT INTO keywords_indexing (keyword_id,album_id,keywords_indexing_type) VALUES(?,?,?)', array('text'), MDB2_PREPARE_MANIP);
				break;
		}
		
		$affectedRows = $stmt -> execute($data);
	}
	
	public function unassign($type, $keyword_id, $id)
	{
		$data[0] = $keyword_id;
		$data[1] = $id;
		$data[2] = $type;
		
		switch($type)
		{
			case 'article':
					$stmt = $this -> _db -> prepare('DELETE FROM `keywords_indexing` WHERE `keyword_id` = ? AND `article_id` = ? AND `keywords_indexing_type` = ?', array('integer','integer','text'), MDB2_PREPARE_MANIP);
				break;
			
			case 'image':
					$stmt = $this -> _db -> prepare('DELETE FROM `keywords_indexing` WHERE `keyword_id` = ? AND `image_id` = ? AND `keywords_indexing_type` = ?', array('integer','integer','text'), MDB2_PREPARE_MANIP);
				break;
				
			case 'album':
					$stmt = $this -> _db -> prepare('DELETE FROM `keywords_indexing` WHERE `keyword_id` = ? AND `album_id` = ? AND `keywords_indexing_type` = ?', array('integer','integer','text'), MDB2_PREPARE_MANIP);
				break;
		}
		
		$affectedRows = $stmt -> execute($data);
	}
	
	public function count()
	{
		
	}
	
	public function data()
	{
		
	}
	
	public function dataAll()
	{
		return $this -> _data;
	}
	
	// Seters
	
	public function setSearchData()
	{
		
	}
	
	public function setDB ($db)
	{
		$this -> _db = $db;
	}
	
	// Geters
	
	public function getSearchData()
	{
		
	}
	
	public function getDB()
	{
		return $this -> _db;
	}
	
	
	/**
	 * Private Methods
	 */
	
	private function dataElementByID ($type, $id)
	{
		switch ($type)
		{
			case 'article':
					$field = 'article_id';
				break;
				
			case 'iamge':
					$field = 'image_id';
				break;
				
			case 'album':
					$field = 'album_id';
				break;
		}
		
		$searchSQL = 'SELECT `' . $field . '` AS `Element_ID` FROM `keywords_indexing` WHERE `keywords_indexing_type` = \'' . $type . '\' AND `keyword_id` = \'' . $id . '\'';
		$querySearchResults = $this -> _db -> query ($searchSQL);
		$data = $querySearchResults -> fetchAll(MDB2_FETCHMODE_ASSOC);
		
		return $data;
	}
	
	private function dataKeywordsAssigned($assignedto, $type)
	{
		$where = '';
		
		if ($assignedto != '')
		{
			switch ($type)
			{
				case 'article':
						$assignedto = '`ki`.`article_id` = ' . $assignedto;
					break;
					
				case 'iamge':
						$assignedto = '`ki`.`image_id` = ' . $assignedto;
					break;
					
				case 'album':
						$assignedto = '`ki`.`album_id` = ' . $assignedto;
					break;
			}
			
		}
		
		if ($type != '')
		{
			$type = ' `ki`.`keywords_indexing_type` = \'' . $type . '\'';
		}
		
		if ($assignedto != '' || $type != '')
		{
			$where = 'WHERE ';
			
			if ($assignedto != '')
			{
				$where .= ' ' . $assignedto;
			}
			
			if ($type != '')
			{
				if ($assignedto != '')
				{
					$where .= ' AND';
				}
				$where .= ' ' . $type;
			}
		}
		
		$searchSQL = 'SELECT `kt`.`keyword_id`, `kt`.`Keyword_Name` FROM `keywords_indexing` `ki` JOIN `keywords_taxonomy` `kt` ON `ki`.`Keyword_ID` = `kt`.`Keyword_ID` '. $where;
		
		$querySearchResults = $this -> _db -> query ($searchSQL);
		$data = $querySearchResults -> fetchAll(MDB2_FETCHMODE_ASSOC);
		
		return $data;
	}
	
	private function dataKeywordsList ($name, $exclude)
	{
		$where = '';
		
		if ($name != '')
		{
			$name = '`Keyword_Name` = \'' . $name . '\'';
		}
		
		if ($exclude != '')
		{
			$exclude = '`Keyword_ID` NOT IN(' . $exclude . ')';
		}
		
		if ($name != '' || $exclude != '')
		{
			$where = 'WHERE ';
			
			if ($name != '')
			{
				$where .= ' ' . $name;
			}
			
			if ($exclude != '')
			{
				if ($name != '')
				{
					$where .= ' AND';
				}
				$where .= ' ' . $exclude;
			}
		}
		
		$searchSQL = 'SELECT `Keyword_Name`, `Keyword_ID` FROM `keywords_taxonomy` '. $where;
		
		$querySearchResults = $this -> _db -> query ($searchSQL);
		$data = $querySearchResults -> fetchAll(MDB2_FETCHMODE_ASSOC);
		
		return $data;
	}
	
}
?>