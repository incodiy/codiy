<?php
namespace Incodiy\Codiy\Controllers\Core\Craft\Includes;

use Illuminate\Support\Facades\Route;
use Incodiy\Codiy\Models\Admin\System\Modules;

/**
 * Created on 9 Apr 2021
 * Time Created	: 14:49:04
 *
 * @filesource	RouteInfo.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
trait RouteInfo {
	use Privileges;
	
	public $pageInfo;
	public $routeInfo;
	public $route_page;
	public $controllerName;
	public $currentRoute;
	public $actionButton     = ['index', 'index', 'edit', 'show'];
	
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
		$slice_controller     = explode('Controllers', $controller_path);
		$slice_controller     = explode('Controller', $slice_controller[1]);
		$this->pageInfo       = str_replace('@', '', $slice_controller[1]);
		
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
			
			$action_page                = [];
			$action_page['action_page'] = [];
			
			if (count($this->actionButton) >= 1) {
				if ('index' === $this->pageInfo) {
					$action_page['action_page'] = ["warning|add {$this->controllerName}" => $this->routeReplaceURL('index', 'create')];
				} elseif ('create' === $this->pageInfo) {
					$action_page['action_page'] = ["info|back to {$this->controllerName} lists" => $this->routeReplaceURL('create', 'index')];
				} elseif ('edit' === $this->pageInfo) {
					$actionPage = [];
					if (true === $this->is_softdeleted) {
						$actionPage[] = ["secondary|restore {$this->controllerName}" => $this->routeReplaceURL('edit', 'destroy')];
					} else {
						$actionPage[] = ["danger|delete {$this->controllerName}" => $this->routeReplaceURL('edit', 'destroy')];
					}
					$actionPage[] = [
						"warning|add {$this->controllerName}"        => $this->routeReplaceURL('edit', 'create'),
						"success|view this {$this->controllerName}"  => str_replace('/edit', '', url()->current()),
						"info|back to {$this->controllerName} lists" => $this->routeReplaceURL('edit', 'index')
					];
					
					$action_page['action_page'] = array_merge_recursive($actionPage[0], $actionPage[1]);
					
				} elseif ('show' === $this->pageInfo) {
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
			$routeURI	= $routeUri;
			$routeUri	= explode('/', diy_current_url());
			unset($routeUri[array_key_last($routeUri)]);
			
			return $routeURI . '::' . (int) last($routeUri);
		}
	}
}