<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract database query interface for the database adapter
 *
 * @package    Cerberus
 * @category   Adapter/Database
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011-2012, Miodrag Tokić
 * @license    New BSD License
 */
abstract class Cerberus_Core_Adapter_Database_Query {

	/**
	 * @var  string  Database table name
	 */
	protected $table = 'user';

	/**
	 * @var  array  Database column names
	 */
	protected $columns = array(
		'identity' => 'username',
		'password' => 'password',
	);

	/**
	 * Sets configuration options
	 *
	 * @param   array   Configuration options
	 * @return  void
	 */
	public function __construct(array $config = array())
	{
		if (isset($config['table']))
		{
			// Set the table name
			$this->table = $config['table'];
		}

		if (isset($config['columns']))
		{
			// Overload column names
			$this->columns = $config['columns'];
		}
	}

	/**
	 * Returns password for a given identity
	 *
	 * @param   string  Identity to search for
	 * @return  string  Password of identity or NULL in case identity not found
	 */
	public abstract function find($identity);
}
