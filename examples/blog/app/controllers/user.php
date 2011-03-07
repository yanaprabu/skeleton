<?php
#include_once 'A/User/Session.php';
#include_once 'A/Model/Form.php';

class user extends A_Controller_Action {

	public function login($locator) {

		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();		// controller and view use session
		$session->set('foo', 'bar');
		
		$form = new A_Model_Form();
		$field = new A_Model_Form_Field('username');
		$field->addRule(new A_Rule_Notnull('username', 'Username required'));
		$form->addField($field);
		$field = new A_Model_Form_Field('password');
		$field->addRule(new A_Rule_Notnull('password', 'Password required'));
		$form->addField($field);
		
		$errmsg = '';

		// If username and password valid and isPost
		if($form->isValid($this->request)){ 
			
			$model = $this->_load('app')->model('users');
			$userdata = $model->login($form->get('username'), $form->get('password'), $locator->get('Config')->get('SITESALT'));

			if ($userdata) {	// user record matching userid and password found
				unset($userdata['password']);		// don't save passwords in the session
				$user->login($userdata);
				$this->_redirect($locator->get('Config')->get('BASE') . 'user/login/');	// build redirect URL back to this page
			} else {
				$errmsg = $model->loginErrorMsg();
			}
		} elseif($form->isSubmitted()){		// submitted form has errors
			$errmsg =  $form->getErrorMsg(', ');
		}
		
		$template = $this->_load()->template('user/login');
		$template->set('errmsg', $errmsg);
		$template->set('username', $form->get('username'));
		$template->set('user', $user);
		
		$this->response->set('maincontent', $template);
	}
	
	public function logout($locator) {
		
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();
		
		if ($user->isLoggedIn()) {	// user record matching userid and password found
			$user->logout();
		}
		
		$this->_redirect($locator->get('Config')->get('BASE') . 'user/login/');	// build redirect URL back to this page
	}
	
	public function register($locator){
		
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();	
		$request = $this->request;	
		$messages = array();
		
		if($request->isPost()){
			
			$usermodel = $this->_load('app')->model('users');
			$usermodel->addRule(new A_Rule_Match('passwordagain', 'password', 'Fields password and passwordagain do not match'));
			$usermodel->addRule(new A_Rule_Regexp('/agree/', 'tos', 'Dont agree with the terms of service?'), 'tos'); 
          	// Exclude some fields not needed in the validation of the model
         	//   $usermodel->excludeRules(array('id','firstname','lastname','active','access'));
				
			// Inlcude only rules for these fields
			$usermodel->includeRules(array('username', 'password', 'passwordagain', 'email', 'tos'));
				
			if(!$usermodel->isValid($request))
			{
				$messages[] = $usermodel->getErrorMsg("</li>\n<li>");
				$this->response->setPartial('maincontent', 'user/register/registerForm', array('messages' => $messages));
			} 
			else 
			{
				if($usermodel->isUsernameAvailable($request->get('username')))
				{
					if($usermodel->isEmailAvailable($request->get('email')))
					{
						// Create activation key
						$actkey = $usermodel->createActivationkey();
						// Create a random user salt
						$usersalt = uniqid(mt_rand().time(),true);
						// Insert user data in db
						$usermodel->insertUser( $request->get('username'), 
												$request->get('password'), 
												$request->get('email'), 
												$actkey, 
												$usersalt,
												$locator->get('Config')->get('SITESALT')
												);
						// Send confirmation email
						$activationlink = $locator->get('Config')->get('BASE') . 'user/activate?id=' . $actkey;
						$this->mailActivationMessage($request->get('email'), $activationlink);
						// Get Template SuccesfulRegistration
						$this->response->setPartial('maincontent', 'user/register/success', array( 'email'=>$request->get('email')));
					} 
					else 
					{
						// Another account for this email adress exists, get Template email adress already in database
						$this->response->setPartial('maincontent', 'user/register/emailTakenForm');
					}
				} 
				else 
				{
					if($usermodel->usernameMatchesEmail($request->get('username'), $request->get('email')))
					{ 
						if($usermodel->isAccountActivated($request->get('username'), $request->get('email')))
						{
							if($usermodel->isPasswordCorrect($request->get('username'), $request->get('password')))
							{
								// Login the user
								$usermodel->login($request->get('username'), $request->get('password'));
								// Get Template you have been logged in
								$this->response->setPartial('maincontent', 'user/register/signedin');
							} 
							else 
							{
								// Password was wrong. Get Template LoginForm
								$this->response->setPartial('maincontent', 'user/register/loginForm');
							}
						} 
						else 
						{
							// Get Template AccountNotYetActivated
							$this->response->setPartial('maincontent', 'user/register/activate');
						}
					} 
					else 
					{
						// Get Template username already taken
						$this->response->setPartial('maincontent', 'user/register/usernameUnavailable',array('username'=> $request->get('username')));
					}
				}
			}
		}
		else
		{
			// Show registration form
			$this->response->setPartial('maincontent', 'user/register/registerForm');
		}
		
	}
	
