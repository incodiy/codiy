<?php
namespace Incodiy\Codiy\Controllers\Core\Craft\Includes;

/**
 * Created on 27 Mar 2021
 * Time Created	: 01:43:45
 *
 * @filesource	FileUpload.php
 *
 * @author		wisnuwidi@incodiy.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@incodiy.com
 */
 
trait FileUpload {
	
	/**
	 * Statically define input file type
	 * @var array
	 */
	protected $inputFiles		= [];
	
	/**
	 * File Attribute Collections
	 */
	protected $fileAttributes	= [];
	
	/**
	 * Set Image Validation
	 *
	 * created @Sep 8, 2018
	 * author: wisnuwidi
	 *
	 * @param boolean $filename
	 * @param boolean $size in MegaByte
	 *
	 * @return array
	 */
	private function setImageValidation($filename, $size = 1) {
		$this->fileAttributes[$filename]['file_validation'] = diy_image_validations(diy_set_filesize($size));
	}
	
	/**
	 * Set File Validations
	 * 
	 * @param string $filename
	 * @param string $type
	 * @param boolean $validation
	 * @param number $size
	 */
	private function setFileValidation($filename, $type, $validation = false, $size = 1) {
		if (!empty($size)) $max = '|max:' . diy_set_filesize($size);
		if (!empty($validation)) $this->fileAttributes[$filename]['file_validation'] = "{$type}|mimes:{$validation}{$max}";
	}
	
	/**
	 * Set File Type
	 * 
	 * @param string $filename
	 * @param string $filetype
	 */
	private function setFileType($filename, $filetype) {
		$this->fileAttributes[$filename]['file_type'] = $filetype;
	}
	
	/**
	 * Set Image Thumbnail
	 * 
	 * @param string $filename
	 * @param boolean $thumb
	 * @param array $thumb_size
	 */
	private function setImageThumb($filename, $thumb = false, $thumb_size = [100, null]) {
		$thumbName = false;
		
		if (!empty($thumb)) {
			if (true === $thumb) {
				$thumbName = "{$filename}_thumb";
			} else {
				$thumbName = $thumb;
			}
		} else {
			$thumbName    = "{$filename}_thumb";
		}
		
		if (!empty($thumbName)) {
			$this->fileAttributes[$filename]['thumb_name'] = $thumbName;
			$this->fileAttributes[$filename]['thumb_size'] = $thumb_size;
		}
	}
	
	/**
	 * Data Image Setted for Prevent Adding Thumbnail To Database
	 * @var array
	 */
	private $dropDbThumbnail = [];
	/**
	 * Prevent Inserting Image Thumbnail To The Database
	 * 
	 * @param string $file_target
	 */
	public function preventInsertDbThumbnail($file_target) {
		$this->dropDbThumbnail[$file_target] = $file_target;
	}
	
	/**
	 * Set Image Elements
	 * 
	 * To set some file elements like file type, image validations, image thumbnail
	 * Set this function in constructor function [__construct()] class
	 * 
	 * @param string $fieldname
	 * @param number $file_max_size
	 * @param boolean $file_thumb
	 * @param array $thumb_size
	 */
	public function setImageElements($fieldname, $file_max_size = 1, $file_thumb = false, $thumb_size = [100, null]) {
		$this->setFileType($fieldname, 'image');
		$this->setImageValidation($fieldname, $file_max_size);
		
		if (!empty($file_thumb)) {
			$this->setImageThumb($fieldname, $file_thumb, $thumb_size);
		}
	}
		
	/**
	 * Set File Elements
	 *
	 * To set some file elements like file type and file validations
	 * Set this function in constructor function [__construct()] class
	 *
	 * @param string $fieldname
	 * @param string $type
	 * @param boolean $validation
	 * @param integer $size
	 */
	public function setFileElements($fieldname, $type, $validation = false, $size = 1) {
		$this->setFileType($fieldname, $type);
		$this->setFileValidation($fieldname, $type, $validation, $size);
	}
	
	/**
	 * Simply Manipulate All Request Data with File before Insert/Update Process
	 *
	 * created @Jul 18, 2018
	 * author: wisnuwidi
	 *
	 * @param string $upload_path
	 * @param object $request
	 * @param string $filename
	 * @param string $validation
	 * @param array $thumbnail_size
	 * @param boolean $use_time
	 *
	 * @return array
	 */
	public function uploadFiles($upload_path, $request, $file_data = []) {
		// Upload file to asset resources folder
		$this->form->fileUpload($upload_path, $request, $file_data);
		
		if (empty($this->form->getFileUploads)) {
			$routeBack = str_replace('.', "/", str_replace('store', 'create', current_route()));
			return redirect($routeBack);
		}
		
		// Data Insert Collection
		if (is_array($file_data)) {
			foreach ($this->form->getFileUploads as $file_name => $file_data) {
				$dataExceptions[] = $file_name;
				if (!empty($file_data['thumbnail'])) {
					
					// Check if any drop filename setted
					$checkDropField = false;
					if (isset($this->dropDbThumbnail[$file_name])) {
						$checkDropField = $this->dropDbThumbnail[$file_name];
					}
					
					if ($file_name === $checkDropField) {
						// check for unset image thumbnail
						$dataFile[$file_name] = [
							$file_name => $file_data['file']
						];
					} else {
						// insert image file with thumbnail
						$dataFile[$file_name]   = [
							$file_name           => $file_data['file'],
							"{$file_name}_thumb" => $file_data['thumbnail']
						];
					}
				} else {
					$dataFile[$file_name] = [$file_name => $file_data['file']];
				}
			}
			
			$dataFiles = [];
			foreach ($dataFile as $datafile) {
				foreach ($datafile as $dataname => $datapath) {
					$dataFiles[$dataname] = $datapath;
				}
			}
			
			return array_merge_recursive($request->except($dataExceptions), $dataFiles);
		}
	}
}