<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Illuminate\Http\Request;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Models\Admin\System\Group;
use Incodiy\Codiy\Controllers\Admin\System\Includes\Privileges;

/**
 * Created on Jan 19, 2018
 * Time Created	: 7:25:45 PM
 * Filename		: GroupController.php
 *
 * @filesource	GroupController.php
 *
 * @author		wisnuwidi@Expresscode - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
class GroupController extends Controller {
	use Privileges;
	
	public $data;
	
	private $id           = false;
	private $_set_tab     = [];
	private $_tab_config  = [];
	private $_hide_fields = ['id'];
	private $validations  = ['group_name' => 'required', 'group_info' => 'required', 'active' => 'required'];
	private $filterPage   = ['group_name' => 'admin'];
	
	public function __construct() {
		parent::__construct(Group::class, 'system.config');
	}
	
	private function set_data_before_insert($request, $model_id = false) {
		if (false === $model_id) {
			$getGroup = diy_query($this->model_table)
				->where('group_name', $request->group_name)
				->where('group_info', $request->group_info)
				->first();
		} else {
			$getGroup = diy_query($this->model_table)->where('id', $model_id)->first();
		}
		
		$this->privileges_before_insert($request, $getGroup);
	}
	
	private function set_data_after_insert($data) {
		$this->privileges_after_insert($data);
	}
	
	/**
	 * Render List(s) Datatables For Group Data
	 * 
	 * created @Sep 11, 2018
	 * author: wisnuwidi
	 * 
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function index() {
		$this->setPage();
		
		$this->table->mergeColumns('Group', ['group_name', 'group_info']);
		
		$this->table->searchable(['group_name', 'group_info']);
		$this->table->clickable();
		$this->table->sortable();
		$this->table->destroyButton(['view', 'edit']);
	//	$this->table->lists($this->model_table, ['group_name', 'group_info', 'active']);
		$this->table->lists($this->model_table, ['group_name', 'group_info', 'active'], ['view', 'edit', 'delete', 'manage|lilac|gears']);
	//	$this->table->lists($this->model_table, ['group_name', 'group_info', 'active'], ['manage|lilac|gears']);
	//	$this->table->lists($this->model_table, ['group_name', 'group_info', 'active'], 'manage|lilac|gears');
		
		return $this->render();
	}
	
	/**
	 * Create Group Data
	 * 
	 * created @Sep 11, 2017
	 * author: wisnuwidi
	 * 
	 * @tutorial: Description Logic as Internal ROOT
	 * 		: This form will rendering all fields in group table including [ $this->platform_key ].
	 * @tutorial: Description Logic as External Administrator ( users )
	 * 		: This form will rendering all fields in group table except [ $this->platform_key ].
	 * 		: [ $this->platform_key ] data field would be posted into the database using [ $this->platform_key ] value saved in the user data sessions.
	 * 
	 * @return array|\Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function create() {
		$this->setPage();
		$this->get_menu();
		
		$this->form->model();
		$this->form->text('group_name', null, ['required']);
		$this->form->text('group_info', null, ['required']);
		$this->form->selectbox('active', active_box(), false, ['required']);
		
		$this->form->openTab('Module Privileges');
		$this->form->draw($this->group_privilege());
		$this->form->closeTab();
		
		$this->form->close('Save Group');
		
		return $this->render();
	}
	
	/**
	 * Validate Group 
	 * 
	 * created @Sep 11, 2018
	 * author: wisnuwidi
	 * 
	 * @tutorial
	 * 		:	This validation will count data group, filtered by [ $this->platform_key ] and group_name posted from data requests
	 * 			used for detect duplicate group name in every [ $this->platform_key ]
	 * 
	 * @param object $request
	 * 
	 * @return number
	 */
	private function validation_groups($request) {
		$dataReq	= $request->all();
		if (true === is_multiplatform()) {
			$objects	= diy_query($this->model_table)->where($this->platform_key, $dataReq[$this->platform_key])->where('group_name', $dataReq['group_name'])->get();
		} else {
			$objects	= diy_query($this->model_table)->where('group_name', $dataReq['group_name'])->get();
		}
		
		return count($objects);
	}
	
	/**
	 * Stored to inserting data packages requested by $_POST data
	 * 
	 * created @Sep 11, 2017
	 * author: wisnuwidi
	 * 
	 * @tutorial: 1. This script would check user sessions group.
	 * @tutorial: 2. If user has logged as External users Groups, the data requested would be merge [ $this->platform_key ] from user sessions.
	 * @tutorial: 3. If user has logged as Internal Root Group, [ $this->platform_key ] data requested would send by [ $this->platform_key ], posted by selected form.
	 * @tutorial: 4. Group name data inserted would be uniquee in every [ $this->platform_key ].
	 * @tutorial: 5. Modular data checkboxes, used for setting the access privileges in every single group in every single [ $this->platform_key ].
	 * @tutorial: 6. Modular data checkboxes collections values, would added after inserting group data.
	 * 			 	 It would draw all the modular array before inserting in the base_group_privilege table ["see: $this->set_data_before_insert($callbackRequest, $model_id)"]
	 * @tutorial: 7. Last process, modular data collections package would insert into base_group_privilege table with group_id and module_id.
	 * 			 	 These row data packages would set group privileges in every single group with every single [ $this->platform_key ]
	 * 			 	 ["see: $this->set_data_after_insert($this->roles)"].
	 * 
	 * @param Request $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request) {
		$this->get_session();
		if ('root' !== $this->session['user_group']) {
			if (true === is_multiplatform()) {
				$request->merge([$this->platform_key => $this->session[$this->platform_key]]);
			}
		}
		
		$this->validations['group_name'] = 'required';//'required|unique:base_group';
		$request->validate($this->validations);
		$this->validate(request(), [
			'group_name' => [function ($attribute, $value, $fail) {
				$groupname = ucwords(str_replace('_', ' ', $attribute));
				$check = $this->validation_groups(request());
				if ($check >= 1) {
					$fail(":{$groupname} dengan nama '{$value}' sudah terdaftar. Tolong pilih nama lainnya!");
				}
			}]
		]);
		
		$requests = $request->all();                                    // collect all requests
		if (isset($requests['modules'])) {
			$modules            = [];
			$modules['modules'] = $requests['modules'];                  // get modules requests, if any
			$request->offsetUnset('modules');                            // throw modules request before insert to group table)
			$model_id           = diy_insert(new Group, $request, true); // get group id after request (get last id)
			$callbackRequest    = $request->merge($modules);             // callback the all requests
		} else {
			$model_id           = diy_insert(new Group, $request, true); // get group id after request (get last id)
			$callbackRequest    = $request;
		}
		
		$this->set_data_before_insert($callbackRequest, $model_id);
		$this->set_data_after_insert($this->roles);
		$route_group = str_replace('.', '/', $this->route_page);
		
		return redirect("/{$route_group}/group/{$model_id}/edit"); 
	}
	
	/**
	 * Update Group Data
	 *
	 * created @Sep 11, 2017
	 * author: wisnuwidi
	 * 
	 * @param integer|string $id
	 *
	 * @tutorial: Description Logic as Internal ROOT
	 * 		: 1. This form will rendering all fields in group table including [ $this->platform_key ].
	 * 		: 2. This form will rendering all checkbox privileges group when you edit other group except internal root (group) edit page.
	 * @tutorial: Description Logic as External Administrator ( users )
	 * 		: 1. This form will rendering all fields in group table except [ $this->platform_key ].
	 * 			 [ $this->platform_key ] data field would be posted into the database using [ $this->platform_key ] value saved in the user data sessions.
	 * 		: 2. This form will rendering all checkbox privileges group when you edit other group except internal root (group) and external admin(group) edit page.
	 *
	 * @return array|\Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function edit($id) {	
		$this->setPage();
		$this->get_menu();
		
		$this->form->model();
		$this->form->text('group_name', null, ['required', 'readonly']);
		$this->form->text('group_info', null, ['required']);
		$this->form->selectbox('active', active_box(), $this->model_data->active, ['required']);
		
		if (1 === $this->session['group_id'] || true === diy_string_contained($this->session['user_group'], 'admin'))	{
			if ('root' !== $this->model_data->group_name) { //&& false === diy_string_contained($this->model_data->group_name, 'admin')) {
				// SET PRIVILEGES BOX
				$this->form->openTab('Module Privileges');
				$this->form->draw($this->group_privilege());
				$this->form->closeTab();
			}
		}
		$this->form->close('Save Group');
		
		return $this->render();
	}
	
	/**
	 * Get Current Group Data
	 * 
	 * @tutorial: This data used for checking uniquee group name from group_name data posted
	 * 
	 * created @Sep 11, 2018
	 * author: wisnuwidi
	 * 
	 * @param integer|string $id
	 * 
	 * @return object
	 */
	private function get_current_group($id) {
		return diy_query($this->model_table)->where('id', $id)->first();
	}
	
	public function update(Request $request, $id) {
		$this->get_session();
		if ('root' !== $this->session['user_group']) {
			if (true === is_multiplatform()) {
				$request->merge([$this->platform_key => $this->session[$this->platform_key]]);
			}
		}
		$request->validate($this->validations);
		
		// get current group name
		$posts			= $request->all();
		$post_name		= strtolower($posts['group_name']);
		$current_group	= $this->get_current_group($id);
		$current_name	= strtolower($current_group->group_name);
		
		if ($current_name != $post_name) {
			$this->validate(request(), [
				'group_name' => [function ($attribute, $value, $fail) {
					$groupname = ucwords(str_replace('_', ' ', $attribute));
					$check = $this->validation_groups(request());
					if ($check >= 1) $fail("{$groupname} dengan nama '{$value}' sudah terdaftar. Tolong pilih nama lainnya!");
				}]
			]);
		}
		
		$this->set_data_before_insert($request, $id);
		diy_update($this->model->find($id), $request, true);
		$this->set_data_after_insert($this->roles);
		$route_back = url()->current();
		
		return redirect("{$route_back}/edit");
	}
}