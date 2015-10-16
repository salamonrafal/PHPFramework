<?php
/**
 * Interface Files
 * 
 * @version 1.0.1
 * @author Rafal Salamon
 * @package fileSystem
 *
 */
interface com_salamon_interface_files
{
	/**
	 * Return array of file list in directory
	 * 
	 * @param string $path
	 * @param array $filter
	 * 
	 * @return array
	 */
	public function listFiles ($path, $filter = Array());
}
?>