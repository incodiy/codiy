<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Incodiy\Codiy\Controllers\Core\Controller;
use Illuminate\Http\Request;
use Incodiy\Codiy\Models\Admin\System\Group;
use Incodiy\Codiy\Models\Admin\System\User;
use Illuminate\Support\Facades\Auth;
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
	
	private $importField  = 'import_csv';
	private $delimiter    = '|';
	private $contents     = [];
	private $groupName    = [];
	private $userEmails   = [];
	private $insertRoles  = [];
	private $insertUsers  = [];
	private $passPrefix   = '@';
	private $passSuffix   = '#SF2022';
	
	public function __construct() {
		parent::__construct();
		
		$this->checkGroups();
		$this->checkUsers();
	}
	
	public function index() {
		$this->setPage('Import Accounts');
		$this->removeActionButtons(['add', 'view', 'delete', 'back']);
		
		$this->form->modelWithFile();
		
		$this->form->file($this->importField, [], 'Import .CSV File');
		$this->form->close('Submit', ['class' => 'btn btn-primary btn-slideright pull-right']);
		
		return $this->render();
	}
	
	private function getRequestFileContents(Request $request) {
		$file = $request->file($this->importField)->openFile();
		return explode(PHP_EOL, $file->fread($file->getSize()));
	}
	
	private function checkGroups() {
		$groups = new Group();
		foreach ($groups->all() as $group) {
			$groupInfo         = $group->getAttributes();
			$this->groupName[$groupInfo['id']] = $groupInfo['group_name'];
		}
	}
	
	private function addGroups($content_roles, $active = true) {
		$activeStatus = 0;
		if (true === $active) {
			$activeStatus = 1;
		} elseif (false === $active) {
			$activeStatus = 0;
		} else {
			$activeStatus = $active;
		}
		
		$newRoles = array_diff($content_roles, $this->groupName);
		if (!empty($newRoles)) {
			$groupController      = new GroupController();
			foreach ($newRoles as $newrole) {
				$camelCaseRole     = ucwords(str_replace('_', ' ', str_replace('-', ' ', $newrole)));
				$this->insertRoles = [
					'group_name' => str_replace(' ', '', $camelCaseRole),
					'group_info' => $camelCaseRole,
					'active'     => $activeStatus
				];
				
				$insertGroupRequests = new Request($this->insertRoles);
				$groupController->store($insertGroupRequests);
			}
			
			$this->checkGroups();
			
			return $this->insertRoles;
		}
	}
	
	private function checkUsers() {
		$users = new User();
		foreach ($users->all() as $user) {
			$userInfo           = $user->getAttributes();
			$this->userEmails[] = $userInfo['email'];
		}
	}
	
	private function setPassword($password) {
		return bcrypt($password);
	}
	
	private $userGroupRelated = [];
	private function addUsers($data = []) {
		$userEmails = [];
		$userData   = [];
		$userGroup  = [];
		foreach ($data as $n => $userRows) {
			foreach ($userRows as $fieldname => $value) {
				if ('email' === $fieldname) {
					$userEmails[$n]    = $value;
					$userGroup[$value] = $userRows['role'];
					unset($userRows['role']);
					
					$userData[$value]  = $userRows;
				}
			}
		}
		
		$newUsers = array_diff($userEmails, $this->userEmails);
		if (!empty($newUsers)) {
			$userController = new UserController();
			
			foreach ($newUsers as $n => $newUser) {
				foreach ($userData[$newUser] as $userField => $userValue) {
					$this->insertUsers[$n][$userField] = $userValue;
					if ('username' === $userField) {
						$password = "{$this->passPrefix}{$userValue}{$this->passSuffix}";
						$this->insertUsers[$n]['password']   = $password;//$this->setPassword($password);
						$this->insertUsers[$n]['created_by'] = Auth::id();
						$this->insertUsers[$n]['created_at'] = date('Y-m-d H:i:s');
					}
				}
			}
		//	dd($userGroup, $this->insertUsers);
			if (!empty($this->insertUsers)) {
				$groupID = array_flip($this->groupName);dump($groupID);
				foreach ($this->insertUsers as $dataUsers) {
					$requestGroup['group_id'] = $groupID[$userGroup[$dataUsers['email']]];
					$requestGroup['email']    = $dataUsers['email'];
					
					$insertUserRequests = new Request($dataUsers);
					$userController->set_data_before_post($requestGroup);
				//	$userController->group_id = $requestGroup;
					$userController->store($insertUserRequests);
				}
			}
			dd($this->insertUsers);
		}
	}
	
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
					$contentFile['roles'][$fieldvalue]    = $fieldvalue;
					$contentFile['users'][$n][$fieldname] = ucwords(str_replace('_', ' ', str_replace('-', ' ', $fieldvalue)));
				}
			}
		}
		
		// INSERT NEW ROLES
	//	$this->addGroups($contentFile['roles']);
	
		// INSERT NEW USERS
		$this->addUsers($contentFile['users']);
		
		$userRoles = [];
		$userData  = [];
		foreach ($contentFile['users'] as $n => $userData) {
			$userRoles[$userData['role']][$userData['username']] = $userData['username'];
			unset($contentFile['users'][$n]['role']);
		}
		
		// next insert user
		// next insert role for users
		
		dd($userRoles, $contentFile);
	}
}