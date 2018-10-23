<?php
namespace Binlog\Event;

class FormatDescription extends DefaultEvent {

	function getCalcHeaderLength() {
		return $this->size - (2 +50 +4 +1) -1 -4;
	}

	function setTime($timestamp) {
		$this->oTime = new \DateTime('@'.$timestamp);
	}
}
