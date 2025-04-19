<?php

class com_wiris_std_system_HttpProxyAuth {
	public function __construct() {
		;
	}
	public $pass;
	public $user;
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
	function __toString() { return 'com.wiris.std.system.HttpProxyAuth'; }
}
