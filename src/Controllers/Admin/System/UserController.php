<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Illuminate\Http\Request;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Models\Admin\System\User as User;
use Incodiy\Codiy\Models\Admin\System\Group;
use Incodiy\Codiy\Models\Admin\System\Usergroup;
use Incodiy\Codiy\Models\Admin\System\Language;
use Incodiy\Codiy\Models\Admin\System\Timezone;

/**
 * Created on Jul 26, 2017
 * Time Created	: 10:49:43 AM
 * Filename		: UserController.php
 *
 * @filesource	UserController.php
 *
 * @author		wisnuwidi @Expresscode - 2017
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
class UserController extends Controller {
	
	private $validations	= [
		'username' => 'required',
		'fullname' => 'required',
		'email'    => 'required',
		'password' => 'required',
		'group_id' => 'required_if:base_group,0|not_in:0'
	];
	
	public $data;
	public $users;
	public $name = 'user_accounts';
	
	public function __construct() {
		parent::__construct();
		
		$this->set_route_page('system.accounts.user');
		$this->model(User::class);
	}
	
	private $user_groups;
	private function input_group() {
		if ('root' !== $this->session['user_group']) {
			if (true === is_multiplatform()) {
				$this->user_groups = Group::where('group_name', '!=', 'root')->where($this->platform_key, $this->session[$this->platform_key])->get();
			} else {
				$this->user_groups = Group::where('group_name', '!=', 'root')->get();
			}
		} else {
			$this->user_groups = Group::all();
		}
		
		return diy_selectbox($this->user_groups, 'id', 'group_info');
	}
	
	private function input_language() {
		return diy_selectbox(Language::all(), 'abbr', 'language');
	}
	
	private function input_timezone() {
		return diy_selectbox(Timezone::all(), 'id', 'timezone');
	}
	
	/**
	 * Render Combobox [ $this->platform_label ] Data
	 *
	 * created @Sep 11, 2018
	 * author: wisnuwidi
	 *
	 * @param boolean $user_group
	 *
	 * @return array|string[]|array[]
	 */
	private function input_platform($user_group = false) {
		if (true === is_multiplatform()) {
		//	return set_combobox_data(Multiplatforms::all(), 'id', 'name');
		}
	}
	/*
	public function get_group_by_platforms($platform_key) {
		if (true === is_multiplatform()) {
			return json_query('base_group', ['id', 'group_name'], ['id', 'group_name'], [$this->platform_key => $platform_key]);
		}
	}
	 */
	private $group_id;
	private function set_data_before_post($request, $action_type = 'create') {
		if (true === is_object($request)) {
			$requests = $request;
		} else {
			$req      = new Request();
			$requests = $req->merge($request);
		}
		
		$group_id = [
			'group_id' => $requests->group_id,
			'email'    => $requests->email
		];
		$this->group_id = $group_id;
		
		$requests->offsetUnset('group_id');
		$requests->merge(["{$action_type}d_by" => $this->session['id']]);
	}
	
	private function set_data_after_post($data, $id = false) {
		$email = $data['email'];
		$user  = User::select('id')->where('email', $email)->first();
		unset($data['email']);
		
		$new_array = array_merge($data, ['user_id' => $user->id]);
		$request   = new Request();
		$request->merge($new_array);
		
		$user_group = new Usergroup();
		if (false !== $id) {
			$userGroup = diy_query_get_id($user_group, ['user_id' => $id]);
			diy_update($user_group->find($userGroup->id), $request, true);
		} else {
			diy_insert($user_group, $request, true);
		}
	}
	
	public function index() {
		$this->meta->title('User Lists');
		$this->set_session();		
		/* 
		if ('root' === $this->session['user_group']) {
			$this->form->change_list_header($this->relational_data_set, 'User Group', 'group_name');
			$fieldSet = ['name', 'email', 'address', 'phone', $this->relational_data_set, 'active'];
		}
		 */		
		$this->table->searchable(['username', 'email']);
		$this->table->clickable();
		$this->table->sortable();
		
		$this->table->lists($this->model_table, ['username', 'email', 'address', 'phone', 'active']);
		
		return $this->render();
	}
	
	public function show($id) {
		$model_data     = User::find($id);
		$group_data     = $model_data->group;
		$selected_group = false;
		
		foreach ($group_data as $group) {
			$selected_group = $group->id;
		}
		
		$this->set_page('Detail User', 'user');
		
		$this->form->model($model_data, false);
		
		$this->form->text('name', $model_data->name, ['required']);
		$this->form->text('fullname', $model_data->fullname, ['required']);
		$this->form->text('email', $model_data->email, ['required']);
		$this->form->password('password', ['placeholder' => '********']);
		$this->form->selectbox('active', active_box(), $model_data->active);
		
		$this->form->openTab('User Group');
		$this->form->selectbox('group_id', $this->input_group(), $selected_group, ['required'], 'User Group');
		if (true === is_multiplatform()) {
			$this->form->selectbox($this->platform_key, $this->input_platform(), $model_data->{$this->platform_key}, ['required'], $this->platform_label);
		}
		
		$this->form->openTab('User Info');
		$this->form->file('photo', ['imagepreview']);
		$this->render_input_js_imagepreview($model_data->photo);
		$this->form->textarea('address', $model_data->address);
		$this->form->text('phone');
		$this->form->selectbox('language', $this->input_language(), 'id_ID');
		$this->form->selectbox('timezone', $this->input_timezone(), 218);
		
		$this->form->openTab('User Status');
		$this->form->date('expire_date');
		$this->form->selectbox('change_password', active_box());
		$this->form->closeTab();
		
		$this->form->close();
		
		return $this->render();
	}
	
	public function create() {
		$this->set_session();
		$this->meta->title('Add User');
		
		$this->form->modelWithFile();
		
		$this->form->text('username', null, ['required']);
		$this->form->text('fullname', null, ['required']);
		$this->form->text('email', null, ['required']);
		$this->form->password('password', ['required']);
		$this->form->selectbox('active', active_box(), false, ['required']);
		
		$this->form->openTab('User Info');
		$this->form->file('photo', ['imagepreview']);
		$this->form->textarea('address', null, ['class' => 'form-control ckeditor']);
		$this->form->text('phone');
		$this->form->selectbox('language', $this->input_language(), 'id_ID', ['required']);
		$this->form->selectbox('timezone', $this->input_timezone(), 218, ['required']);
		
		if ('root' === $this->session['user_group']) {
			$this->form->openTab('User Group');
			if (true === is_multiplatform()) {
				$this->form->selectbox($this->platform_key, $this->input_platform(), false, ['required'], $this->platform_label);
			}
			$this->form->selectbox('group_id', $this->input_group(), false, ['required'], 'User Group');
		}
		
		$this->form->openTab('User Status');
		$this->form->date('expire_date');
		$this->form->selectbox('change_password', active_box(), false, ['required']);
		$this->form->closeTab();
		
		$this->form->close('Submit');
		
		return $this->render();
	}
	
	public function store(Request $request) {
		$this->get_session();
		if ('root' === $this->session['user_group']) {
			if (true === is_multiplatform()) {
				$this->validations[$this->platform_key] = 'required';
			}
		}
		$this->validations['email'] = 'required|unique:users';
		
		$request->validate($this->validations);
		if (true === is_multiplatform()) {
			$request->offsetUnset($this->platform_key);
		}
		$this->set_data_before_post($request);
		$this->insert_data($request, false);
		$this->set_data_after_post($this->group_id);
		
		$route_back = str_replace('.', '/', $this->route_page);
		return redirect("{$route_back}/{$this->stored_id}/edit");
	}
	
	public function edit($id) {
		$this->set_session();
		$this->meta->title('Edit User');
		
		$model_data     = $this->model::withTrashed()->find($id);
		$group_data     = $model_data->group;
		$selected_group = false;
		foreach ($group_data as $group) {
			$selected_group = $group->id;
			if (true === is_multiplatform()) $platform_platforms = $group->{$this->platform_key};
		}
		
		if (intval($model_data->id) === intval($this->session['id'])) {
			if (true === is_multiplatform()) $this->form->setHiddenFields([$this->platform_key, 'group_id']);
		}
		
		$this->form->modelWithFile();
		$this->form->text('username', $model_data->name, ['required']);
		$this->form->text('fullname', $model_data->fullname, ['required']);
		$this->form->text('email', $model_data->email, ['required']);
		$this->form->password('password', ['placeholder' => '********']);
		$this->form->selectbox('active', active_box(), $model_data->active);
		
		$this->form->openTab('User Info');
		$this->form->file('photo', ['imagepreview']);
		$this->form->textarea('address', $model_data->address, ['class' => 'form-control ckeditor']);
		$this->form->text('phone', $model_data->phone);
		$this->form->selectbox('language', $this->input_language(), 'id_ID');
		$this->form->selectbox('timezone', $this->input_timezone(), 218);
				
		if ('root' === $this->session['user_group']) {
			if (intval($model_data->id) !== intval($this->session['id'])) {
				$this->form->openTab('User Group');
			}
			
			if (true === is_multiplatform()) {
				$this->form->selectbox($this->platform_key, $this->input_platform(), $platform_platforms, ['required'], $this->platform_label);
			}
			$this->form->selectbox('group_id', $this->input_group(), $selected_group, ['required'], 'User Group');
		}
		
		$this->form->openTab('User Status');
		$this->form->date('expire_date', $model_data->expire_date);
		$this->form->selectbox('change_password', active_box());
		$this->form->closeTab();
		
		$this->form->close('Submit');
		
		if ('root' === $this->session['user_group']) {
			if (true === is_multiplatform()) {
				$this->form->setComboboxActionJS($this->platform_key, 'group_id', 'system.accounts.user.get_group_by_platforms', 'base_group_value', 'base_group_label');
			}
		}
		
		return $this->render();
	}
	
	public function update(Request $request, $id) {
		$this->get_session();
		
		$model_data = $this->model->find($id);
		$data       = $request->all();
		
		if ('root' === $this->session['user_group']) {
			if (true === is_multiplatform()) {
				$this->validations[$this->platform_key] = 'required';
			}
		}
		$this->validations['email'] = 'required';
		if ($model_data->email !== $data['email']) {
			$this->validations['email'] = 'required|unique:users';
		}
		if (null === $request->password) {
			unset($this->validations['password']);
			$request->offsetUnset('password');
		}
		$request->validate($this->validations);
		
		if (true === is_multiplatform()) {
			$request->offsetUnset($this->platform_key);
		}
		
		$this->set_data_before_post($request, __FUNCTION__);
		$this->update_data($request, $id, false);
		$this->set_data_after_post($this->group_id, $id);
		
		$route_back = str_replace('.', '/', $this->route_page);
		return redirect("{$route_back}/{$id}/edit");
	}
	
	/**
	 * Delete(soft) User
	 * 
	 * @param Request $request
	 * @param int $id
	 * @param object $model
	 * 
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 * created @Aug 11, 2018
	 * author: wisnuwidi
	 */
	public function destroyx(Request $request, $id, User $model) {
		return delete($request, $id, $model, $this->route_page);
	}
}