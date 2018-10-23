<?php
/**
 *	see https://dev.mysql.com/doc/internals/en/user-var-event.html
 *	or https://mariadb.com/kb/en/library/replication-protocol/
 */
namespace Binlog;

use InvalidArgumentException;

class BinlogEventType {

	const
		UNKNOWN_EVENT = 0,
		START_EVENT_V3 = 1,
		QUERY_EVENT = 2,
		STOP_EVENT = 3,
		ROTATE_EVENT = 4,
		INTVAR_EVENT = 5,
		LOAD_EVENT = 6,
		SLAVE_EVENT = 7,
		CREATE_FILE_EVENT = 8,
		APPEND_BLOCK_EVENT = 9,
		EXEC_LOAD_EVENT = 10,
		DELETE_FILE_EVENT = 11,
		NEW_LOAD_EVENT = 12,
		RAND_EVENT = 13,
		USER_VAR_EVENT = 14,
		FORMAT_DESCRIPTION_EVENT = 15,
		XID_EVENT = 16,
		BEGIN_LOAD_QUERY_EVENT = 17,
		EXECUTE_LOAD_QUERY_EVENT = 18,
		TABLE_MAP_EVENT = 19,
		WRITE_ROWS_EVENTv0 = 21,
		UPDATE_ROWS_EVENTv0 = 22,
		DELETE_ROWS_EVENTv0 = 23,
		WRITE_ROWS_EVENTv1 = 24,
		UPDATE_ROWS_EVENTv1 = 25,
		DELETE_ROWS_EVENTv1 = 26,
		INCIDENT_EVENT = 27,
		HEARTBEAT_EVENT = 28,
		IGNORABLE_EVENT = 29,
		ROWS_QUERY_EVENT = 30,
		WRITE_ROWS_EVENTv2 = 31,
		UPDATE_ROWS_EVENTv2 = 32,
		DELETE_ROWS_EVENTv2 = 33,
		GTID_EVENT = 34,
		ANONYMOUS_GTID_EVENT = 35,
		PREVIOUS_GTIDS_EVENT = 36;

