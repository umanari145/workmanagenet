<?php
class AddAcountStatus extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_acount_status';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'activeworktimes' => array(
					'account_statues' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 6, 'unsigned' => false, 'after' => 'reward'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'activeworktimes' => array('account_statues'),
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
