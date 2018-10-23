<?php
namespace Binlog;

class BinlogFile {

	const
        FILE_HEADER = "\xFEbin";


    protected
        $name,
        $oReader;

    function __construct($file) {
		$hStream = fopen($file, 'r');
        $this->oReader = new BinlogReader($hStream);
        $this->name = basename($file);
    }

    function init() {
        $header = $this->oReader->read(4, 0);
        if ($header != static::FILE_HEADER) {
            throw new \Exception("binlog file $this->name is not a binlog");
        }
        return true;
    }

    function getEvent() {
        $oEvent = new BinlogEvent($this->oReader);
        $oEvent->init();
		#$this->oReader->setPos($oEvent->nextPos);
        return $oEvent;
    }


}
