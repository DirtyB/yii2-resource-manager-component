<?php
/**
 * @link http://2amigos.us
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\resourcemanager;

use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\base\Component;
use Yii;

/**
 *
 * FileSystemResourceManager handles resource to upload/uploaded to a server folder.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 */
class FileSystemResourceManager extends Component implements ResourceManagerInterface
{

	/**
	 * @var string the upload directory path or its alias
	 */
	public $basePath = '@webroot/uploads';
	/**
	 * @var string the upload directory url or its alias
	 */
	public $baseUrl = '@web/uploads';

	/**
	 * Saves an UploadedFile instance
	 * @param \yii\web\UploadedFile $file the file uploaded
	 * @param string $name the name of the file. If empty, it will be set to the name of the uploaded file
	 * @param array $options to save the file. The options can be any of the following:
	 *  - `folder` : whether we should create a subfolder where to save the file
	 *  - `override` : whether we allow rewriting a existing file
	 * @return boolean
	 */
	public function save($file, $name, $options = [])
	{
		if(empty($name)){
			$name = $file->name;
		}
		$savePath = $this->getFullPath($name,$options);
		@mkdir(dirname($savePath), 0777, true);

		return $file->saveAs($savePath);
	}

	/**
	 * Copies file to storage
	 * @param string $path path to file
	 * @param string $name the name of the file. If empty, it will be set to the name of the specified file
	 * @param array $options to save the file. The options can be any of the following:
	 *  - `folder` : whether we should create a subfolder where to save the file
	 *  - `override` : whether we allow rewriting a existing file
	 * @return boolean
	 */
	public function saveFile($path, $name, $options = [])
	{
		if(empty($name)){
			$name = basename($path);
		}
		$savePath = $this->getFullPath($name,$options);
		@mkdir(dirname($savePath), 0777, true);

		return copy($path, $savePath);
	}

	/**
	 * Saves data to a file
	 * @param string $body contents of file
	 * @param string $name the name of the file. If empty, it will be set to the name of the specified file
	 * @param array $options to save the file. The options can be any of the following:
	 *  - `folder` : whether we should create a subfolder where to save the file
	 *  - `override` : whether we allow rewriting a existing file
	 * @return boolean
	 */
	public function saveContents($body, $name, $options = [])
	{
		$savePath = $this->getFullPath($name,$options);
		@mkdir(dirname($savePath), 0777, true);

		return (file_put_contents($savePath,$body) !== false);
	}

	/**
	 * Removes a file
	 * @param string $name the name of the file to remove
	 * @return boolean
	 */
	public function delete($name)
	{
		return $this->fileExists($name) ? @unlink($this->getBasePath() . DIRECTORY_SEPARATOR . $name) : false;
	}

	/**
	 * Checks whether a file exists or not
	 * @param string $name the name of the file
	 * @return boolean
	 */
	public function fileExists($name)
	{
		return file_exists($this->getBasePath() . DIRECTORY_SEPARATOR . $name);
	}

	/**
	 * Returns the url of the file or empty string if the file doesn't exist.
	 * @param string $name the name of the file
	 * @return string
	 */
	public function getUrl($name)
	{
		return $this->getBaseUrl() . '/' . $name;
	}

	/**
	 * get contents a file
	 * @param string $name the name of the file
	 * @param array $options
	 * @return string|null body of file
	 */
	public function getFileContents($name, $options = []){
		$path = $this->getFullPath($name,$options);

		try {
			$contents = file_get_contents($path);
		}
		catch(ErrorException $e){
			Yii::info($e->getMessage());
			return null;
		}

		return ($contents !== false) ? $contents : null;
	}

	/**
	 * Returns the upload directory path
	 * @return string
	 */
	public function getBasePath()
	{
		return Yii::getAlias($this->basePath);
	}

	/**
	 * Sets the upload directory path
	 * @param $value
	 */
	public function setBasePath($value)
	{
		$this->basePath = rtrim($value, DIRECTORY_SEPARATOR);
	}

	/**
	 * Returns the base url
	 * @return string the url pointing to the directory where we saved the files
	 */
	public function getBaseUrl()
	{
		return Yii::getAlias($this->baseUrl);
	}

	/**
	 * Sets the base url
	 * @param string $value the url pointing to the directory where to get the files
	 */
	public function setBaseUrl($value)
	{
		$this->baseUrl = rtrim($value, '/');
	}

	/**
	 * Prepares directory for specified filename
	 * @param string $name
	 * @param array $options
	 * @return bool|string canonized path or false on fail
	 */
	protected function getFullPath($name,$options){
		$name = ltrim($name, DIRECTORY_SEPARATOR);

		if ($folder = trim(ArrayHelper::getValue($options, 'folder'), DIRECTORY_SEPARATOR)) {
			$name = $folder . DIRECTORY_SEPARATOR . $name;
		}

		if (!ArrayHelper::getValue($options, 'override', true) && $this->fileExists($name)) {
			return false;
		}

		$path = $this->getBasePath() . DIRECTORY_SEPARATOR . $name;
		$path = str_replace('/',DIRECTORY_SEPARATOR,$path);

		return $path;
	}

}
