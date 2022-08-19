<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Illuminate\Http\Request;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Models\Admin\System\Group;
use Incodiy\Codiy\Controllers\Admin\System\Includes\Privileges;
#use Incodiy\Codiy\Models\Admin\System\Multiplatforms;

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
	
	private $route_group			 = 'system.config';
	private $table_privilege	 = 'base_group_privilege';
	
	private $id						 = false;
	private $_set_tab				 = [];
	private $_tab_config			 = [];
	private $_hide_fields		 = ['id'];
	private $validations			 = ['group_name' => 'required', 'group_info' => 'required', 'active' => 'required'];
		
	public $data;
	public $model;
	
	public function __construct() {
		parent::__construct();
		
		$this->model(Group::class);
	}
	
	private function set_route($path) {
		return $this->route_group . '.group.' . $path;
	}
	
	private function check_model() {
	//	$relational = is_multiplatform();
		if ('root' !== $this->session['user_group']) {
			$this->model = $this->model->where('group_name', '!=', 'root');
		}
	}
	
	private function check_data($group_id, $module_id) {
		$data = diy_query($this->table_privilege)
			->where('group_id', $group_id)
			->where('module_id', $module_id)
			->first();
		
		return $data;
	}
	
	private function check_group($group_id) {
		$data = diy_query($this->table_privilege)
			->where('group_id', $group_id)
			->first();
		
		return $data;
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
		$this->meta->title('Group Lists');
		
		$this->table->mergeColumns('Group', ['group_name', 'group_info']);
		
		$this->table->searchable(['group_name', 'group_info']);
		$this->table->clickable();
		$this->table->sortable();
		
		$this->table->lists($this->model_table, ['group_name', 'group_info', 'active']);
		
		return $this->render();
	}
	
	/**
	 * Render Form Data Group
	 * 
	 * created @Sep 11, 2018
	 * author: wisnuwidi
	 * 
	 * @param integer|string $id
	 * 
	 * @return array|\Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function showx($id) {
		$model_data = Group::find($id);
		
		$this->meta->title('Detail Group');
		
		$this->form->config_show_data_static($model_data, $this->model_table, $this->_hide_fields);
		$this->form->model($model_data, $this->set_route('index'));
		$this->form->table($this->model_table, $this->_set_tab, $this->_tab_config);
				
		return $this->render();
	}
	
	/**
	 * Render Combobox Masjid Data
	 * 
	 * created @Sep 11, 2018
	 * author: wisnuwidi
	 * 
	 * @param boolean $user_group
	 * 
	 * @return array|string[]|array[]
	 */
	private function input_platforms($user_group = false) {/* 
		$masjid = Multiplatforms::all();
		
		return set_combobox_data($masjid, 'id', 'name'); */
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
		$this->meta->title('Add Group');
		
		$this->set_session();
		$this->get_menu();
		$this->check_model();
		
		$this->form->model();
		/* 
		if ('root' === $this->session['user_group']) {
			$this->form->selectbox($this->platform_key, $this->input_platforms(), false, ['required'], 'Masjid');
		}
		 */
		$this->form->text('group_name', null, ['required']);
		$this->form->text('group_info', null, ['required']);
		$this->form->selectbox('active', active_box(), false, ['required']);
		$this->form->openTab('Privilege');
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
		$route_group = str_replace('.', '/', $this->route_group);
		
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
		$model_data = $this->model->find($id);		
		$this->meta->title('Edit Group');
		$this->get_menu();
		
		$this->form->model();
		
		$this->form->text('group_name', null, ['required']);
		$this->form->text('group_info', null, ['required']);
		$this->form->selectbox('active', active_box(), $model_data->active, ['required']);
		
		// SET PRIVILEGES BOX
		$this->form->openTab('Page Privileges');
		$this->form->draw($this->group_privilege());
		$this->form->closeTab();
		
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
		
		$this->set_data_before_insert($request);
		diy_update($this->model->find($id), $request, true);
		$this->set_data_after_insert($this->roles);
		$route_back = url()->current();
		
		return redirect("{$route_back}/edit");
	}
	
	/**
	 * Delete(soft) Group
	 * 
	 * created @Aug 13, 2018
	 * author: wisnuwidi
	 * 
	 * @param Request $request
	 * @param int $id
	 * @param Group $model
	 * 
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function destroyx(Request $request, $id, Group $model) {
		return diy_delete($request, $id, $model, $this->route_group . '.group');
	}
}