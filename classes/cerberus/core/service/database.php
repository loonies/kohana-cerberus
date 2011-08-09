<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A port of [Zend Framework](http://framework.zend.com/)
 * authentication component to Kohana
 *
 * @package    Cerberus
 * @category   Service
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Core_Service_Database implements Cerberus_Service {

	/**
	 * @var  mixed  Database query object
	 */
	protected $_query = NULL;

	/**
	 * @var  string  Database table name
	 */
	protected $_table = 'user';

	/**
	 * @var  array  Database column names
	 */
	protected $_columns = array(
		'identity' => 'username',
		'password' => 'password',
	);

	/**
	 * @var  string  Hashing algorithm
	 */
	protected $_algorithm = 'sha256';

	/**
	 * @var  string  Shared secret key
	 */
	protected $_key = NULL;

	/**
	 * @var  string  User's identity
	 */
	protected $_identity = NULL;

	/**
	 * @var  string  Password
	 */
	protected $_password = NULL;

	/**
	 * Constructor
	 *
	 * @param   array   Configuration
	 * @param   Database_Query_Builder_Select
	 * @return  void
	 */
	public function __construct(array $config, Database_Query_Builder_Select $query = NULL)
	{
		if ( ! isset($config['key']))
			throw new Kohana_Exception('A valid secret key must be set in config');

		// Set key
		$this->_key = $config['key'];

		if (isset($config['algorithm']))
		{
			// Set hashing algorithm
			$this->_algorithm = $config['algorithm'];
		}

		if (isset($config['table']))
		{
			// Set the table name
			$this->_table = $config['table'];
		}

		if (isset($config['columns']))
		{
			// Overload column names
			$this->_columns = $config['columns'];
		}

		if ($query === NULL)
		{
			// Use default query builder
			$query = new Database_Query_Builder_Select;
		}

		// Set a query object
		$this->_query = $query;
	}

	/**
	 * Set credentials
	 *
	 * @param   string  Identity
	 * @param   string  Password
	 * @return  Cerberus_Service_Database
	 */
	public function credentials($identity, $password)
	{
		$this->_identity = $identity;

		$this->_password = $password;

		return $this;
	}

	/**
	 * Returns data from executed query
	 *
	 * @return  array
	 */
	protected function query()
	{
		return $this->_query
			->select($this->_columns['identity'], $this->_columns['password'])
			->from($this->_table)
			->where($this->_columns['identity'], '=', $this->_identity)
			->limit(1)
			->execute()
			->current();
	}

	/**
	 * Implements [Cerberus_Service::authenticate]
	 *
	 * @return  Cerberus_Result
	 */
	public function authenticate()
	{
		$data = $this->query();

		if ($data === FALSE)
		{
			return new Cerberus_Result(Cerberus_Result::FAILURE_IDENTITY_NOT_FOUND, $this->_identity);
		}

		// Get the password hash
		$password = $this->hash($this->_password);

		if ($password !== $data[$this->_columns['password']])
		{
			return new Cerberus_Result(Cerberus_Result::FAILURE_CREDENTIAL_INVALID, $this->_identity);
		}
		elseif ($password === $data[$this->_columns['password']])
		{
			return new Cerberus_Result(Cerberus_Result::SUCCESS, $this->_identity);
		}

		return new Cerberus_Result(Cerberus_Result::FAILURE_GENERAL, $this->_identity);
	}

	/**
	 * Returns hashed password
	 *
	 * @param   string
	 * @return  string
	 */
	public function hash($password)
	{
		return hash_hmac($this->_algorithm, $password, $this->_key);
	}
}
