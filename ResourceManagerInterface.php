<?php
/**
 * @link http://2amigos.us
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\resourcemanager;
 /**
 * 
 * Interface ResourceManagerInterface defines a set of methods to be implemented by a [[ResourceManager]]
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 */
interface ResourceManagerInterface
{
	/**
	 * Saves an UploadedFile instance
	 * @param \yii\web\UploadedFile $file the file uploaded
	 * @param string $name the name of the file
	 * @param array $options
	 * @return boolean
	 */
	public function save($file, $name, $options = []);

	/**
	 * Copies file to storage
	 * @param string $path path to file
	 * @param string $name the name of the file
	 * @param array $options
	 * @return boolean
	 */
	public function saveFile($path, $name, $options = []);

	/**
	 * Saves data to a file
	 * @param string $body contents of file
	 * @param string $name the name of the file
	 * @param array $options
	 * @return boolean
	 */
	public function saveContents($body, $name, $options = []);

	/**
	 * Removes a file
	 * @param string $name the name of the file to remove
	 * @return boolean
	 */
	public function delete($name);

	/**
	 * Checks whether a file exists or not
	 * @param string $name the name of the file
	 * @return boolean
	 */
	public function fileExists($name);

	/**
	 * Returns the url of the file or empty string if the file doesn't exist.
	 * @param string $name the name of the file
	 * @return string
	 */
	public function getUrl($name);

	/**
	 * get contents a file
	 * @param string $name the name of the file
	 * @param array $options
	 * @return string|null body of file
	 */
	public function getFileContents($name, $options = []);

}