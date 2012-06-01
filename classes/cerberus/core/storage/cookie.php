<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A port of [Zend Framework](http://framework.zend.com/)
 * authentication component to Kohana
 *
 * @package    Cerberus
 * @category   Storage
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Core_Storage_Cookie implements Cerberus_Storage {

	/**
	 * @var  string
	 */
	protected $_content = NULL;

	/**
	 * @var  string  Cookie name
	 */
	protected $_name = 'cerberus';

	/**
	 * @var  int  Cookie lifetime
	 */
	protected $_lifetime = Date::WEEK;

	/**
	 * Creates a new Cerberus_Storage_Cookie instance
	 *
	 * @param   array   Configuration
	 * @return  void
	 */
	public function __construct(array $config = NULL)
	{
		if (isset($config['lifetime']))
		{
			$this->_lifetime = $config['lifetime'];
		}

		if (isset($config['name']))
		{
			$this->_name = $config['name'];
		}
	}

	/**
	 * Implements [Cerberus_Storage::is_empty]
	 *
	 * @return  bool
	 */
	public function is_empty()
	{
		return $this->read() === NULL;
	}

	/**
	 * Implements [Cerberus_Storage::read]
	 *
	 * @return  string
	 */
	public function read()
	{
		if ($this->_content === NULL)
		{
			$this->_content = Cookie::get($this->_name);
		}

		return $this->_content;
	}

	/**
	 * Implements [Cerberus_Storage::write]
	 *
	 * @param   string
	 * @return  void
	 */
	public function write($content)
	{
		$this->_content = $content;

		Cookie::set($this->_name, $content, $this->_lifetime);
	}

	/**
	 * Implements [Cerberus_Storage::clear]
	 *
	 * @return  void
	 */
	public function clear()
	{
		$this->_content = NULL;

		Cookie::delete($this->_name);
	}
}