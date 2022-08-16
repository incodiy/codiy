<?php
namespace Incodiy\Codiy\Controllers\Admin\System;

use Illuminate\Http\Request;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Models\Admin\System\Preference;
use Incodiy\Codiy\Models\Admin\System\Language;
use Incodiy\Codiy\Models\Admin\System\Timezone;

/**
 * Created on Mar 7, 2018
 * Time Created	: 9:41:31 AM
 * Filename		: PreferenceController.php
 *
 * @filesource	PreferenceController.php
 *
 * @author		wisnuwidi@gmail.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
class PreferenceController extends Controller {
	public $data;
	
	private $name			= 'base_preference';
	private $route_group	= 'system.config.preference';
	private $model_table	= 'base_preference';
	private $model;
	
	private $_set_tab		= [];
	private $_tab_config	= [];
	
	public function __construct() {
		parent::__construct();
		
		$this->model		= Preference::all();
		$this->base_route	= "{$this->route_group}.";
	}
	
	public function index() {
		$this->hide_button_actions();
		return $this->edit(1);
	}
	
	private function input_language() {
		return set_combobox_data(Language::all(), 'abbr', 'language');
	}
	
	private function input_timezone() {
		return set_combobox_data(Timezone::all(), 'id', 'timezone');
	}
	
	public function edit($id) {
		$this->hide_button_actions();
		$model_data		= Preference::find($id);
		
		$this->set_page('Edit Preference', 'preference');
	//	$this->form->alert_message('Sukses');
		
		$this->form->model($model_data, "{$this->route_group}.update", $model_data->id, true);
		
		$this->form->text('title');
		$this->form->text('sub_title');
		$this->form->file('logo', ['imagepreview']);
		$this->render_input_js_imagepreview($model_data->logo);
		
		$this->form->textarea('header', $model_data->header);
		$this->form->textarea('footer', $model_data->footer);
		$this->form->selectbox('template', ['', 'Default'], $model_data->template);
		$this->form->selectbox('language', $this->input_language(), $model_data->language);
		$this->form->selectbox('timezone', $this->input_timezone(), $model_data->timezone);
		
		$this->form->open_tab('Session');
		$this->form->text('session_name', $model_data->session_name);
		$this->form->text('session_lifetime', $model_data->session_lifetime);
		
		$this->form->open_tab('Meta Tag');
		$this->form->text('meta_author', $model_data->meta_author);
		$this->form->tags('meta_title', $model_data->meta_title);
		$this->form->tags('meta_keywords', $model_data->meta_keywords);
		$this->form->textarea('meta_description|limit:500', $model_data->meta_description);
		
		$this->form->open_tab('Email Preference');
		$this->form->text('email_person', $model_data->email_person);
		$this->form->text('email_address', $model_data->email_address);
		
		$this->form->open_tab('SMTP Setting');
		$this->form->text('smtp_host', $model_data->smtp_host);
		$this->form->text('smtp_port', $model_data->smtp_port);
		$this->form->text('smtp_secure', $model_data->smtp_secure);
		$this->form->text('smtp_user', $model_data->smtp_user);
		$this->form->password('smtp_password');
		
		$this->form->open_tab('Web Preference');
		$this->form->text('login_attempts', $model_data->login_attempts);
		$this->form->text('change_password', $model_data->change_password);
		$this->form->selectbox('debug', ['No', 'Yes'], $model_data->debug);
		$this->form->selectbox('maintenance', ['No', 'Yes'], $model_data->maintenance);
		$this->form->close_tab();
		
		$this->form->close('Submit', ['class' => 'btn btn-primary btn-slideright pull-right']);
		
		return $this->render();
	}
	
	public function update(Request $request, $id) {
		$filename	= 'logo';
		$req			= $request->all();
		
		if (isset($req[$filename])) {
			$data	= $this->data_file_processor($this->name, $request, $filename, 'image|mimes:jpeg,png,jpg,gif,svg|max:2048');
		} else {
			// throw file request
			$data	= array_merge_recursive ($request->except($filename));
		}
		
		$urli = str_replace('/1', '', url()->current());
		return update(Preference::find($id), $data, $urli);
	}
}