<?php
// Interface
require_once 'com.salamon.interface.files.php';

/**
 * @name File System
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.files.php
 * @package fileSystem
 */
class com_salamon_files implements com_salamon_interface_files
{
	/**
	 * Private Variables
	 */

	
	/**
	 * Public Methods
	 */
	
	/**
	 * @see com_salamon_interface_files::listFiles()
	 */
	public function listFiles ($path, $filter = Array())
	{
		$files = scandir($path);
		
		
		for ($i = 0; $i < count($files); $i++)
		{
			if (filetype($path. '/'. $files[$i]) == 'file'){
				$arrExt = explode('.', $files[$i]);
				$Ext = $arrExt[count($arrExt) - 1];
				
				if ($this -> checkIsExtFile($filter, $Ext))
				{
					$files_output[] = $files[$i];
				}
			}
		}
		
		return $files_output;
	}
	
	
	/**
	 * Private Methods
	 */
	
	/**
	 * Check file extension
	 * 
	 * @param array $filter
	 * @param string $ext
	 * 
	 * @return boolean
	 */
	private function checkIsExtFile ($filter, $ext)
	{
		$out = false;
		
		for ($i = 0; $i < count($filter); $i++)
		{
			if ($filter[$i] == $ext)
				$out = true;
		}
		
		return $out;
	}
}
?>