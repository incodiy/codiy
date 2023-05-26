<?php
namespace Incodiy\Codiy\Library\Components\Charts\Canvas;

use Incodiy\Codiy\Library\Components\Charts\Canvas\Scripts;

/**
 * Created on May 23, 2023
 * 
 * Time Created : 4:31:19 PM
 *
 * @filesource  Builder.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com
 */
class Builder {
	use Scripts;
	
	public function __construct() {}
	
	public $model;
	
	protected $canvas = [];
	public function chartCanvas($identity = []) {
		$chartURI           = url(diy_current_route()->uri) . "?renderCharts=true";
		$dataAjax           = [];
		$dataAjax['info']   = $this->identities[$identity];
		$dataAjax['params'] = $this->params[$identity];
		$methodValues       = json_encode([$this->chartPostData => diy_encrypt(json_encode($dataAjax))]);
		
		$htmlCanvas         = "<div id=\"{$identity}\">IncoDIY Chart Canvas</div>";
		$chartscripts       = '<script type="text/javascript">';
		$chartscripts      .= $this->ajaxProcess($this->identities[$identity]['string'], $chartURI, $methodValues, $this->chartPostData);
		
		$params = [];
		if (!empty($this->params[$identity])) {
			$params = $this->params[$identity];
		}
		
		$attributes = [];
		if (!empty($this->attributes[$identity])) {
			$attributes = $this->attributes[$identity];
		}
		
		$chartscripts      .= $this->canvascipt($identity, $this->identities[$identity]['string'], $params, $attributes);
		$chartscripts      .= '</script>';
		$canvas             = $htmlCanvas . $chartscripts;
		
		return $this->draw($canvas);
	}
}