<?php
namespace Incodiy\Codiy\Controllers\Core\Craft;

use Incodiy\Codiy\Library\Components\Table\Craft\Datatables;

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
	
	public $pageType			= false;
	
	private $pageView;
	private $viewAdmin;
	private $viewFront;
	private $dataOptions		= [];
	
	protected $hideFields		= [];
	protected $excludeFields	= [];
	
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
		if (empty($this->pageView)) {
			$this->configView();
		}
		
		$formElements = [];
		if (!empty($this->data['components']->form->elements)) {
			$formElements = $this->form->render($this->data['components']->form->elements);
		}
		
		$tableElements = [];
		if (!empty($this->data['components']->table->elements)) {
			$tableElements = $this->table->render($this->data['components']->table->elements);
		}
		
		$this->addScriptsFromElements();
		
		if (!empty($_GET['renderDataTables'])) {
			return $this->initRenderDatatables();
		}
		
		if (false !== $data) {
			if (!is_array($data)) {
				// if $data variable not array
				$data_contents = [$data];
				$merge_data = array_merge($this->data['content_page'], $data_contents);
			} else {
				// if $data variable is an array
				if (diy_is_empty($data)) {
					// if array = []
					$merge_data = $this->data['content_page'];
				} else {
					$data_contents	= $data;
					$merge_data		= array_merge($this->data['content_page'], $data_contents);
				}
			}
			$dataContent = array_merge($merge_data, $formElements, $tableElements);
			
			$this->data['content_page'] = $dataContent;
		} else {
			$this->data['content_page'] = array_merge($formElements, $tableElements);
		}
		
		return view($this->pageView, $this->data, $this->dataOptions);
	}
	
	private function initRenderDatatables() {
		if ('false' != $_GET['renderDataTables']) {
			$Datatables					= [];
			$Datatables['datatables']	= $this->data['components']->table;
			$datatables					= diy_array_to_object_recursive($Datatables);
			
			$filters					= [];
			if (!empty($_GET['filters'])) {
				if ('true' === $_GET['filters']) $filters = $_GET;
			}
			
			$DataTables = new Datatables();
			
			return $DataTables->process($datatables, $filters);
		}
	}
	
	public function setPageType($page_type = true) {
		if (false === $page_type || str_contains($page_type, 'front') || str_contains($page_type, 'index')) {
			$this->pageType = 'frontpage';
		} else {
			$this->pageType = 'adminpage';
		}
	}
	
	/**
	 * Set Page Attributes
	 *
	 * created @Apr 8, 2018
	 * author: wisnuwidi
	 *
	 * @param string $page
	 * @param string $url
	 */
	protected function setPage($page, $path = false) {
		$page_name = diy_underscore_to_camelcase($page);
		
		$this->meta->title($page_name);
		$this->configView($path);
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
}