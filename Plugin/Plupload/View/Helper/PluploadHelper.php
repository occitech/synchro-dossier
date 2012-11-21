<?php

App::uses('AppHelper', 'View/Helper');

class PluploadHelper extends AppHelper {

	public $helpers = array('Html');

	protected $_jsIncludes = array(
		'https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js',
		'http://bp.yahooapis.com/2.4.21/browserplus-min.js',
		'Plupload.plupload.full.js',
		'Plupload.jquery.plupload.queue/jquery.plupload.queue.js',
	);

	protected $_cssInclude = array(
		'Plupload.jquery.plupload.queue.css'
	);

	protected $_pluploadJs = "
		<script type='text/javascript'>
			$(function() {
				$('%s').pluploadQueue({ 
					url : '%s',
					runtimes : '%s',
					max_file_size : '%s',
					chunk_size : '%s',

					flash_swf_url : '/plupload/js/plupload.flash.swf',
					silverlight_xap_url : '/plupload/js/plupload.silverlight.xap'
				})
			});
		</script>";

	protected function _includeScripts() {
		$script = '';
		foreach ($this->_jsIncludes as $path) {
			$script .= $this->Html->script($path);
		}
		return $script;
	}

	protected function _pluploadJs($options) {
		$_default = array(
			'id' => '#uploader',
			'runtimes' => 'html5,gears,flash,silverlight,browserplus',
			'max_file_size' => '10mb',
			'chunk_size' => '1mb',
			'flash_swf_url' => '/plupload/js/plupload.flash.swf',
			'silverlight_xap_url' => '/plupload/js/plupload.silverlight.xap'
		);

		$options = array_merge($_default, $options);

		$result = sprintf($this->_pluploadJs,
			$options['id'],
			$options['url'],
			$options['runtimes'],
			$options['max_file_size'],
			$options['chunk_size'],
			$options['flash_swf_url'],
			$options['silverlight_xap_url']
		);

		return $result;
	}

	public function plupload($options = array()) {
		$html = $this->_includeScripts();
		$html .= $this->_pluploadJs($options);

		return $html;
	}

	public function css() {
		$css = '';
		foreach ($this->_cssInclude as $path) {
			$css .= $this->Html->css($path);
		}
		return $css;
	}
}