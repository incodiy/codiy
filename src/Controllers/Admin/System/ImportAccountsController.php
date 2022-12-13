<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;
use Illuminate\Http\Request;
use Incodiy\Codiy\Models\Admin\System\Group;
/**
 * Created on Dec 13, 2022
 * 
 * Time Created : 10:44:25 AM
 *
 * @filesource	ImportAccountsController.php
 *
 * @author     wisnuwidi@gmail.com - 2022
 * @copyright  wisnuwidi
 * @email      wisnuwidi@gmail.com
 */
class ImportAccountsController extends Controller {
	public $data;
	public $import_field = 'import_csv';
	
	public function __construct() {
		parent::__construct();
		
		$this->checkGroups();
	}
	
	public function index() {
		$this->setPage('Import Accounts');
		$this->removeActionButtons(['add', 'view', 'delete', 'back']);
		
		$this->form->modelWithFile();
		
		$this->form->file($this->import_field, [], 'Import .CSV File');
		$this->form->close('Submit', ['class' => 'btn btn-primary btn-slideright pull-right']);
		
		return $this->render();
	}
	
	private function getRequestFileContents(Request $request) {
		$file = $request->file($this->import_field)->openFile();
		return explode(PHP_EOL, $file->fread($file->getSize()));
	}
	
	private $groupName = [];
	private function checkGroups() {
		$groups = new Group();
		foreach ($groups->all() as $group) {
			$groupInfo = $group->getAttributes();
			$this->groupName[] = $groupInfo['group_name'];
		}
	}
	
	private $insertRoles = [];
	private function addGroups($content_roles) {
		$newRoles = array_diff($content_roles, $this->groupName);
		if (!empty($newRoles)) {
			$groupController = new GroupController();
			
			foreach ($newRoles as $newrole) {
				$this->insertRoles = [
					'group_name' => $newrole,
					'group_info' => ucwords(str_replace('_', ' ', str_replace('-', ' ', $newrole))),
					'active'     => 1
				];
				
				$insertGroupRequests = new Request($this->insertRoles);
				$groupController->store($insertGroupRequests);
			}
			dump($this->insertRoles);
			return $this->insertRoles;
		}
	}
	
	private $contents = [];
	private $delimiter = '|';
	public function store(Request $request, $req = true) {
		$data     = $this->getRequestFileContents($request);
		$content  = [];
		foreach ($data as $n => $rowData) {
			if (!empty($rowData)) {
				if (0 === $n) {
					$content['head']     = explode($this->delimiter, $rowData);
				} else {
					$content['data'][$n] = explode($this->delimiter, $rowData);
				}
			}
		}
		
		$fileHeader  = $content['head'];
		$fileData    = $content['data'];
		$contentFile = [];
		foreach ($fileData as $n => $rows) {
			foreach ($rows as $i => $row) {
				$fieldname  = $fileHeader[$i];
				$fieldvalue = $row;
				
				if (diy_string_contained($fieldname, 'name') || diy_string_contained($fieldname, 'alias')) $fieldvalue = ucwords($row);
				
				$contentFile['users'][$n][$fieldname] = $fieldvalue;
				if (diy_string_contained($fieldname, 'role')) {
					$contentFile['roles'][$fieldvalue] = $fieldvalue;
				}
			}
		}
		
		// INSERT NEW ROLES
		$this->addGroups($contentFile['roles']);
		dd($this->insertRoles);
		
		$userRoles = [];
		$userData  = [];
		foreach ($contentFile['users'] as $n => $userData) {
			$userRoles[$userData['role']][$userData['username']] = $userData['username'];
			unset($contentFile['users'][$n]['role']);
		}
		dd($this->groupName, $contentFile['roles']);
		// next insert roles
		// next insert user
		// next insert role for users
		
		dd($userRoles, $contentFile);
	}
}