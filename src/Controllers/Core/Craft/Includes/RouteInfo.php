<?php
namespace Incodiy\Codiy\Controllers\Core\Craft\Includes;

use Illuminate\Support\Facades\Route;
use Incodiy\Codiy\Models\Admin\System\Modules;

/**
 * Created on 9 Apr 2021
 * Time Created : 14:49:04
 *
 * @filesource	RouteInfo.php
 *
 * @author    wisnuwidi@gmail.com - 2021
 * @copyright wisnuwidi
 * @email     wisnuwidi@gmail.com
 */
 
trait RouteInfo {
	use Privileges;
	
	public $pageInfo;
	public $routeInfo;
	public $route_page;
	public $controllerName;
	public $currentRoute;
	public $actionButton = ['index', 'index', 'edit', 'show'];
	
	/**
	 * Hide action button(s) in module page.
	 *
	 * created @Aug 10, 2018
	 * author: wisnuwidi
	 */
	public function hideActionButton() {
		$this->actionButton = [];
	}
	
	public function set_route_page($route) {
		$this->route_page = $route;
	}
	
	/**
	 * Get Current Page Information Data
	 *
	 * created @Nov 10, 2018
	 * author: wisnuwidi
	 */
	private function get_pageinfo() {
		$this->currentRoute   = Route::getCurrentRoute();
		$action_route         = (object) $this->currentRoute->getAction();
		$controller_path      = $action_route->controller;
		
		if (!diy_string_contained($controller_path, 'Controllers@')) {
			$slice_controller  = explode('Controllers', $controller_path);
			$slice_controller  = explode('Controller', $slice_controller[1]);
			$this->pageInfo    = str_replace('@', '', $slice_controller[1]);
		} else {
			$slice_controller  = explode('@', $controller_path);
			$this->pageInfo    = $slice_controller[1];
		}
		
		$slice_controller     = explode('\\', $slice_controller[0]);
		$this->controllerName = last($slice_controller);
	}
	
	/**
	 * Get Current Route Information
	 *
	 * Edit +/ Show = Back + Add
	 * Create = Back
	 * Index = Add
	 */
	public function routeInfo() {
		
		if (strpos(php_sapi_name(), 'cli') === false) {
			$this->module_privileges();
			$this->get_pageinfo();
			
			$action_role           = [];
			$action_role['show']   = false;
			$action_role['create'] = false;
			$action_role['edit']   = false;
			$action_role['delete'] = false;
			
			$actionPage            = [];
			$actionPage['show']    = [];
			$actionPage['create']  = [];
			$actionPage['edit']    = [];
			$actionPage['delete']  = [];
			
			$action_page = [];
			$action_page['action_page'] = [];
			
			if (!empty($this->module_privilege['actions'])) {
				foreach ($this->module_privilege['actions'] as $role_action) {
					$action_role[$role_action] = true;
				}
			}
			
			if (count($this->actionButton) >= 1) {
				if ('index' === $this->pageInfo && true === $action_role['create']) {
					$action_page['action_page'] = ["warning|add {$this->controllerName}" => $this->routeReplaceURL('index', 'create')];
					
				} elseif ('create' === $this->pageInfo && true === $action_role['create']) {
					$action_page['action_page'] = ["info|back to {$this->controllerName} lists" => $this->routeReplaceURL('create', 'index')];
					
				} elseif ('edit' === $this->pageInfo) {
					
					if (true === $action_role['delete']) {
						if (true === $this->is_softdeleted) {
							$actionPage['delete'] = ["secondary|restore {$this->controllerName}" => $this->routeReplaceURL('edit', 'destroy')];
						} else {
							$actionPage['delete'] = ["danger|delete {$this->controllerName}" => $this->routeReplaceURL('edit', 'destroy')];
						}
					}
					if (true === $action_role['create']) {
						$actionPage['create'] = ["warning|add {$this->controllerName}" => $this->routeReplaceURL('edit', 'create')];
					}
					if (true === $action_role['show']) {
						$actionPage['edit'] = ["success|view this {$this->controllerName}"  => str_replace('/edit', '', url()->current())];
						$actionPage['show'] = ["info|back to {$this->controllerName} lists" => $this->routeReplaceURL('edit', 'index')];
					}
					
					$action_page['action_page'] = array_merge_recursive($actionPage['delete'], $actionPage['create'], $actionPage['edit'], $actionPage['show']);
					
				} elseif ('show' === $this->pageInfo && true === $action_role['show']) {
					$action_page['action_page'] = [
						"warning|add {$this->controllerName}"        => $this->routeReplaceURL('show', 'create'),
						"success|edit this {$this->controllerName}"  => url()->current() . '/edit',
						"info|back to {$this->controllerName} lists" => $this->routeReplaceURL('show', 'index')
					];
				}
			}
			
			$routeInfo = [
				'current_path' => $this->currentRoute->getName(),
				'module_name'  => $this->controllerName,
				'page_info'    => $this->pageInfo
			];
			
			$this->setDataValues('route_info', (object) array_merge($routeInfo, $action_page));
		}
	}
	
	private function routeReplaceURL($from, $to) {
		$routeUri = str_replace($from, $to, $this->currentRoute->getName());
		
		if ('destroy' !== $to) {
			return route($routeUri);
		} else {
			$routeURI = $routeUri;
			$routeUri = explode('/', diy_current_url());
			unset($routeUri[array_key_last($routeUri)]);
			
			return $routeURI . '::' . (int) last($routeUri);
		}
	}
}