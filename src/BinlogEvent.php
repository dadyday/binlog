<?php
namespace Binlog;

use Binlog\BinlogEventType as Type;
use Binlog\BinlogReader as Reader;

class BinlogEvent {

	use GetSetTrait;

	protected static $aHeaderFormat = [
		'timestamp' => 'uint<4>',
		'eventType' => 'uint<1>',
		'serverId' => 'uint<4>',
		'eventSize' => 'uint<4>',
		'logPos' => 'uint<4>',
		'flags' => 'uint<2>',
	];

	protected
		$oReader,
		$raw;

	protected
		$oTime,
		$eventType,
		$serverId,
		$eventPos,
		$eventSize,
		$nextPos,
		$flags;

	function __construct(Reader $oReader) {
		$this->oReader = $oReader;
	}

	function setTimestamp($value) {
		$this->oTime = new \DateTime('@'.$value);
	}

	function setLogPos($value) {
		$this->nextPos = $value;
	}

	function init() {
		$this->eventPos = $this->oReader->getPos();
		$this->oReader->readInto($this, static::$aHeaderFormat);

		$headSize = $this->oReader->getPos() - $this->eventPos;
		$this->raw = $this->oReader->read($this->eventSize - $headSize);
	}

	function getData() {
		$class = Type::getClass($this->eventType);
		$oData = new $class($this->eventType, $this->raw);
		return $oData;
	}
}
