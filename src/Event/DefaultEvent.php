<?php
namespace Binlog\Event;

use Binlog\BinlogEventType as Type;
use Binlog\BinlogReader as Reader;
use Binlog\Bin;
use Binlog\GetSetTrait;

class DefaultEvent {

	use GetSetTrait;

	protected
		$type,
		$size;

	function __construct($type, $rawData) {
		$this->type = $type;
		$this->size = strlen($rawData);

		$aFormat = Type::getFormat($this->type);

		$oRd = new Reader($rawData);
		$oRd->readInto($this, $aFormat);
	}
}
