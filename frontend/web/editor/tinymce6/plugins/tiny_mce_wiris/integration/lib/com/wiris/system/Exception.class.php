<?php

class com_wiris_system_Exception {
	public function __construct($message, $cause = null) {
		if(!php_Boot::$skip_constructor) {
		$this->message = $message;
	}}
	public function getMessage() {
		return $this->message;
	}
	public $message;
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->ÂÂ»dynamics[$m]) && is_callable($this->ÂÂ»dynamics[$m]))
			return call_user_func_array($this->ÂÂ»dynamics[$m], $a);
		else if('toString' == $m)
			return $this->__toString();
		else
			throw new HException('Unable to call Â«'.$m.'ÂÂ»');
	}
	function __toString() { return 'com.wiris.system.Exception'; }
}
