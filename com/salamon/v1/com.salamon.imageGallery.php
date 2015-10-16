<?php
 /****************************************
  * Interface
  ****************************************/
	require_once 'com.salamon.interface.imageGallery.php';

 /****************************************
  * Libs
  ****************************************/
	require_once 'imageGallery/album/com.salamon.imageGallery.album.class.php';
	require_once 'imageGallery/album/com.salamon.imageGallery.album.functions.php';
	require_once 'imageGallery/image/com.salamon.imageGallery.image.class.php';
	require_once 'imageGallery/image/com.salamon.imageGallery.image.functions.php';
	require_once 'com.salamon.files.php';

 /****************************************
  * PEARS
  ****************************************/
	require_once('MDB2.php');
	
 /****************************************
  * Extends
  ****************************************/
	require_once 'com.salamon.general.php';

/**
 * @name Image Gallery Main
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.imageGallery.php
 * @package gallery
 */
class com_salamon_imageGallery extends com_salamon_general implements com_salamon_interface_imageGallery
{
	/****************************************
	 * Private Variables
	 ****************************************/
	
	/**
	 * Function component for album
	 * 
	 * @var com_salamon_imageGallery_album_functions
	 */
	private $_obj_album;
	/**
	 * Function component for image
	 * 
	 * @var com_salamon_imageGallery_image_functions
	 */
	private $_obj_image;
	/**
	 * Album ID
	 * 
	 * @var integer
	 */
	private $_album_id;
	/**
	 * Image ID
	 * 
	 * @var integer
	 */
	private $_image_id;
	/**
	 * Array of type of image size
	 * 
	 * @var array
	 */
	private $_type_size = Array ();
	/**
	 * Path to folder with images
	 * 
	 * @var string
	 */
	private $_image_path;
	/**
	 * Image domain
	 * 
	 * @var string
	 */
	private $_image_domain;
	/**
	 * Temporary image path for images not added to gallery
	 * 
	 * @var string
	 */
	private $_image_temp_path;
	/**
	 * Array of folders for each of type of image size
	 * 
	 * @var array
	 */
	private $_type_size_folders = Array ();
	/**
	 * Relative path to image folder
	 * 
	 * @var string
	 */
	private $_image_rel_temp_path;
	/**
	 * Array of type of image (like: jpg)
	 * 
	 * @var array
	 */
	private $_image_type = Array();
	/**
	 * DB connection
	 * 
	 * @var MDB2
	 */
	private $_db;
	
	
	
	/****************************************
	 * Public Methods
	 ****************************************/
	
	
	
	/****************************************
	 * Constructor
	 ****************************************/
	
	
	/**
	 * @see com_salamon_interface_imageGallery::__construct()
	 */
	public function __construct($options = Array(), $db) 
	{
		if (isset($options['type_size']) && $options['type_size'] != '')
		{
			$this -> setTypeSize ($options['type_size']);
		}
		
		if (isset($options['image_path']) && $options['image_path'] != '')
		{
			$this -> setImagePath ($options['image_path']);
		}
		
		if (isset($options['image_domain']) && $options['image_domain'] != '')
		{
			$this -> setImageDomain ($options['image_domain']);
		}
		
		if (isset($options['image_temp_path']) && $options['image_temp_path'] != '')
		{
			$this -> setImageTempPath ($options['image_temp_path']);
		}
		
		if (isset($options['type_size_folders']) && $options['type_size_folders'] != '')
		{
			$this -> setTypeSizeFolders ($options['type_size_folders']);
		}
		
		if (isset($options['image_rel_temp_path']) && $options['image_rel_temp_path'] != '')
		{
			$this -> setImageRelTempPath ($options['image_rel_temp_path']);
		}
		
		if (isset($options['image_type']) && $options['image_type'] != '')
		{
			$this -> setImageType ($options['image_type']);
		}
		
		$this -> setDB($db);
		
		$this -> _obj_album = new com_salamon_imageGallery_album_functions ($options, $this -> getDB());
		$this -> _obj_image = new com_salamon_imageGallery_image_functions ($options, $this -> getDB());
	}
	
	
	
