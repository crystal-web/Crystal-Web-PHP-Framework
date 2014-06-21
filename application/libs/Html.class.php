<?php
class Html {
private $xhtml;
private $inline_tags = array ("base", "link", "meta", "hr", "br", "img", "col", "input");
private $open_tags = array();
private $content     = '';

	public function __construct($xhtml = false) {
		$this->xhtml = $xhtml;
	}
	
	public function __call($method, $args) {
		$tag        = $method;
		$attributes = isset($args[0]) ? $args[0] : array();
		$text       = isset($args[1]) ? $args[1] : '';
		
		if(!is_array($attributes) && !$text) {
			$text = $attributes;
			$attributes = array();
		}
	
		$this->content .= '<';
		$this->content .= $tag;
		$this->content .= $this->generateAttributes($attributes);
		
		if (in_array($tag, $this->inline_tags)) {
			if ($this->xhtml) {
				$this->content .= ' />';
			} else {
				$this->content .= '>';
			}
		} else {
			$this->content .= '>';
			$this->open_tags[] = $tag;
		}
		if ($text) {
			$this->content .= $text;
		}
		return $this;
	}
	
	public function end() {
		$this->content .= '</'.array_pop($this->open_tags).'>';
		return $this;
	}
	
	public function append($string) {
		$this->content .= $string;
		return $this;
	}
	
	public function comment($string) {
		$this->content .= PHP_EOL . '<!-- '.$string.' -->' . PHP_EOL;
		return $this;
	}
	
	private function generateAttributes($attributes = array()) {
		$html = '';
		foreach($attributes as $key => $value) {
			$html .= ' '.$key.'="'.$value.'"';
		}
		return $html;
	}
	
	public function __toString() {
		return $this->content;
	}
}