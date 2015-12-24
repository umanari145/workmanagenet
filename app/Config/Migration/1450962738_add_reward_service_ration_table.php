<?php
class AddRewardServiceRationTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_reward_service_ration_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'activeworktimes' => array(
					'account_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'character_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'account_id'),
					'chatgirl_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'user_id'),
					'chat_group' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'chatgirl_name'),
					'production_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'chat_group'),
					'production_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'production_id'),
					'manager_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'production_name'),
					'manager_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'manager_id'),
					'live_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'manager_name'),
					'handlename' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'live_id'),
					'service_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'reward'),
					'account_statues' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 6, 'unsigned' => false, 'after' => 'service_name'),
				),
				'users' => array(
					'chatgirl_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'after' => 'id'),
				),
			),
			'create_table' => array(
				'reserves' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'room_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'start_reserve_date' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'end_reserve_date' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'note' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'created' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'services' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'service_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'ratio' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'created' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'is_delete' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
			'drop_field' => array(
				'users' => array('character_id'),
			),
		),
		'down' => array(
			'drop_field' => array(
				'activeworktimes' => array('account_id', 'character_id', 'chatgirl_name', 'chat_group', 'production_id', 'production_name', 'manager_id', 'manager_name', 'live_id', 'handlename', 'service_name', 'account_statues'),
				'users' => array('chatgirl_id'),
			),
			'drop_table' => array(
				'reserves', 'services'
			),
			'create_field' => array(
				'users' => array(
					'character_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
				),
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
