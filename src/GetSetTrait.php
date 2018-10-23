<?php
namespace Binlog;

trait GetSetTrait {

	function __isset($name) {
		return
			property_exists($this, $name)
		 	|| method_exists($this, 'get'.ucfirst($name))
			|| method_exists($this, 'set'.ucfirst($name));
	}

	function __get($name) {
		$func = 'get'.ucfirst($name);
		if (method_exists($this, $func)) {
			return $this->$func();
		}
		else if (property_exists($this, $name)) {
			return $this->$name;
		}
		throw new \InvalidArgumentException("property $name not found");
	}

	function __set($name, $value) {
		if (isset($name)) return $this->__setter($name, $value);
		throw new \InvalidArgumentException("property $name not found");
	}

	function __setter($name, $value) {
		$func = 'set'.ucfirst($name);
		if (method_exists($this, $func)) {
			return $this->$func($value);
		}
		else if (property_exists($this, $name)) {
			return $this->$name = $value;
		}
		return $this->$name = $value;
	}
}