	static $aFormat = [
		15 => [
			'type' => 'FORMAT_DESCRIPTION_EVENT',
			'class' => __NAMESPACE__.'\Event\FormatDescription',
			'data' => [
				'binlogVersion' => 'uint<2>',
				'serverVersion' => 'str<50>',
				'time' => 'uint<4>',
				'eventHeaderLength' => 'uint<1>',
				'eventHeader' => [
					'length' => 'calcHeaderLength',
					'data' => [
						'size' => 'uint1',
					]
				],
				'checksumType' => 'uint<1>', #Checksum Algorithm Type
				'crc32' => 'uint<4>', #CRC32 4 bytes (only if checksum algo is CRC32)
			],
		],

		0xa3 => [
			'type' => 'GTID_LIST_EVENT',
			'data' => [
				'gtidListLength' => 'uint4',
				'gtidList' => [
					'length' => 'gtidListLength',
					'data' => [
						'domainId' => 'uint4',
						'serverId' => 'uint4',
						'sequence' => 'uint8'
					]
				]
			],
		],

		0xa1 => [
			'type' => 'BINLOG_CHECKPOINT_EVENT',
			'data' => [
				'filenameLength' => 'uint4',
				'filename' => 'str<:filenameLength>',
			],
		],

	/*
		0 => [
			'type' => 'UNKNOWN_EVENT',
			'data' => [],
		],
		1 => [
			'type' => 'START_EVENT_V3',
			'data' => [
				'version' => 2,
				'serverversion' => 50,
				'time' => 4
			],
		],
		2 => [
			'type' => 'QUERY_EVENT',
			'data' => [
				'slaveProxyId' => 4,
				'executionTime' => 4,
				'schemaLength' => 1,
				'errorCode' => 2,
				'statusVarsLength' => [4 => 2],
				'statusVars' => 'statusVarsLength',
			],
		],
		3 => [
			'type' => 'STOP_EVENT',
			'data' => [],
		],
		4 => [
			'type' => 'ROTATE_EVENT',
			'data' => [
				'position' => [2 => 8],
				'nextFile' => 0,
			],
		],
		5 => [
			'type' => 'INTVAR_EVENT',
			'data' => [
				'type' => 1,
				'value' => 8,
			],
		],
		6 => [
			'type' => 'LOAD_EVENT',
		],
		7 => [
			'type' => 'SLAVE_EVENT',
			'data' => [],
		],
		8 => [
			'type' => 'CREATE_FILE_EVENT',
		],
		9 => [
			'type' => 'APPEND_BLOCK_EVENT',
		],
		10 => [
			'type' => 'EXEC_LOAD_EVENT',
		],
		11 => [
			'type' => 'DELETE_FILE_EVENT',
		],
		12 => [
			'type' => 'NEW_LOAD_EVENT',
		],
		13 => [
			'type' => 'RAND_EVENT',
			'data' => [
				'seed1' => 8,
				'seed2' => 8,
			],
		],
		14 => [
			'type' => 'USER_VAR_EVENT',
			'data' => [
				'nameLength' => 4,
				'name' => 'nameLength',
				'isNull' => 1,
				'type' => 1,
				'charset' => 4,
				'valueLength' => 4,
				'value' => 'valueLength',
				'flags' => 1,
				# https://dev.mysql.com/doc/internals/en/user-var-event.html
			]
		],
		15 => [
			'type' => 'FORMAT_DESCRIPTION_EVENT',
			'data' => [
				'binlogVersion' => 'uint<2>',
				'serverVersion' => 'str<50>',
				'time' => 'uint<4>',
				'eventHeaderLength' => 'uint<1>',
				'eventHeader' => 'byte<eventHeaderLength>',
			],
		],
		16 => [
			'type' => 'XID_EVENT',
			'data' => [
				'xid' => 8,
			]
		],
		17 => [
			'type' => 'BEGIN_LOAD_QUERY_EVENT',
		],
		18 => [
			'type' => 'EXECUTE_LOAD_QUERY_EVENT',
		],
		19 => [
			'type' => 'TABLE_MAP_EVENT',
		],
		21 => [
			'type' => 'WRITE_ROWS_EVENTv0',
		],
		22 => [
			'type' => 'UPDATE_ROWS_EVENTv0',
		],
		23 => [
			'type' => 'DELETE_ROWS_EVENTv0',
		],
		24 => [
			'type' => 'WRITE_ROWS_EVENTv1',
		],
		25 => [
			'type' => 'UPDATE_ROWS_EVENTv1',
		],
		26 => [
			'type' => 'DELETE_ROWS_EVENTv1',
		],
		27 => [
			'type' => 'INCIDENT_EVENT',
			'data' => [
				'type' => 2,
				'messageLength' => 1,
				'message' => 'messageLength'
			]
		],
		28 => [
			'type' => 'HEARTBEAT_EVENT',
			'data' => [],
		],
		29 => [
			'type' => 'IGNORABLE_EVENT',
		],
		30 => [
			'type' => 'ROWS_QUERY_EVENT',
		],
		31 => [
			'type' => 'WRITE_ROWS_EVENTv2',
		],
		32 => [
			'type' => 'UPDATE_ROWS_EVENTv2',
		],
		33 => [
			'type' => 'DELETE_ROWS_EVENTv2',
		],
		34 => [
			'type' => 'GTID_EVENT',
		],
		35 => [
			'type' => 'ANONYMOUS_GTID_EVENT',
		],
		36 => [
			'type' => 'PREVIOUS_GTIDS_EVENT',
		],
	*/
	];

	static function getFormat($type) {
		if (!isset(static::$aFormat[$type])) {
			throw new InvalidArgumentException("binlog event type $type not found");
		}
		$format = static::$aFormat[$type];
		if (!isset($format['data'])) {
			throw new InvalidArgumentException("binlog event type {$format['type']} not defined");
		}
		return $format['data'];
	}

	static function getClass($type) {
		$className = __NAMESPACE__.'\\Event\\DefaultEvent';
		if (isset(static::$aFormat[$type]['class'])) {
			$className = static::$aFormat[$type]['class'];
		}
		else {
			$type = static::$aFormat[$type]['type'];
			$type = strtolower($type);
			preg_replace_callback('~_(\w)~', function($aMatch) {
				return strtoupper($aMatch[1]);
			}, $type);
			$class = __NAMESPACE__.'\\Event\\'.$type;
			if (\class_exists($class)) $className = $class;
		}
		return $className;
	}
}
