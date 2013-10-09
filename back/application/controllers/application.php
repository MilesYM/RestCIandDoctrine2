<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
		$this->load->library('key');
    }

	public function index()
	{
		$this->login();
	}

	public function login()
	{
		//Form Manage
		$this->load->library( 'form_validation' );
		$this->form_validation->set_rules( 'email', 'email', 'trim|required|valid_email|callback__check_login' );
		$this->form_validation->set_rules( 'pwd', 'password', 'trim|required' );
		
		if( $this->form_validation->run() )
		{
		// the form has successfully validated
			$email = strtolower( $this->input->post( 'email' ) );

			//Use Doctrine to retrieve user by Pwd
			$em = $this->doctrine->em;
			$pwd = hash( 'sha256', $this->input->post( 'pwd' ) . $email );
			$user = $em->getRepository( 'Entity\User' )->findOneBy( array( 'email' => $email, 'password' => $pwd ) );

			if( $user )
			{
				//Everything is Fine !
					//We generate a key
				$key = $this->key->create(10, 1);

				//We retrieve the entity freshly generated
				$key_entity = $em->getRepository( 'Entity\Security_Key' )->findOneBy( array( 'value' => $key ) );

				//We associate it to the user:
				$user->setPrivateKey( $key_entity );

				// We can now persist this entity:
				try
				{
					$em->persist($user);
					$em->flush();
				}
				catch(\PDOException $e)
				{
					// Error When Persisting the Entity !!
					$array = array(
				                   'errors' => "<p>Server Error</p>",
				                   'logged_in' => FALSE
				               );

					$this->output
					     ->set_content_type( 'application/json' )
					     ->set_output( json_encode( $array ) );

					return FALSE;
				}

				// End persisting entity / attach key-user


				// Everything is fine, send the data back ! 
				$user_array = array(
									'id' => $user->getId(),
									'name' => $user->getUsername(),
									'key' => $key
								);

				$array = array(
			                   'user' => $user_array,
			                   'logged_in' => TRUE
			               );

				$this->output
				     ->set_content_type( 'application/json' )
				     ->set_output( json_encode( $array ) );

				return TRUE;
			}

			//We didn't fin any matches
			$this->output
			     ->set_content_type( 'application/json' )
			     ->set_output( json_encode( array( 	'errors' => "<p>Wrong password / email combination</p>",
			     								   	'logged_in' => FALSE 
			     								) ) );
			return FALSE;

		}

		//Error in the Form validation
		$this->output
		     ->set_content_type( 'application/json' )
		     ->set_output( json_encode( array( 	'errors' => validation_errors(),
		     								   	'logged_in' => FALSE 
		     								  ) ) );
		return FALSE;
	}
	
	public function register()
	{
		//Gestion du Formulaire
		$this->load->library( 'form_validation' );
		$this->form_validation->set_rules( 'username', 'Username', 'trim|xss_clean|required|min_length[3]|max_length[30]|callback__check_unique_username' );
		$this->form_validation->set_rules( 'email', 'Email', 'trim|xss_clean|required|valid_email|max_length[50]|callback__check_unique_email' );
		$this->form_validation->set_rules( 'pwd', 'Password', 'trim|xss_clean|required|max_length[30]|min_length[8]' );
		
		if( $this->form_validation->run() )
		{
			//Use Doctrine to register a new user
			$em = $this->doctrine->em;

			$user = new Entity\User;
			$user->setUsername( $this->input->post( 'username' ) );
			$user->setEmail( strtolower( $this->input->post( 'email' ) ) );
			$user->setPassword( $this->input->post( 'pwd' ) );

            // $user->setPicture( $this->input->post( 'picture' ) );
            $user->setPicture( "/picture/user.png" );

			// We can now persist this entity:

			try
			{
				$em->persist( $user );
				$em->flush();
			}
			catch(\PDOException $e)
			{
				// Error When Persisting the Entity !!
				$array = array(
			                   'errors' => "<p>Server Error</p>",
			                   'logged_in' => FALSE
			               );

				$this->output
				     ->set_content_type( 'application/json' )
				     ->set_output( json_encode( $array ) );

				return FALSE;
			}

			//Everything is fine
			$array = array(
		                   'logged_in' => FALSE,
		                   'success' => TRUE,
		               );

			$this->output
			     ->set_content_type( 'application/json' )
			     ->set_output( json_encode( $array ) );

			return TRUE;

		}
		//Error in the Form validation
		$this->output
		     ->set_content_type( 'application/json' )
		     ->set_output( json_encode( array( 	'errors' => validation_errors(),
		     								   	'logged_in' => FALSE 
		     								  ) ) );
		return FALSE;
	}
	
	public function logout()
	{
		//We Remove the Key entity and set the User's Key attribute to null
		$em = $this->doctrine->em;

		if( $this->input->post( 'key' ) ){

			//We retrieve the entity freshly generated
			$key_entity = $em->getRepository( 'Entity\Security_Key' )->findOneBy( array( 'value' => $this->input->post( 'key' ) ) );
			try
			{
				$em->remove($key_entity);
				$em->flush();
			}
			catch(\PDOException $e)
			{
				// Error When Persisting the Entity !!
				$array = array(
			                   'errors' => "<p>Server Error</p>",
			                   'logged_in' => TRUE
			               );

				$this->output
				     ->set_content_type( 'application/json' )
				     ->set_output( json_encode( $array ) );

				return FALSE;
			}

			//We set up logged out to true
			//Error in the Form validation
			$this->output
			     ->set_content_type( 'application/json' )
			     ->set_output( json_encode( array(
			     									'success' => TRUE,
			     									'logged_in' => FALSE,
			     								  ) ) );
		}
		else
		{
			//We set up logged out to true
			//Error in the Form validation
			$this->output
			     ->set_content_type( 'application/json' )
			     ->set_output( json_encode( array(
			     									'success' => TRUE,
			     									'logged_in' => FALSE,
			     								  ) ) );
		}

	}
	
	public function _check_login( $email )
	{

		$email = strtolower( $email );
		if( $this->input->post( 'pwd' ) )
		{
			$pwd = $this->input->post( 'pwd' );

			//Use Doctrine to retrieve user by email
			$em = $this->doctrine->em;
			$user = $em->getRepository( 'Entity\User' )->findOneBy( array( 'email' => $email ) );
			if( $user ) return true;
		}
		
		$this->form_validation->set_message( '_check_login', 'This Email is not registered' );
		return false;
	}
	
	public function _check_unique_username( $username )
	{
		//we lower the username as we don't want to have several name with different case
		$username = strtolower( $username );

		//Use Doctrine to retrieve user by username
		$em = $this->doctrine->em;
		$user = $em->getRepository( 'Entity\User' )->findOneBy( array( 'username' => $username ) );
		if( !$user )
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message( '_check_unique_username', 'This Username has already been registered. Please pick up another one.' );
			return false;
		}
	}
	
	public function _check_unique_email( $email )
	{
		//Just in case, we lower the email
		$email = strtolower( $email );

		//Use Doctrine to retrieve user by email
		$em = $this->doctrine->em;
		$user = $em->getRepository( 'Entity\User' )->findOneBy( array( 'email' => $email ) );
		if( !$user )
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message( '_check_unique_email', 'This Email has already been registered. Please pick up another one.' );
			return false;
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
