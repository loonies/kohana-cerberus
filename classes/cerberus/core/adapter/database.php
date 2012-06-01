<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A port of [Zend Framework](http://framework.zend.com/)
 * authentication component to Kohana
 *
 * @package    Cerberus
 * @category   Adapter
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Core_Adapter_Database implements Cerberus_Adapter {

	/**
	 * @var  mixed  Database query object
	 */
	protected $query = NULL;

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
	 * @var  string  Hashing algorithm
	 */
	protected $algorithm = 'sha256';

	/**
	 * @var  string  Shared secret key
	 */
	protected $key = NULL;

	/**
	 * @var  string  User's identity
	 */
	protected $identity = NULL;

	/**
	 * @var  string  Password
	 */
	protected $password = NULL;

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
		$this->key = $config['key'];

		if (isset($config['algorithm']))
		{
			// Set hashing algorithm
			$this->algorithm = $config['algorithm'];
		}

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

		if ($query === NULL)
		{
			// Use default query builder
			$query = new Database_Query_Builder_Select;
		}

		// Set a query object
		$this->query = $query;
	}

	/**
	 * Set credentials
	 *
	 * @param   string  Identity
	 * @param   string  Password
	 * @return  Cerberus_Adapter_Database
	 */
	public function credentials($identity, $password)
	{
		$this->identity = $identity;

		$this->password = $password;

		return $this;
	}

	/**
	 * Returns data from executed query
	 *
	 * @return  array
	 */
	protected function query()
	{
		return $this->query
			->select($this->columns['identity'], $this->columns['password'])
			->from($this->_table)
			->where($this->columns['identity'], '=', $this->identity)
			->limit(1)
			->execute()
			->current();
	}

	/**
	 * Implements [Cerberus_Adapter::authenticate]
	 *
	 * @return  Cerberus_Result
	 */
	public function authenticate()
	{
		$data = $this->query();

		if ($data === FALSE)
		{
			return new Cerberus_Result(Cerberus_Result::FAILURE_IDENTITY_NOT_FOUND, $this->identity);
		}

		// Get the password hash
		$password = $this->hash($this->password);

		if ($password !== $data[$this->columns['password']])
		{
			return new Cerberus_Result(Cerberus_Result::FAILURE_CREDENTIAL_INVALID, $this->identity);
		}
		elseif ($password === $data[$this->columns['password']])
		{
			return new Cerberus_Result(Cerberus_Result::SUCCESS, $this->identity);
		}

		return new Cerberus_Result(Cerberus_Result::FAILURE_GENERAL, $this->identity);
	}

	/**
	 * Returns hashed password
	 *
	 * @param   string
	 * @return  string
	 */
	public function hash($password)
	{
		return hash_hmac($this->algorithm, $password, $this->key);
	}
}
