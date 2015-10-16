<?php
 /****************************************
  * Interface
  ****************************************/
	require_once 'imageGallery/album/com.salamon.interface.imageGallery.album.class.php';
	
 /* ************************************** *
  * PEARS
  * ************************************** */
	require_once('MDB2.php');

 /* ************************************** *
  * Includ package common class
  * ************************************** */
	require_once 'imageGallery/com.salamon.imageGallery.album.php';

 /* ************************************** *
  * Libs
  * ************************************** */
	require_once 'imageGallery/image/com.salamon.imageGallery.image.class.php';
	require_once 'imageGallery/image/com.salamon.imageGallery.image.functions.php';

/**
 * @name Album Class
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.imageGallery.album.class.php
 * @package gallery
 */
class com_salamon_imageGallery_album_class extends com_salamon_imageGallery_album implements com_salamon_interface_imageGallery_album_class
{
	
	
	/* ************************************* *
	 * Private Variables
	 * ************************************* */
	
	/**
	 * Number of images in album
	 * 
	 * @var integer
	 */
	private $_countImages = 0;
	
	
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::__construct()
	 */
	public function __construct($album_id, $options, $db)
	{
		parent::__construct($options, $db);
		$this -> setAlbumId($album_id);
		$album_data = $this -> AlbumData();
		$this -> setAlbumData($album_data);
		$this -> setCountImages($this -> countImagesQuery());
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::isOnline()
	 */
	public function isOnline ()
	{
		$online = false;
		
		if ($this -> _album_data['album_online'] == 1)
			$online = true;
		
		return $online;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::isPublic()
	 */
	public function isPublic ()
	{
		$public = false;
		
		if ($this -> _album_data['album_public'] == 1)
			$public = true;
		
		return $public;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::isImagesAlbums()
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
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::albumImages()
	 */
	public function albumImages ($start=-1, $max = 0, $order = 'ASC')
	{
		$imagesID = $this -> imagesIDAlbumQuery($start, $max, $order);
		$imagesData = Array();
		
		for ($i = 0; $i < count($imagesID); $i++)
		{
			$opt['image_type'] = $this -> getImageType();
			$opt['image_rel_temp_path'] = $this -> getImageRelTempPath();
			$opt['type_size_folders'] = $this -> getTypeSizeFolders();
			$opt['image_temp_path'] = $this -> getImageTempPath();
			$opt['image_domain'] = $this -> getImageDomain();
			$opt['image_path'] = $this -> getImagePath();
			$opt['type_size'] = $this -> getTypeSize();
			
			$imagesData[$i] = new com_salamon_imageGallery_image_class($imagesID[$i][0], $opt, $this -> getDB());
		}
		
		return $imagesData;
	}
	
	
	/* ************************************** *
	 * Gets
	 * ************************************** */
	
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::getThumbsForSocial()
	 */
	public function getThumbsForSocial() 
	{
		$metatag = '<meta property="og:title" content="' . $this -> getAlbumName() . '" />';
		$metatag .= '<meta property="og:description" content="' . $this -> getAlbumDescription() . '" />';
		$metatag .= '<meta property="og:image" content="' . $this -> getCoverAlbum() . '" />';
		
		return $metatag;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::getAlbumData()
	 */
	public function getAlbumData()
	{
		return $this -> _album_data;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::getAlbumURLName()
	 */
	public function getAlbumURLName ()
	{
		return $this -> _album_data['album_url_name'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::getAlbumName()
	 */
	public function getAlbumName ()
	{
		return $this -> _album_data['album_name'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::getAlbumDescription()
	 */
	public function getAlbumDescription ()
	{
		return $this -> _album_data['album_description'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::getAlbumDateCreate()
	 */
	public function getAlbumDateCreate ()
	{
		return $this -> _album_data['album_datecreate'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::getAlbumDatePublic()
	 */
	public function getAlbumDatePublic ()
	{
		return $this -> _album_data['album_datepublic'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::getCoverAlbum()
	 */
	public function getCoverAlbum ()
	{
		$cover_path = '';
		
		if ($this -> _album_data['thumb_id'] != 0)
		{
			$imageType = $this -> getTypeSizeFolders();
			$fileNameCover = $this -> imageCoverFileName($this -> _album_data['thumb_id']);
			
			$cover_path = 'http://' . $this -> getImageDomain() . '/' . $imageType[200] . $fileNameCover;
		}
		
		return $cover_path;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::getCountImages()
	 */
	public function getCountImages()
	{
		return $this -> _countImages;
	}
	
	/* ************************************** *
	 * Sets
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::setAlbumData()
	 */
	public function setAlbumData($album_data)
	{
		$this -> _album_data = $album_data;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album_class::setCountImages()
	 */
	public function setCountImages ($countImages)
	{
		$this -> _countImages = $countImages;
	}
	
	/* ************************************** *
	 * Private Methods
	 * ************************************** */
	
	/**
	 * Retrive album data
	 * 
	 * @return array
	 */
	private function AlbumData ()
	{
		$querySearch = "SELECT `Album_URL_Name`,`Album_Name`, `Album_Description`, `Album_DateCreate`, `Album_DatePublic`, `Album_Online`, `Album_Public`, `Thumb_ID`
						FROM `photo_albums`
						WHERE `Album_ID` = {$this -> getAlbumId()}";
		
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		
		return $data;
	}
	
	/**
	 * Convert Image ID to file name
	 * 
	 * @param integer $image_id
	 * 
	 * @return string
	 */
	private function imageCoverFileName ($image_id)
	{
		$querySearch = "SELECT `Images_File_Name` 
						FROM `photo_images` 
						WHERE `Image_ID` = {$image_id}
		";
		
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		return $data['images_file_name'];
	}
	
	/**
	 * Return number of image inside specific Album
	 * 
	 * @return integer
	 */
	private function countImagesQuery ()
	{
		$querySearch = "SELECT COUNT(*) AS numberimages
						FROM `photo_albums_images` 
						WHERE `Album_ID` = {$this -> getAlbumId()}
		";
		
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		$querySearchResults -> free();
		
		return $data['numberimages'];
	}
	
	/**
	 * Return Array of images IDs for specific album
	 * 
	 * @param integer $start = -1
	 * @param integer $max = 0
	 * @param string $order 
	 */
	private function imagesIDAlbumQuery ($start = -1, $max = 0, $order)
	{
		if ($max == 0 && $start == -1)
		{
			$querySearch = "SELECT `Image_ID`
							FROM `photo_albums_images` 
							WHERE `Album_ID` = {$this -> getAlbumId()}
			";
		} else if ($start == -1 && $max != 0) {
			$querySearch = "SELECT `Image_ID`
							FROM `photo_albums_images` 
							WHERE `Album_ID` = {$this -> getAlbumId()}
							LIMIT 0, {$max}
			";	
		} else if ($start != -1 && $max != 0) {
			$querySearch = "SELECT `Image_ID`
							FROM `photo_albums_images` 
							WHERE `Album_ID` = {$this -> getAlbumId()}
							ORDER BY `Image_ID` {$order}
							LIMIT {$start}, {$max}
			";
		}
		
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchAll();
		$querySearchResults -> free();
		
		return $data;
	}
	
}
?>