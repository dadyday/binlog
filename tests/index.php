<?php
require_once __DIR__.'/cfg.php';

#header('Content-type: text/plain');

$oRd = new Binlog\BinlogReader(__DIR__.'/data/mysql-bin.000001');
$oBin = new Binlog\BinlogFile(__DIR__.'/data/mysql-bin.000001');
$oBin->init();



echo '<pre>';
echo dumpHex($oRd->read(1024));

$oEvent = $oBin->getEvent();
dump($oEvent);

$oData = $oEvent->getData();
dump($oData);

$oEvent = $oBin->getEvent();
dump($oEvent);

$oData = $oEvent->getData();
dump($oData);

$oEvent = $oBin->getEvent();
dump($oEvent);

$oData = $oEvent->getData();
dump($oData);


#echo dumpHex($oBin->read(0, 4));
