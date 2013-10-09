<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Keys Controller
 *
 * This is a basic Key Management REST controller to make and delete keys.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php
require(APPPATH.'/libraries/REST_Controller.php');

class Key
{
	protected $methods = array(
		'index_put' => array('level' => 10, 'limit' => 10),
		'index_delete' => array('level' => 10),
		'level_post' => array('level' => 10),
		'regenerate_post' => array('level' => 10),
	);

	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * Key Create
	 *
	 * Insert a key into the database.
	 *
	 * @access	public
	 * @return	void
	 */
	public function create($level, $ignore_limits, $is_private_key = 0)
    {

		// Build a new key
		$key = self::_generate_key();

		// If no key level provided, give them a rubbish one
		$level = $level ? $level : 1;
		$ignore_limits = $ignore_limits ? $ignore_limits : 1;

		// Insert the new key
		// Is private key check whether or not we should register the ip address of the user and compare it eveyr time to make sure
		//the request is coming from the same device. Is it necessary ?
		if (self::_insert_key($key, array('level' => $level, 'ignore_limits' => $ignore_limits, 'is_private_key' => $is_private_key)))
		{
			return $key;
		}

		else
		{
			return FALSE;
		}
    }

	// --------------------------------------------------------------------

	/**
	 * Key Delete
	 *
	 * Remove a key from the database to stop it working.
	 *
	 * @access	public
	 * @return	void
	 */
	public function delete($key)
    {
		$key = $key;

		// Does this key even exist?
		if ( ! self::_key_exists($key))
		{
			// NOOOOOOOOO!
			return FALSE;
		}

		// Kill it
		self::_delete_key($key);

		// Tell em we killed it
		return TRUE;
    }

	// --------------------------------------------------------------------

	/**
	 * Update Key
	 *
	 * Change the level
	 *
	 * @access	public
	 * @return	void
	 */
	public function level($key, $level)
    {
		$key = $key;
		$new_level = $level;

		// Does this key even exist?
		if ( ! self::_key_exists($key))
		{
			// NOOOOOOOOO!
			return FALSE;
		}

		// Update the key level
		if (self::_update_key($key, array('level' => $new_level)))
		{
			return TRUE;
		}

		else
		{
			return FALSE;
		}
    }

	// --------------------------------------------------------------------

	/**
	 * Update Key
	 *
	 * Change the level
	 *
	 * @access	public
	 * @return	void
	 */
	public function suspend($key)
    {
		$key = $key;

		// Does this key even exist?
		if ( ! self::_key_exists($key))
		{
			// NOOOOOOOOO!
			return FALSE;
		}

		// Update the key level
		if (self::_update_key($key, array('level' => 0)))
		{
			return TRUE;
		}

		else
		{
			return FALSE;
		}
    }

	// --------------------------------------------------------------------

	/**
	 * Regenerate Key
	 *
	 * Remove a key from the database to stop it working.
	 *
	 * @access	public
	 * @return	void
	 */
	public function regenerate($key)
    {
		$old_key = $key;
		$key_details = self::_get_key($old_key);

		// The key wasnt found
		if ( ! $key_details)
		{
			// NOOOOOOOOO!
			return FALSE;
		}

		// Build a new key
		$new_key = self::_generate_key();

		// Insert the new key
		if (self::_insert_key($new_key, array('level' => $key_details->level, 'ignore_limits' => $key_details->ignore_limits)))
		{
			// Suspend old key
			self::_update_key($old_key, array('level' => 0));

			return TRUE;
		}

		else
		{
			return FALSE;
		}
    }

	// --------------------------------------------------------------------

	/* Helper Methods */

	private function _generate_key()
	{
		$this->CI->load->helper('security');
		do
		{
			$salt = do_hash(time().mt_rand());
			$new_key = substr($salt, 0, config_item('rest_key_length'));
		}

		// Already in the DB? Fail. Try again
		while (self::_key_exists($new_key));

		return $new_key;
	}

	// --------------------------------------------------------------------

	/* Private Data Methods */

	private function _get_key($key)
	{
		$this->CI->load->library('doctrine');
		$em = $this->CI->doctrine->em;
		return $em->getRepository( 'Entity\Key' )->findOneBy( array( 'key' => $key ) );

		// return $this->db->where(config_item('rest_key_column'), $key)->get(config_item('rest_keys_table'))->row();
	}

	// --------------------------------------------------------------------

	private function _key_exists($key)
	{
		$this->CI->load->library('doctrine');
		$em = $this->CI->doctrine->em;

		$key_object = $em->getRepository( 'Entity\Security_Key' )->findOneBy( array( 'value' => $key ) );

		if( $key_object )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		// return $this->db->where(config_item('rest_key_column'), $key)->count_all_results(config_item('rest_keys_table')) > 0;
	}

	// --------------------------------------------------------------------

	private function _insert_key($key_value, $data)
	{
		$this->CI->load->library('doctrine');

		$key = new Entity\Security_Key;
		$key->setValue( $key_value );
		$key->setLevel( $data['level'] );
		$key->setIgnoreLimits( $data['ignore_limits'] );
		$key->setIsPrivateKey( $data['is_private_key'] );
		$key->setIpAddresses( $_SERVER['REMOTE_ADDR'] );

		$em = $this->CI->doctrine->em;
		$em->persist( $key );

		try
		{
			$em->flush();
		}
		catch(\PDOException $e)
		{
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	private function _update_key( $key, $data )
	{
		$this->CI->load->library('doctrine');
		$em = $this->CI->doctrine->em;

		$this->load->library('doctrine');
		$em = $this->CI->doctrine->em;
		$key_object  = $em->getRepository( 'Entity\Key' )->findOneBy( array( 'key' => $key ) );

		$key_object->setUsername('wildlyinaccurate');

		return $this->db->where(config_item('rest_key_column'), $key)->update(config_item('rest_keys_table'), $data);
	}

	// --------------------------------------------------------------------

	private function _delete_key( $key )
	{
		$this->CI->load->library('doctrine');
		$em = $this->CI->doctrine->em;
		$key_object  = $em->getRepository( 'Entity\Key' )->findOneBy( array( 'key' => $key ) );

		$em->remove( $key_object );
		try
		{
			$em->flush();
		}
		catch(\PDOException $e)
		{
			return FALSE;
		}

		return TRUE;

		// return $this->db->where(config_item('rest_key_column'), $key)->delete(config_item('rest_keys_table'));
	}
}