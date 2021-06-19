<?php
namespace Incodiy\Codiy\Controllers\Core;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Incodiy\Codiy\Controllers\Core\Craft\View;
use Incodiy\Codiy\Controllers\Core\Craft\Action;
use Incodiy\Codiy\Controllers\Core\Craft\Scripts;

use Incodiy\Codiy\Controllers\Core\Craft\Components\MetaTags;
use Incodiy\Codiy\Controllers\Core\Craft\Components\Template;
use Incodiy\Codiy\Controllers\Core\Craft\Components\Form;
use Incodiy\Codiy\Controllers\Core\Craft\Components\Table;

use Incodiy\Codiy\Controllers\Core\Craft\Includes\FileUpload;
use Incodiy\Codiy\Controllers\Core\Craft\Includes\RouteInfo;

/**
 * BismillahiRahmaniRahim
 * 
 * Created on 10 Mar 2021
 * Time Created : 13:23:43
 *
 * @filesource Controller.php
 *            
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
class Controller extends BaseController {
	
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	use MetaTags, Template;
	use Scripts, Action, View;
	use Form, FileUpload, RouteInfo;
	use Table;
	
	public $data			= [];
	public $session_auth	= [];
	public $getLogin		= true;
	public $rootPage		= 'home';//'system/config/module';
	public $adminPage		= 'dashboard';
	
	private $plugins		= [];
	
	public function __construct() {
		ini_set('memory_limit', '128M');
		
		$this->dataCollections();
		if (strpos(php_sapi_name(), 'cli') === false) {
			if (!empty($this->form)) $this->routeInfo();
		}
	}
	
	private function dataCollections() {
		$this->components();
		$this->getHiddenFields();
		$this->getExcludeFields();
		
		$this->setDataValues('content_page', []);
	}
	
	/**
	 * Initiate All Registered Plugin Components 
	 * 		=> from app\Http\Controllers\Core\Craft\Components
	 * 		=> data collection setting in config\diy.registers
	 */
	private function components() {
		if (!empty(diy_config('plugins', 'registers'))) {
			foreach (diy_config('plugins', 'registers') as $plugin) {
				$initiate = "init{$plugin}";
				$this->{$initiate}();
			}
			
			$this->setDataValues('components', diy_array_to_object_recursive($this->plugins));
		}
	}
	
	/**
	 * Set Data Value Used For Rendering Data In View
	 * 
	 * @param string $key
	 * @param string|array|integer $value
	 */
	private function setDataValues($key, $value) {
		$this->data[$key] = null;
		$this->data[$key] = $value;
	}
}