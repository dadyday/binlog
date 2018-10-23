<?php
namespace Binlog;

class BinlogReader {

    protected
        $hStream,
        $raw,
        $pos;

    function __construct($hStreamOrString) {
        $this->hStream = is_resource($hStreamOrString) ? $hStreamOrString : null;
        $this->raw = is_string($hStreamOrString) ? $hStreamOrString : null;
        $this->pos = 0;
    }

    function setPos($pos) {
        $this->pos = $pos;
    }

    function getPos() {
        return $this->pos;
    }

    function read($length, $from = null) {
        if ($from) {
			if ($this->hStream) fseek($this->hStream, $from);
			$this->pos = $from;
		}
		if ($this->hStream) {
            $data = $length ? fread($this->hStream, $length) : '';
        }
        else {
            $data = substr($this->raw, $this->pos, $length);
        }
        $this->pos += strlen($data);
        return $data;
    }


    function readInto($oInst, array $aFormat) {
		foreach ($aFormat as $param => $type) {
			if (is_array($type)) {
                $prop = $type['length'];
                $class = isset($type['class']) ? $type['class'] : 'stdClass';
                $l = $oInst->$prop;
                $value = [];
                for ($l; $l; $l--) {
                    $oItem = new $class();
                    $this->readInto($oItem, $type['data']);
                    $value[] = $oItem;
                }
			}
            else {
                if (preg_match('~^(\w+)<:(\w+)>$~', $type, $aMatch)) {
                    list(, $type, $prop) = $aMatch;
                    if (!isset($oInst->$prop)) {
                        throw new \Exception("bin length $prop not found");
                    }
                    $type = $type.'<'.$oInst->$prop.'>';
                }
                if (is_null($type)) continue;

                $size = Bin::size($type);
                $data = $this->read($size);
                $value = Bin::parse($type, $data);
            }
            $oInst->$param = $value;
		}
	}

}