	private function mailActivationMessage($email, $activationlink){
		$subject = 'Registration at this app';
		$message = 'Thanks for registering, ' . "\n\r";
		$message = 'Please click the following link to activate your account' . "\n\r";
		$message .= 'Click this: ' . $activationlink . "\n\r"; 
		$message .= 'Thanks.';
		$from = 'From: skeleton blog';
		mail($email, $subject, $message, $from);
	}
	
	public function activate($locator){
		$errmsg = '';
		// get the activation key
		$activationkey = $this->request->get('id');
		// @todo: do some validation to make sure it's a 32 string
		
		$model = $this->_load('app')->model('users');
		
		$model->activate($activationkey);
/*
		if(!empty($activationkey)){
			// @Todo: Check if the account already been activated?
			
				// If yes, user might not know. Show login screen

				// If not, activate account + sign in user + redirect to certain page		
			
			$result = $model->activate($activationkey);
			if($result){
				// registration activation succesfull
				$errmsg = 'Your account is now activated';
			} else {
				// something went wrong..
				$errmsg = 'We could not activate the account.';
			}
			
		} else {
			// User is on activate page but the activation key is missing. What to show?
			$errmsg = 'The activation key is missing.';
		}
		$template = $this->_load()->template('user/activate');
		$template->set('errmsg', $model->getErrorMsg());
*/
		
		$this->response->setPartial('maincontent', 'user/activate', array('errmsg' => $model->getErrorMsg(' ')));
		
	}
	
	public function password($locator){
		
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();
		
		$errmsg = '';

		$form = new A_Model_Form();
		$field = new A_Model_Form_Field('username');
		$field->addRule(new A_Rule_Notnull('username', 'username required'));
		$form->addField($field);
		// @todo: should we check in db if filled in username even exists
		
		$model = $this->_load('app')->model('users');
		
		// If password forgot form is posted and is valid
		if($form->isValid($this->request)){
			// @todo: retrieve email+password from user model and send email with pw
			
		} elseif($form->isSubmitted()){		// submitted form has errors
			$errmsg =  $form->getErrorMsg(', ');
		}
		
		// Show password forgot page and form
		$template = $this->_load()->template('user/password');
		$template->set('errmsg', $errmsg);
		$template->set('user', $user);
		$this->response->set('maincontent', $template);
	}
		
	public function profile($locator){
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();
		
		$errmsg = '';

		// If user is not signed in don't show profile page but redirect to login?
		if (!$user->isLoggedIn()) {
			$this->_redirect($locator->get('Config')->get('BASE') . 'user/login/');	// build redirect URL back to this page		
		}
				
		$form = new A_Model_Form();
		// @todo: what info do we want
		
		// To show the profile we need the model
		$model = $this->_load('app')->model('users');
		// @todo: load user data from db
		
		// If profile form is posted and is valid
		if($form->isValid($this->request)){
			// @todo: save/update profile data
			
		} elseif($form->isSubmitted()){		// submitted form has errors
			$errmsg =  $form->getErrorMsg(', ');
		}
		
		// Show profile page and form
		$template = $this->_load()->template('user/profile');
		$template->set('errmsg', $errmsg);
		$template->set('user', $user);
		$this->response->set('maincontent', $template);
	}
	

	
}