<?php
class Workmanagement extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'workmanagement';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'activeworktimes' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'begin' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'end' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'point' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'reward' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'is_delete' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'admins' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'username' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 120, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'role' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'created' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'is_delete' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'rooms' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'room_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'address' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'note' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'is_delete' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'created' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'users' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'character_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'username' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'japanese_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 120, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'transfer_account' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'created' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'is_delete' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'worktimes' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'room_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'workstatus' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false),
					'start_time' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'end_time' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'report' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'activeworktimes', 'admins', 'rooms', 'users', 'worktimes'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
