<?php
 /****************************************
  * Interface
  ****************************************/
	require_once 'imageGallery/album/com.salamon.interface.imageGallery.album.functions.php';

 /* ************************************** *
  * PEARS
  * ************************************** */
	require_once('MDB2.php');

 /* ************************************** *
  * Includ package common class
  * ************************************** */
	require_once 'imageGallery/com.salamon.imageGallery.album.php';

/**
 * @name Album functions
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.imageGallery.album.functions.php
 * @package gallery
 */
class com_salamon_imageGallery_album_functions extends com_salamon_imageGallery_album implements com_salamon_interface_imageGallery_album_functions
{
	
	
	/* ************************************** *
	 * Private Variables
	 * ************************************** */
	
	/**
	 * Number of images in album
	 * 
	 * @var integer
	 */
	private $_count_albums = 0;
	
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_album_functions::__construct()
	 */
	public function __construct($options, $db)
	{
		parent::__construct($options, $db);
		$this -> setCountAlbums($this -> countAlbumsQuery());
	}
	
	
	/* ************************************** *
	 * Functions
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_album_functions::addAlbum()
	 */
	public function addAlbum ($data)
	{
		$stmt = $this -> _db -> prepare('INSERT INTO photo_albums (Album_URL_Name,Album_Name,Album_Description,Album_DateCreate,Album_DatePublic,Album_Public,Album_Online) VALUES(?,?,?,?,?,?,?)', array('text','text','text','timestamp','timestamp','integer','integer'), MDB2_PREPARE_MANIP);
		return $stmt -> execute($data);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_functions::updateAlbum()
	 */
	public function updateAlbum ($data)
	{
		$stmt = $this -> _db -> prepare("UPDATE `photo_albums` SET `Album_URL_Name` = ?, `Album_Name` = ?, `Album_Description` = ?, `Album_DateCreate` = ?, `Album_DatePublic` = ?, `Album_Online` = ?, `Album_Public` = ? WHERE `Album_ID` = {$this -> getAlbumId()}", array('text','text','text','timestamp','timestamp','integer','integer'), MDB2_PREPARE_MANIP);
		return $stmt -> execute($data);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_functions::updateAlbumCover()
	 */
	public function updateAlbumCover ($cover_id)
	{
		$data = Array( $cover_id );
		$stmt = $this -> _db -> prepare("UPDATE `photo_albums` SET `Thumb_ID` = ?  WHERE `Album_ID` = {$this -> getAlbumId()}", array('integer'), MDB2_PREPARE_MANIP);
		return $stmt -> execute($data);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_functions::assignImage()
	 */
	public function assignImage($image_id)
	{
		$data = Array (
			0 => $image_id,
			1 => $this -> getAlbumId()
		);
		
		$stmt = $this -> _db -> prepare('INSERT INTO `photo_albums_images` (`Image_ID`, `Album_ID`) VALUES(?,?)', array('integer','integer'), MDB2_PREPARE_MANIP);
		return $stmt -> execute($data);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_functions::unassignImage()
	 */
	public function unassignImage($image_id)
	{
		$data = Array (
			0 => $image_id,
			1 => $this -> getAlbumId()
		);
		
		$stmt = $this -> _db -> prepare('DELETE FROM `photo_albums_images` WHERE `Image_ID` = ? AND Album_ID = ?', array('integer','integer'), MDB2_PREPARE_MANIP);
		return $stmt -> execute($data);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_functions::isImagesAlbums()
	 */
	public function isImagesAlbums ($image_id)
	{
		$is = false;
		$searchSQL = "SELECT COUNT(*) AS `isImage` FROM `photo_albums_images` WHERE `Image_ID` = {$image_id} AND `Album_ID` = {$this -> getAlbumId()}";
		
		$querySearchResults = $this -> _db -> query ($searchSQL);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		
		if ($data['isimage'] == 1) 
		{
			$is = true;
		}
		
		return $is;
	}
	
	
	/* ************************************** *
	 * Seters
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_album_functions::setCountAlbums()
	 */
	public function setCountAlbums ($count)
	{
		$this -> _count_albums = $count;
	}
	
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_album_functions::getCountAlbums()
	 */
	public function getCountAlbums()
	{
		return $this -> _count_albums;
	}
	
	
	/* ************************************** *
	 * Private Methods
	 * ************************************** */
	
	/**
	 * Get number of images in album from database
	 * 
	 * @return integer
	 */
	private function countAlbumsQuery ()
	{
		$querySearch = "SELECT COUNT(*) AS numberalbums
						FROM `photo_albums`";
		
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		
		return $data['numberalbums'];
	}
}
?>