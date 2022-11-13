<?php
namespace Incodiy\Codiy\Controllers\Core\Craft;

use Incodiy\Codiy\Library\Components\Table\Craft\Datatables;
use Incodiy\Codiy\Models\Admin\System\Preference;

/**
 * Created on 25 Mar 2021
 * Time Created	: 12:53:25
 *
 * @filesource	View.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
trait View {
	
	public $pageType         = false;
	
	private $pageView;
	private $viewAdmin;
	private $viewFront;
	private $dataOptions     = [];
	
	protected $hideFields    = [];
	protected $excludeFields = [];
	
	/**
	 * Get Some Hidden Set Field(s)
	 */
	private function getHiddenFields() {
		if (!empty($this->form)) $this->form->hideFields = $this->hideFields;
	}
	
	/**
	 * Get Some Exclude Set / Drop Off Field(s)
	 */
	private function getExcludeFields() {
		if (!empty($this->form)) $this->form->excludeFields = $this->excludeFields;
	}
	
	public function render($data = false) {
		if (empty($this->pageView)) $this->configView();
		
		$this->data['appName'] = diy_config('app_name');
		$this->data['logo']    = $this->logo_path();
		
		$formElements = [];
		if (!empty($this->data['components']->form->elements)) {
			$this->form->setValidations($this->validations);
			$formElements = $this->form->render($this->data['components']->form->elements);
		}
		
		$tableElements = [];
		if (!empty($this->data['components']->table->elements)) {
			$tableElements = $this->table->render($this->data['components']->table->elements);
		}
		
		$chartElements = [];
		if (!empty($this->data['components']->chart->elements)) {
			$chartElements = $this->chart->render($this->data['components']->chart->elements);
		}
		
		$this->addScriptsFromElements();
		
		// RENDER DATATABLES!!!
		if (!empty($_GET['renderDataTables'])) {
			$filter_datatables = [];
			if (!empty($this->model_filters)) {
				$filter_datatables = $this->model_filters;
			}
			
			return $this->initRenderDatatables([], $filter_datatables);
		}
		
		if (!empty($_GET['ajaxfproc'])) {
			return $this->form->ajaxProcessing();
		}
		
		$this->template->render_sidebar_menu($this->menu);
		$this->data['menu_sidebar']    = [];
		if (!is_null($this->template->menu_sidebar)) {
			$this->data['menu_sidebar'] = $this->template->menu_sidebar;
		}
		
		$this->template->render_sidebar_content();
		$this->data['sidebar_content']    = [];
		if (!is_null($this->template->sidebar_content)) {
			$this->data['sidebar_content'] = $this->template->sidebar_content;
		}
		
		// CHECK ROLE MODULE
		if (true === $this->is_module_granted) {
			
			$this->data['breadcrumbs'] = [];
			if (!is_null($this->template->breadcrumbs)) $this->data['breadcrumbs'] = $this->template->breadcrumbs;
			
			if (false !== $data) {
				if (!is_array($data)) {
					// if $data variable not array
					$data_contents = [$data];
					$merge_data    = array_merge($this->data['content_page'], $data_contents);
				} else {
					// if $data variable is an array
					if (diy_is_empty($data)) {
						// if array    = []
						$merge_data    = $this->data['content_page'];
					} else {
						$data_contents = $data;
						$merge_data    = array_merge($this->data['content_page'], $data_contents);
					}
				}
				$dataContent = array_merge($merge_data, $formElements, $tableElements, $chartElements);
				
				$this->data['content_page'] = $dataContent;
			} else {
				$this->data['content_page'] = array_merge($formElements, $tableElements, $chartElements);
			}
		} else {
			$this->data['breadcrumbs'] = null;
			$this->data['route_info']  = null;
		}
		
		if (empty($this->session['id'])) {
			$this->data['content_page'] = $this->loginPage();
		}
		
		return view($this->pageView, $this->data, $this->dataOptions);
	}
	
	private function loginPage() {
		$page = new Preference();
		$obj  = $page->first()->getAttributes();
		$data = [];
		
		if (!empty($obj)) {
			if (!empty($obj['logo']))             $data['login_page']['logo']       = $obj['logo'];
			if (!empty($obj['login_title']))      $data['login_page']['title']      = $obj['login_title'];
			if (!empty($obj['login_background'])) $data['login_page']['background'] = $obj['login_background'];
		}
		
		return $data;
	}
	
	private function initRenderDatatables($filters = [], $model_filters = []) {
		if ('false' != $_GET['renderDataTables']) {
			$Datatables = [];
			$Datatables['datatables'] = $this->data['components']->table;
			$datatables = diy_array_to_object_recursive($Datatables);
			
			if (!empty($_GET['filters'])) {
				if ('true' === $_GET['filters']) $filters = $_GET;
			}
			
			$DataTables = new Datatables();
			return $DataTables->process($datatables, $filters, $model_filters);
		}
	}
	
	public function setPageType($page_type = true) {
		if (false === $page_type || str_contains($page_type, 'front') || str_contains($page_type, 'index')) {
			$this->pageType = 'frontpage';
		} else {
			$this->pageType = 'adminpage';
		}
	}
	
	public $is_root = false;	
	public $filter_page = [];
	/**
	 * Set Page Attributes
	 *
	 * created @Apr 8, 2018
	 * author: wisnuwidi
	 *
	 * @param string $page
	 * @param string $url
	 */
	protected function setPage($page = null, $path = false) {
		$this->set_session();
		if (!empty($this->session['user_group'])) {
			$this->is_root = str_contains($this->session['user_group'], 'root');
		}
		$this->routeInfo();
		
		if (!empty($this->session['id'])) {
			$this->filter_page = diy_mapping_page(intval($this->session['id']));
		}
		
		if (!empty($this->model_class)) $this->model($this->model_class);
		if (is_empty($page)) {
			$currentPage = last(explode('.', current_route()));
			if (str_contains(strtolower($currentPage), 'index')) {
				$currentModule = strtolower(str_replace('Controller', '', class_basename($this))) . ' Lists';
			} else {
				$currentModule = $currentPage . ' ' . strtolower(str_replace('Controller', '', class_basename($this)));
			}
		} else {
			$currentModule = $page;
		}
		
		$page_name  = diy_underscore_to_camelcase($currentModule);
		$page_title = strtolower($currentModule);
		
		$this->meta->title($page_name);
		$this->template->set_breadcrumb (
			$page_name,  [$page_title => url($this->template->currentURL), 'index'],
			$page_title, [$page_title, 'home']
		);
		$this->configView($path);
		$this->filterPage();
		diy_log_activity();
	}
	
	private function uriAdmin($uri = 'index') {
		$this->viewAdmin = diy_config('template') . '.pages.admin';
		$this->pageView  = $this->viewAdmin . '.' . $uri;
	}
	
	private function uriFront($uri = 'index') {
		$this->viewFront = diy_config('template') . '.pages.front';
		$this->pageView  = $this->viewFront . '.' . $uri;
	}
	
	/**
	 * Configure View Path with spesification page type[ front page or admin page ]
	 *
	 * @param string $path
	 */
	private function configView($path = false) {
		$this->setPageType();
		$page_type = str_replace('page', '', $this->pageType);
		
		if (false !== $page_type) {
			if ('admin' === $page_type) {
				if ($path != false) {
					$this->uriAdmin($path);
				} else {
					$this->uriAdmin();
				}
			} else {
				if ($path != false) {
					$this->uriFront($path);
				} else {
					$this->uriFront();
				}
			}
		} else {
			if ($path != false) {
				$this->uriAdmin($path);
			} else {
				$this->uriAdmin();
			}
		}
		
		$this->data['page_type'] = $this->pageType;
		$this->data['page_view'] = $this->pageView;
	}
	
	private $preference;
	/**
	 * Get All Web Preferences
	 *
	 * created @Aug 21, 2018
	 * author: wisnuwidi
	 */
	private function getPreferences() {
		$this->preference = diy_get_model_data(Preference::class);
	}
	
	public function logo_path($thumb = false) {
		$this->getPreferences();
		
		if (true === $thumb) {
			return $this->preference['logo_thumb'];
		} else {
			return $this->preference['logo'];
		}
	}
}