	/****************************************
	 * Data
	 ****************************************/
	
	
	
	/**
	 * @see com_salamon_interface_imageGallery::dataAllAlbums()
	 */
	public function dataAllAlbums ($start, $max, $orderby_direction = 'ASC')
	{
		$albumsid = $this -> qAlbumId ($start, $max, $orderby_direction);
		$tempArray = Array();
		
		for ($i = 0; $i < count($albumsid); $i++ )
		{
			$opt['image_type'] = $this -> getImageType();
			$opt['image_rel_temp_path'] = $this -> getImageRelTempPath();
			$opt['type_size_folders'] = $this -> getTypeSizeFolders();
			$opt['image_temp_path'] = $this -> getImageTempPath();
			$opt['image_domain'] = $this -> getImageDomain();
			$opt['image_path'] = $this -> getImagePath();
			$opt['type_size'] = $this -> getTypeSize();
			
			$tempArray[$i] = new com_salamon_imageGallery_album_class ($albumsid[$i][0], $opt, $this -> getDB());
		}
		
		
		return $tempArray;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::dataAllImages()
	 */
	public function dataAllImages ($start, $max, $filters = Array(), $order = 'ASC')
	{
		$imageid = $this -> qImageId ($start, $max, $filters, $order);
		$tempArray = Array();
		
		for ($i = 0; $i < count($imageid); $i++ )
		{
			$opt['image_type'] = $this -> getImageType();
			$opt['image_rel_temp_path'] = $this -> getImageRelTempPath();
			$opt['type_size_folders'] = $this -> getTypeSizeFolders();
			$opt['image_temp_path'] = $this -> getImageTempPath();
			$opt['image_domain'] = $this -> getImageDomain();
			$opt['image_path'] = $this -> getImagePath();
			$opt['type_size'] = $this -> getTypeSize();
			
			$tempArray[$i] = new com_salamon_imageGallery_image_class ($imageid[$i][0], $opt, $this -> getDB());
		}
		
		
		return $tempArray;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::dataImage()
	 */
	public function dataImage ()
	{
		$opt['image_type'] = $this -> getImageType();
		$opt['image_rel_temp_path'] = $this -> getImageRelTempPath();
		$opt['type_size_folders'] = $this -> getTypeSizeFolders();
		$opt['image_temp_path'] = $this -> getImageTempPath();
		$opt['image_domain'] = $this -> getImageDomain();
		$opt['image_path'] = $this -> getImagePath();
		$opt['type_size'] = $this -> getTypeSize();
		
		
		return new com_salamon_imageGallery_image_class ($this -> getImageId(), $opt, $this -> getDB());
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::imagePositionList()
	 */
	public function imagePositionList ()
	{
		$filters = Array();
		$imageIdList = Array();
		$data = Array ();
		
		if (is_numeric($this -> getAlbumId()) && $this -> getAlbumId() > 0)
		{
			$filters['album_id'] = $this -> getAlbumId();
		}
		
		$imageIdsData = $this -> qImageId(0, 0, $filters, 'DESC', true);
		
		for ($i = 0; $i < count($imageIdsData); $i++)
		{
			$imageIdList[$i] = $imageIdsData[$i][0];
		}
		
		$currentPos = array_keys($imageIdList, $this -> getImageId());

		if (array_key_exists(0, $currentPos)) 
		{
		 	$data['current'] = $imageIdList[$currentPos[0]];
			
			if ($currentPos[0] > 0)
			{
				$data['prev'] = $imageIdList[$currentPos[0] - 1];
			}
				
			if ($currentPos[0] < count($imageIdList)-1)
			{
				$data['next'] = $imageIdList[$currentPos[0] + 1];
			}
		}
		
		return $data;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::dataAlbum()
	 */
	public function dataAlbum ()
	{
		$opt['image_type'] = $this -> getImageType();
		$opt['image_rel_temp_path'] = $this -> getImageRelTempPath();
		$opt['type_size_folders'] = $this -> getTypeSizeFolders();
		$opt['image_temp_path'] = $this -> getImageTempPath();
		$opt['image_domain'] = $this -> getImageDomain();
		$opt['image_path'] = $this -> getImagePath();
		$opt['type_size'] = $this -> getTypeSize();
		
		
		return new com_salamon_imageGallery_album_class ($this -> getAlbumId(), $opt, $this -> getDB());
	}
	
	
	
	/****************************************
	 * Function
	 ****************************************/
	
	
	
	/**
	 * @see com_salamon_interface_imageGallery::addImage()
	 */
	public function addImage ($data)
	{
		$insert = $this -> _obj_image -> addImage($data);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::addAlbum()
	 */
	public function addAlbum ($data)
	{
		$insert = $this -> _obj_album -> addAlbum($data);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::updateImage()
	 */
	public function updateImage ($varUpdate)
	{
		$this -> _obj_image -> setImageId($this -> getImageId());
		$update = $this -> _obj_image -> updateImage ($varUpdate);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::updateAlbum()
	 */
	public function updateAlbum ($varUpdate)
	{
		$this -> _obj_album -> setAlbumId($this -> getAlbumId());
		$update = $this -> _obj_album -> updateAlbum ($varUpdate);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::addImageToAlbum()
	 */
	public function addImageToAlbum ($selectedAlbums)
	{
		for ($i = 0; $i < count($selectedAlbums); $i++)
		{
			$this -> _obj_album -> setAlbumId($selectedAlbums[$i]);
			
			if (!$this -> _obj_album -> isImagesAlbums ($this -> getImageId()))
				$this -> _obj_album -> assignImage($this -> getImageId());
		}
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::deleteImageToAlbum()
	 */
	public function deleteImageToAlbum ($selectedAlbums)
	{
		for ($i = 0; $i < count($selectedAlbums); $i++)
		{
			$this -> _obj_album -> setAlbumId($selectedAlbums[$i]);
			
			if ($this -> _obj_album -> isImagesAlbums ($this -> getImageId()))
				$this -> _obj_album -> unassignImage($this -> getImageId());
		}
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::updateAlbumCover()
	 */
	public function updateAlbumCover ($cover_id)
	{
		$this -> _obj_album -> setAlbumId($this -> getAlbumId());
		$this -> _obj_album -> updateAlbumCover($cover_id);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::listImageToAdd()
	 */
	public function listImageToAdd ($file_name = '')
	{
		$TypeSizeFolders = $this -> getTypeSizeFolders();
		$typeSize = $this -> getTypeSize();
		$file_output = Array ();
		
		$path = $this -> getImageTempPath() . $TypeSizeFolders[$typeSize['small']];
		$filesComponent = new com_salamon_files();
		$files = $filesComponent -> listFiles($path, $this -> getImageType());		
		
		if (trim($file_name) == '')
		{
			$n = 0;
			for ($i = 0; $i < count($files); $i++)
			{
				$fileExistsinDB = $this -> _obj_image -> checkImageExists($files[$i]);
				
				if (!$fileExistsinDB)
				{
					$big = $this -> getImageTempPath() . $TypeSizeFolders[$typeSize['big']] . $files[$i];
					$medium = $this -> getImageTempPath() . $TypeSizeFolders[$typeSize['medium']] . $files[$i];
					$small = $this -> getImageTempPath() . $TypeSizeFolders[$typeSize['small']] . $files[$i];
					$arrExt = explode('.', $files[$i]);
					$Ext = $arrExt[count($arrExt) - 1];
					
					$webbig = $this-> getImageDomain() . '/' . $this -> getImageRelTempPath() . $TypeSizeFolders[$typeSize['big']] . $files[$i];
					$webmedium = $this-> getImageDomain() . '/' . $this -> getImageRelTempPath() . $TypeSizeFolders[$typeSize['medium']] .  $files[$i];
					$websmall = $this-> getImageDomain() . '/' . $this -> getImageRelTempPath() . $TypeSizeFolders[$typeSize['small']] . $files[$i];
					
					
					$file_output[$n]['path']['small'] = $small;
					$file_output[$n]['path']['medium'] = $medium;
					$file_output[$n]['path']['big'] = $big;
					
					$file_output[$n]['webpath']['temp']['small'] = $websmall;
					$file_output[$n]['webpath']['temp']['medium'] = $webmedium;
					$file_output[$n]['webpath']['temp']['big'] = $webbig;
					
					
					$file_output[$n]['fileinfo']['type'] = filetype($small);
					$file_output[$n]['fileinfo']['ext'] = $Ext;
					$file_output[$n]['fileinfo']['fullname'] = $files[$i];
					$file_output[$n]['fileinfo']['name'] = str_replace('.' . $Ext,  '', $files[$i]);
					
					
					$exifData = $this -> readExifData($small);
					
					$file_output[$n]['fileinfo']['small']['size'] = filesize($small);
					$file_output[$n]['fileinfo']['small']['width'] = $exifData['COMPUTED']['Width'];
					$file_output[$n]['fileinfo']['small']['height'] = $exifData['COMPUTED']['Height'];
					
					$exifData = $this -> readExifData($medium);
					$file_output[$n]['fileinfo']['medium']['size'] = filesize($medium);
					$file_output[$n]['fileinfo']['medium']['width'] = $exifData['COMPUTED']['Width'];
					$file_output[$n]['fileinfo']['medium']['height'] = $exifData['COMPUTED']['Height'];
					
					$exifData = $this -> readExifData($big);
					$file_output[$n]['fileinfo']['big']['size'] = filesize($big);
					$file_output[$n]['fileinfo']['big']['width'] = $exifData['COMPUTED']['Width'];
					$file_output[$n]['fileinfo']['big']['height'] = $exifData['COMPUTED']['Height'];
					
					$n++;
				}
	
			}
		} else {
			$i = 0;
			$big = $this -> getImageTempPath() . $TypeSizeFolders[$typeSize['big']] . $file_name;
			$medium = $this -> getImageTempPath() . $TypeSizeFolders[$typeSize['medium']] . $file_name;
			$small = $this -> getImageTempPath() . $TypeSizeFolders[$typeSize['small']] . $file_name;
			$arrExt = explode('.', $file_name);
			$Ext = $arrExt[count($arrExt) - 1];
			
			$webbig = $this-> getImageDomain() . '/' . $this -> getImageRelTempPath() . $TypeSizeFolders[$typeSize['big']] . $file_name;
			$webmedium = $this-> getImageDomain() . '/' . $this -> getImageRelTempPath() . $TypeSizeFolders[$typeSize['medium']] .  $file_name;
			$websmall = $this-> getImageDomain() . '/' . $this -> getImageRelTempPath() . $TypeSizeFolders[$typeSize['small']] . $file_name;
			
			
			
			$file_output[$i]['path']['small'] = $small;
			$file_output[$i]['path']['medium'] = $medium;
			$file_output[$i]['path']['big'] = $big;
			
			$file_output[$i]['webpath']['temp']['small'] = $websmall;
			$file_output[$i]['webpath']['temp']['medium'] = $webmedium;
			$file_output[$i]['webpath']['temp']['big'] = $webbig;
			
			
			$file_output[$i]['fileinfo']['type'] = filetype($small);
			$file_output[$i]['fileinfo']['ext'] = $Ext;
			$file_output[$i]['fileinfo']['fullname'] = $file_name;
			$file_output[$i]['fileinfo']['name'] = str_replace('.' . $Ext,  '', $file_name);
			
			
			$exifData = $this -> readExifData($small);
			$file_output[$i]['fileinfo']['datecreate'] = date('Y-m-d H:m:s', $exifData['FileDateTime']); ;  
			$file_output[$i]['fileinfo']['small']['size'] = filesize($small);
			$file_output[$i]['fileinfo']['small']['width'] = $exifData['COMPUTED']['Width'];
			$file_output[$i]['fileinfo']['small']['height'] = $exifData['COMPUTED']['Height'];
			
			$exifData = $this -> readExifData($medium);
			$file_output[$i]['fileinfo']['medium']['size'] = filesize($medium);
			$file_output[$i]['fileinfo']['medium']['width'] = $exifData['COMPUTED']['Width'];
			$file_output[$i]['fileinfo']['medium']['height'] = $exifData['COMPUTED']['Height'];
			
			$exifData = $this -> readExifData($big);
			$file_output[$i]['fileinfo']['big']['size'] = filesize($big);
			$file_output[$i]['fileinfo']['big']['width'] = $exifData['COMPUTED']['Width'];
			$file_output[$i]['fileinfo']['big']['height'] = $exifData['COMPUTED']['Height'];
		}
		
		return $file_output;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::setAlbumIdTranslated()
	 */
	public function setAlbumIdTranslated($name, $type = 'album')
	{
		$this -> setAlbumId($this -> transformNameToID($name, $type));
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::transformNameToID()
	 */
	public function transformNameToID ($name, $type = 'album')
	{
		
		switch ($type)
		{
			case 'image':
					$querySearch = "SELECT i.`Image_ID` FROM `photo_images` i WHERE  i.`Image_URL_Name`  = '{$name}'";
					$querySearchResults = $this -> _db -> query ($querySearch);
					$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
					$querySearchResults -> free();
					
					return $data['image_id'];
				break;
			default:
					$querySearch = "SELECT al.`Album_ID` FROM `photo_albums` al WHERE  al.`Album_URL_Name`  = '{$name}'";
					$querySearchResults = $this -> _db -> query ($querySearch);
					$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
					$querySearchResults -> free();
					
					return $data['album_id'];
				break;
		}
		
	}
	
	/****************************************
	 * Seters
	 ****************************************/
	
	
	/**
	 * @see com_salamon_interface_imageGallery::setImageId()
	 */
	public function setImageId($imageId)
	{
		$this -> _image_id = $imageId;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::setAlbumId()
	 */
	public function setAlbumId ($albumId)
	{
		$this -> _album_id = $albumId;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::setImageDomain()
	 */
	public function setImageDomain ($imageDomain)
	{
		$this -> _image_domain = $imageDomain;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::setImagePath()
	 */
	public function setImagePath ($imagePath)
	{
		$this -> _image_path = $imagePath;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::setTypeSize()
	 */
	public function setTypeSize ($typeSize)
	{
		$this -> _type_size = $typeSize;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::setImageTempPath()
	 */
	public function setImageTempPath ($imageTempPath)
	{
		$this -> _image_temp_path = $imageTempPath;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::setTypeSizeFolders()
	 */
	public function setTypeSizeFolders ($typeSizeFolders)
	{
		$this -> _type_size_folders = $typeSizeFolders;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::setImageRelTempPath()
	 */
	public function setImageRelTempPath ($imageRelTempPath)
	{
		$this -> _image_rel_temp_path = $imageRelTempPath;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::setImageType()
	 */
	public function setImageType($imageType)
	{
		$this -> _image_type = $imageType;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::setDB()
	 */
	public function setDB ($db)
	{
		$this -> _db = $db;
	}
	
	
	
	/****************************************
	 * Geters
	 ****************************************/
	
	
	
	/**
	 * @see com_salamon_interface_imageGallery::getImageId()
	 */
	public function getImageId()
	{
		return $this -> _image_id;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getAlbumId()
	 */
	public function getAlbumId ()
	{
		return $this -> _album_id;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getImageDomain()
	 */
	public function getImageDomain ()
	{
		return $this -> _image_domain;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getImagePath()
	 */
	public function getImagePath ()
	{
		return $this -> _image_path;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getTypeSize()
	 */
	public function getTypeSize ()
	{
		return $this -> _type_size;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getImageTempPath()
	 */
	public function getImageTempPath ()
	{
		return $this -> _image_temp_path;	
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getTypeSizeFolders()
	 */
	public function getTypeSizeFolders ()
	{
		return $this -> _type_size_folders;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getImageRelTempPath()
	 */
	public function getImageRelTempPath ()
	{
		return $this -> _image_rel_temp_path;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getImageType()
	 */
	public function getImageType()
	{
		return $this -> _image_type;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getDB()
	 */
	public function getDB()
	{
		return $this -> _db;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getNumberAlbums()
	 */
	public function getNumberAlbums ()
	{
		return $this -> _obj_album -> getCountAlbums();
	}
	
	/**
	 * @see com_salamon_interface_imageGallery::getNumberImages()
	 */
	public function getNumberImages ()
	{
		return $this -> _obj_image -> getCountImages();
	}
	
	
	
	/****************************************
	 * Private Methods
	 ****************************************/
	
	
	
	/**
	 * Read Exif data attached to image
	 * 
	 * @param string $path
	 * 
	 * @return array
	 */
	private function readExifData ($path)
	{
		$exif_data = read_exif_data($path);
		return $exif_data;
	}
	
	/**
	 * Return array of list of albums ids
	 * 
	 * @param integer $start
	 * @param integer $max
	 * @param string $orderby_direction
	 * 
	 * @return array
	 */
	private function qAlbumId ($start, $max, $orderby_direction = "ASC")
	{
		$data = Array($start, $max);
		
		if ($max != 0 )
			$querySearch = "SELECT `Album_ID` FROM `photo_albums` ORDER BY `Album_ID` {$orderby_direction} LIMIT {$start}, {$max}";
		else
			$querySearch = "SELECT `Album_ID` FROM `photo_albums` ORDER BY `Album_ID` {$orderby_direction}";
			
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchAll();
		$querySearchResults -> free();
		
		return $data;
	}
	
	/**
	 * Return array of list of images ids 
	 * 
	 * @param integer $start
	 * @param integer $max
	 * @param array $filters
	 * @param string $order
	 * @param boolean $all
	 * 
	 * @return array
	 */
	private function qImageId ($start, $max, $filters, $order, $all = false)
	{
		$data = Array();
		$where = $this -> filterSQL($filters);
		
		if ($all)
		{
			$querySearch = "SELECT im.`Image_ID` FROM `photo_images` im " . $where . " ORDER BY im.`Image_ID` {$order}";
		} else {
			$querySearch = "SELECT im.`Image_ID` FROM `photo_images` im " . $where . " ORDER BY im.`Image_ID` {$order} LIMIT {$start}, {$max}";
		}

		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchAll();
		$querySearchResults -> free();
		
		return $data;
	}
	
	/**
	 * Create WHERE statment for SQL
	 * 
	 * @param array $filters
	 * 
	 * @return string
	 */
	private function filterSQL ($filters)
	{
		$where = '';
		
		foreach ($filters as $filter => $value)
		{
			switch ($filter)
			{
				case 'ex_album_id':
					
					if (is_array($value))
					{
						$list = implode(',', $value);
						$where .= ' im.`image_id` NOT IN (SELECT alim2.`image_id` FROM photo_albums_images alim2 WHERE alim2.`album_id` IN ('. $list .'))' ;
					} else {
						$where .= ' im.`image_id` NOT IN (SELECT alim2.`image_id` FROM photo_albums_images alim2 WHERE alim2.`album_id` = '. $value .')' ;
					}
					
					break;
					
				case 'album_id':
					
					if (is_array($value))
					{
						$list = implode(',', $value);
						$where .= ' im.`image_id` IN (SELECT alim2.`image_id` FROM photo_albums_images alim2 WHERE alim2.`album_id` IN ('. $list .'))' ;
					} else {
						$where .= ' im.`image_id` IN (SELECT alim2.`image_id` FROM photo_albums_images alim2 WHERE alim2.`album_id` = '. $value .')' ;
					}
					
					break;
			}
		}
		
		if ($where != '')
		{
			$where = 'WHERE ' . $where;
		}
		
		return  $where;
	}
}
?>