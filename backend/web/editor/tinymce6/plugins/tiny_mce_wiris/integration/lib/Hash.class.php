<?php

class Hash implements IteratorAggregate{
	public function __construct() {
		if(!php_Boot::$skip_constructor) {
		$this->h = array();
	}}

	#[\ReturnTypeWillChange]
	public function getIterator() {
		return $this->iterator();
	}
	public function toString() {
		$s = "{";
		$it = $this->keys();
		$Â�»it = $it;
		while($Â�»it->hasNext()) {
			$i = $Â�»it->next();
			$s .= $i;
			$s .= " => ";
			$s .= Std::string($this->get($i));
			if($it->hasNext()) {
				$s .= ", ";
			}
		}
		return $s . "}";
	}
	public function iterator() {
		return new _hx_array_iterator(array_values($this->h));
	}
	public function keys() {
		return new _hx_array_iterator(array_keys($this->h));
	}
	public function remove($key) {
		if(array_key_exists($key, $this->h)) {
			unset($this->h[$key]);
			return true;
		} else {
			return false;
		}
	}
	public function exists($key) {
		return array_key_exists($key, $this->h);
	}
	public function get($key) {
		if(array_key_exists($key, $this->h)) {
			return $this->h[$key];
		} else {
			return null;
		}
	}
	public function set($key, $value) {
		$this->h[$key] = $value;
	}
	public $h;
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->Â�»dynamics[$m]) && is_callable($this->Â�»dynamics[$m]))
			return call_user_func_array($this->Â�»dynamics[$m], $a);
		else if('toString' == $m)
			return $this->__toString();
		else
			throw new HException('Unable to call Â«'.$m.'Â�»');
	}
	function __toString() { return $this->toString(); }
}
