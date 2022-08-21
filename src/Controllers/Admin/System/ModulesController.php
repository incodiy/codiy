<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Illuminate\Http\Request;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Models\Admin\System\Modules;
use Incodiy\Codiy\Models\Admin\System\Icon;

/**
 * Created on Jan 11, 2018
 * Time Created	: 7:38:49 AM
 * Filename		: ModulesController.php
 *
 * @filesource	ModulesController.php
 *
 * @author		wisnuwidi@Expresscode - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
class ModulesController extends Controller {
	public $data;
	
	private $name			   = 'module';
	private $route_group	   = 'system.config';
	
	private $_hide_fields	= ['id'];
	private $_set_tab			= [];
	private $_tab_config		= [];
	private $validations		= [
		'route_path'	=> 'required|not_in:0',
		'flag_status'	=> 'required',
		'active'		=> 'required',
	];
	
	private $filters = [];
	public function __construct() {
		parent::__construct(Modules::class, 'system.config');
	}

	/**
	 * Rendering un/registered module(s).
	 * 
	 * @param boolean $selected
	 * @return boolean|string
	 * 
	 * author: wisnuwidi
	 */
	private function render_value_module_name($selected = false, $fullroutes = false) {
		$routes      = get_route_lists($selected, $fullroutes);
		$option_data = [];
		$pointer     = ' - ';
		
		foreach ($routes as $base_group => $base_model) {
			if ('single' !== $base_group) {
				foreach ($base_model as $model_name => $data_model) {
					foreach ($data_model as $model => $value) {
						if ('route_data' === $model) {
							$option_data[ucwords($base_group)][$value->route_base] = $pointer . diy_underscore_to_camelcase($model_name);
						} else {
							foreach ($value as $next_model => $next_val) {
								if ('route_data' === $next_model) {
									$option_data[ucwords($base_group) . ' / ' . ucwords($model_name)][$next_val->route_base] = $pointer . diy_underscore_to_camelcase($model);
								} else {
									foreach ($next_val as $vKey => $val) {
										if ('route_data' === $vKey) {
											$option_data[ucwords($base_group) . ' / ' . ucwords($model_name) . ' / ' . ucwords($model)][$val->route_base] = $pointer . diy_underscore_to_camelcase($next_model);
										}
									}
								}
							}
						}
					}
				}
			} else {
				foreach ($base_model as $model_name => $data_model) {
					foreach ($data_model as $model => $value) {
						if ('route_data' === $model) {
							$option_data[$value->route_base] = diy_underscore_to_camelcase($model_name);
						}
					}
				}
			}
		}
		
		return $option_data;
	}
	
	private function set_data_before_insert($request) {
		$module_slice = explode('.', $request->route_path);
		
		if (count($module_slice) >= 3) {
			$module_parent	= "{$module_slice[0]} {$module_slice[1]}";
			$module_name	= $module_slice[2];
		} elseif (count($module_slice) == 2) {
			$module_parent	= $module_slice[0];
			$module_name	= $module_slice[1];
		} else {
			$module_parent	= $module_slice[0];
			$module_name	= $module_parent;
		}
		
		$module_name = diy_underscore_to_camelcase($module_name);
		
		diy_merge_request($request, [
			'parent_name'	=> ucwords($module_parent),
			'module_name'	=> ucwords($module_name)
		]);
	}
	
	private function role_check() {
		$this->get_session();
		if ('root' === $this->session['user_group']) {
			return redirect(url()->route('admin.index'));
		}
	}
	
	private function input_icons() {
		return diy_combobox_data(Icon::all(), 'class', 'label');
	}
	
	private function check_session($redirect) {
		$this->get_session();
		if ('root' !== $this->session['user_group']) {
			return redirect(url()->route($redirect));
		}
	}
	
	public function index() {
		$this->setPage(ucwords($this->name) . ' Lists');
		
		$this->table->mergeColumns('Module', ['module_name', 'parent_name']);
		
		$this->table->searchable(['module_name', 'route_path']);
		$this->table->clickable();
		$this->table->sortable();
		
		$this->table->lists($this->model_table, ['module_name', 'parent_name', 'route_path', 'flag_status', 'active']);
		$this->table->clear();
		
		return $this->render();
	}
	
	public function create() {
		$this->setPage('Add ' . camel_case($this->name));
		if (count($this->render_value_module_name()) >= 1) {
			$disabled = [];
		} else {
			$disabled = ['disabled' => 'disabled'];
		}
		
		$this->form->model();
		
		$this->form->selectbox('route_path', $this->render_value_module_name(), false, ['required']);
		$this->form->text('module_name', null, $disabled);
		$this->form->textarea('module_info', null, $disabled);
		$this->form->selectbox('icon', $this->input_icons());
		
		if (count($this->render_value_module_name()) >= 1) {
			$this->form->selectbox('flag_status', flag_status(), false, ['required']);
			$this->form->selectbox('active', active_box(), false, ['required']);
			$this->form->close('Save Module', ['class' => 'btn btn-primary btn-slideright pull-right']);
		}
		
		return $this->render();
	}
	
	public function store(Request $request, $req = true) {
		$request->validate($this->validations);		
		$this->set_data_before_insert($request);
		
		$model       = diy_insert($this->model, $request, true);
		$route_group = str_replace('.', '/', $this->route_group);
		
		return redirect("/{$route_group}/module/{$model}/edit");
	}
	
	public function edit($id) {
		$this->setPage('Edit ' . camel_case($this->name));
		
		$model_data = $this->model->find($id);
		
		$this->form->model();
		$this->form->selectbox('route_path', $this->render_value_module_name($model_data->route_path), $model_data->route_path, ['required', 'disabled' => 'disabled']);
		$this->form->text('parent_name', null, ['disabled' => 'disabled']);
		$this->form->text('module_name');
		$this->form->textarea('module_info');
		$this->form->selectbox('icon', $this->input_icons(), $model_data->icon);
		$this->form->selectbox('flag_status', flag_status(), $model_data->flag_status, ['required']);
		$this->form->selectbox('active', active_box(), $model_data->active, ['required']);
		
		$this->form->close('Save Module', ['class' => 'btn btn-primary btn-slideright pull-right']);
		
		return $this->render();
	}
	
	public function update(Request $request, $id) {
		$request->validate($this->validations);
		$this->set_data_before_insert($request);
		
		diy_update($this->model->find($id), $request, true);
		$route_group	= str_replace('.', '/', $this->route_group);
		
		return redirect("/{$route_group}/module/{$id}/edit");
	}
}