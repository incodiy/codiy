<?php
namespace Incodiy\Codiy\Library\Components\Charts;

use Incodiy\Codiy\Library\Components\Charts\Canvas\Builder;
use Incodiy\Codiy\Library\Components\Form\Elements\Tab;

/**
 * Created on May 23, 2023
 * 
 * Time Created : 4:29:05 PM
 *
 * @filesource  Objects.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com
 */
class Objects extends Builder {
	use Tab;
	
	public $elements      = [];
	public $element_name  = [];
	public $columns       = [];
	public $labels        = [];
	public $relations     = [];
	public $connection;
	
	private $params       = [];
	private $setDatatable = true;
	private $chartLibrary = 'highcharts';
	
	/**
	 * --[openTabHTMLForm]--
	 */
	private $opentabHTML  = '--[openTabHTMLForm]--';
	
	public function __construct() {
		$this->element_name['table']    = $this->chartLibrary;
		$this->variables['table_class'] = 'table animated fadeIn table-striped table-default table-bordered table-hover dataTable repeater display responsive nowrap';
	}
	
	public function method($method) {
		$this->method = $method;
	}
	
	private function draw($initial, $data = []) {
		$this->elements[] = $initial;
	}
	
	public function render($object) {
		$tabObj = "";
		if (true === is_array($object)) $tabObj = implode('', $object);
		
		if (true === diy_string_contained($tabObj, $this->opentabHTML)) {
			return $this->renderTab($object);
		} else {
			return $object;
		}
	}
	
	public function column($source, $fieldsets = [], $format, $category = null, $group = null, $order = null) {
		dd($source, $fieldsets, $format, $category, $group, $order);
		return $this->draw('column chart');
	}
}