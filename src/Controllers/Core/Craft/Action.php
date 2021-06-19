<?php
namespace Incodiy\Codiy\Controllers\Core\Craft;

use Illuminate\Http\Request;

/**
 * Created on 24 Mar 2021
 * Time Created	: 17:56:08
 *
 * @filesource	Action.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
trait Action {
	
	public $model		= [];
	public $validation	= [];
	public $uploadTrack;
	
	/**
	 * Redirect page after login
	 *
	 * created @Aug 18, 2018
	 * author: wisnuwidi
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function firstRedirect() {
		$group_id = intval($this->session_auth['group_id']);
		if (1 === intval($group_id)) {
			// root group as internal
			return redirect()->intended($this->rootPage);
		} else {
			// admin and/or another group except root group as external
			return redirect()->intended($this->adminPage);
		}
	}
	
	/**
	 * Get Model
	 * 
	 * @param boolean $find
	 * 
	 * @return object
	 */
	protected function getModel($find = false) {
		return diy_get_model($this->model, $find);
	}
	
	/**
	 * Get Table Name By Model
	 * 
	 * @param boolean $find
	 * 
	 * @return string
	 */
	protected function getModelTable($find = false) {
		return $this->getModel($find)->getTable();
	}
	
	public function create() {
		return $this->render();
	}
	
	public function edit($id) {
		if (!empty($this->getModel($id))) {
			$model = $this->getModel($id);
			$model->find($id);
			
			if (!empty($model->getAttributes())) {
				return $this->create();
			}
		}
	}
	
	public $filter_datatables = [];
	protected function filterDataTable(Request $request) {
		$this->filter_datatables = $request->all();
		return $this;
	}
	
	protected function store(Request $request, $model = []) {
		$req = $request->all();
		if (!empty($req['filters'])) {
			if ('true' === $req['filters']) {
				$this->filterDataTable($request);
			}
		} else {
			$request->validate($this->validation);
			if (empty($model)) $model = $this->getModel();
			
			// check if any input file type submited
			$data	= $this->checkFileInputSubmited($request);
			$id		= diy_insert($model, $data, true);
			
			return $this->routeBackAfterAction(__FUNCTION__, $id);
		}
	}
	
	protected function update(Request $request, $id) {
		$request->validate($this->validation);
		$model	= $this->getModel($id);
		
		// check if any input file type submited
		$data	= $this->checkFileInputSubmited($request);
		diy_update($model, $data);
		
		return $this->routeBackAfterAction(__FUNCTION__, $id);
	}
	
	protected function destroy(Request $request, $id) {
		$model	= $this->getModel();
		
		diy_delete($request, $model, $id);
		return $this->routeBackAfterAction(__FUNCTION__);
	}
	
	/**
	 * Get Data Model
	 *
	 * @param object $class
	 */
	protected function model($class) {
		$this->model = $class;
		if (!empty($this->form)) {
			$this->form->model = $this->model;
		}
	}
	
	/**
	 * Redirect Back After Sumbit Data Process
	 * 
	 * @param string $function_name
	 * @param integer $id
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	private function routeBackAfterAction($function_name, $id = false) {
		if (!empty($id)) {
			$routeBack = str_replace('.', '/', str_replace($function_name, "{$id}.edit", current_route()));
		} else {
			$routeBack = str_replace('.', '/', str_replace($function_name, '', current_route()));
		}
		
		return redirect($routeBack);
	}
	
	/**
	 * Set Upload Path URL
	 * 
	 * @return mixed
	 */
	private function setUploadURL() {
		$currentRoute = explode('.', current_route());
		unset($currentRoute[array_key_last($currentRoute)]);
		$currentRoute = implode('.', $currentRoute);
		
		return str_replace('.', '/', str_replace('.' . __FUNCTION__, '', $currentRoute));
	}
	
	/**
	 * Check If any input type file submited or not
	 * 
	 * @param Request $request
	 * @return object|\Illuminate\Http\Request
	 */
	private function checkFileInputSubmited(Request $request) {
		if (!empty($request->files)) {
			
			foreach ($request->files as $inputname => $file) {
				if ($request->hasfile($inputname)) {
					// if any file type submited
					return $this->uploadFiles($this->setUploadURL(), $request, $this->fileAttributes);
				} else {
					// if no one file type submited
					return $request;
				}
			}
			
			// if no one file type submited
			return $request;
			
		} else {
			// if no one file type submited
			return $request;
		}
	}
}