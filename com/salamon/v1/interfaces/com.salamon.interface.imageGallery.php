<?php
/**
 * Interface Image Gallery
 * 
 * @version 1.0.1
 * @author Rafal Salamon
 * @package gallery
 *
 */
interface com_salamon_interface_imageGallery
{

	
	/**
	 * Construstor of imageGallery class
	 * 
	 * @param array $options
	 * @param MDB2 $db
	 * 
	 * @return void
	 */
	public function __construct($options = Array(), $db);
	
	/**
	 * Data
	 */
	
	/**
	 * Return array with all gallery albums data
	 * 
	 * @param integer $start
	 * @param integer $max
	 * @param string $orderby_direction
	 * 
	 * @return array
	 */
	public function dataAllAlbums ($start, $max, $orderby_direction = 'ASC');
	
	/**
	 * Return array with all gallery images data
	 * 
	 * @param unknown_type $start
	 * @param unknown_type $max
	 * @param unknown_type $filters
	 * @param unknown_type $order
	 * 
	 * @return array
	 */
	public function dataAllImages ($start, $max, $filters = Array(), $order = 'ASC');
	
	/**
	 * Return data for specific image
	 * 
	 * @return com_salamon_imageGallery_image_class
	 */
	public function dataImage ();
	
	/**
	 * Return position number on the list of images
	 * 
	 * @return array
	 */
	public function imagePositionList ();
	
	/**
	 * Return data for specific array
	 * 
	 * @return com_salamon_imageGallery_album_class
	 */
	public function dataAlbum ();
	
	
	/**
	 * Function
	 */
	
	/**
	 * Add new image to gallery
	 * 
	 * @param array $data
	 * 
	 * @return void
	 */
	public function addImage ($data);
	
	/**
	 * Add new album to gallery
	 * 
	 * @param array $data
	 * 
	 * @return void
	 */
	public function addAlbum ($data);
	
	/**
	 * Update image data
	 * 
	 * @param array $varUpdate
	 * 
	 * @return void
	 */
	public function updateImage ($varUpdate);
	
	/**
	 * Update album data
	 * 
	 * @param array $varUpdate
	 * 
	 * @return void
	 */
	public function updateAlbum ($varUpdate);
	
	/**
	 * Append image to existing album
	 * 
	 * @param array $selectedAlbums
	 * 
	 * @return void
	 */
	public function addImageToAlbum ($selectedAlbums);
	
	/**
	 * Remove image from specific album
	 * 
	 * @param attay $selectedAlbums
	 * 
	 * @return void
	 */
	public function deleteImageToAlbum ($selectedAlbums);
	
	/**
	 * Update album cover image
	 * 
	 * @param integer $cover_id
	 * 
	 * @return void
	 */
	public function updateAlbumCover ($cover_id);
	
	/**
	 * Check in selected path new image to add
	 * 
	 * @param string $file_name
	 * 
	 * @return array
	 */
	public function listImageToAdd ($file_name = '');
	
	/**
	 * Set album ID converted from album URL name
	 * 
	 * @param string $name
	 * @param string $type
	 * 
	 * @return void
	 */
	public function setAlbumIdTranslated($name, $type = 'album');
	
	/**
	 * Transform URL name to ID for albums or images
	 * 
	 * @param string $name
	 * @param string $type
	 * 
	 * @return integer
	 */
	public function transformNameToID ($name, $type = 'album');
	
	/**
	 * Seters
	 */
	
	/**
	 * Set Image ID
	 * 
	 * @param integer $imageId
	 * 
	 * @return void
	 */
	public function setImageId($imageId);
	
	/**
	 * Set Album ID
	 * 
	 * @param integer $albumId
	 * 
	 * @return void
	 */
	public function setAlbumId ($albumId);
	
	/**
	 * Set image domain
	 * 
	 * @param string $imageDomain
	 * 
	 * @return void
	 */
	public function setImageDomain ($imageDomain);
	
	/**
	 * Set path to folder with images
	 * 
	 * @param string $imagePath
	 * 
	 * @return void
	 */
	public function setImagePath ($imagePath);
	
	/**
	 * Set type of size image
	 * 
	 * @param array $typeSize
	 * 
	 * @return void
	 */
	public function setTypeSize ($typeSize);
	
	/**
	 * Set temporary image path for images not added to gallery
	 * 
	 * @param string $imageTempPath
	 * 
	 * @return void
	 */
	public function setImageTempPath ($imageTempPath);
	
	/**
	 * Set folders for each of type of image size
	 * 
	 * @param array $typeSizeFolders
	 * 
	 * @return void
	 */
	public function setTypeSizeFolders ($typeSizeFolders);
	
	/**
	 * Set relative path to image folder
	 * 
	 * @param string $imageRelTempPath
	 * 
	 * @return void
	 */
	public function setImageRelTempPath ($imageRelTempPath);
	
	/**
	 * Set Types of image (like: jpg)
	 * 
	 * @param string $imageType
	 * 
	 * @return void
	 */
	public function setImageType($imageType);
	
	/**
	 * DB connection
	 * 
	 * @param MDB2 $db
	 * 
	 * @return void
	 */
	public function setDB ($db);
	
	
	
	
	/**
	 * Geters
	 */
	
	
	
	/**
	 * Return Image ID
	 * 
	 * @return integer
	 */
	public function getImageId();
	
	/**
	 * Return Album ID
	 * 
	 * @return integer
	 */
	public function getAlbumId ();
	
	/**
	 * Return image domain
	 * 
	 * @return string
	 */
	public function getImageDomain ();
	
	/**
	 * Return path to folder with images
	 * 
	 * @return string
	 */
	public function getImagePath ();
	
	/**
	 * Return type of size image
	 * 
	 * @return array
	 */
	public function getTypeSize ();
	
	/**
	 * Return temporary image path for images not added to gallery
	 * 
	 * @return string
	 */
	public function getImageTempPath ();
	
	/**
	 * Return folders for each of type of image size
	 * 
	 * @return array
	 */
	public function getTypeSizeFolders ();
	
	/**
	 * Return relative path to image folder
	 * 
	 * @return string
	 */
	public function getImageRelTempPath ();
	
	/**
	 * Return Array of types of image (like: jpg)
	 * 
	 * @return string
	 */
	public function getImageType();
	
	/**
	 * Return DB connection
	 * 
	 * @return MDB2
	 */
	public function getDB();
	
	/**
	 * Return total number of albums
	 * 
	 * @return integer
	 */
	public function getNumberAlbums ();
	
	/**
	 * Return total number of images
	 * 
	 * @return integer
	 */
	public function getNumberImages ();
}
?>