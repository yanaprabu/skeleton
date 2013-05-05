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
			
			$model = $this->_load('app')->model('users', $locator->get('Db'));
			$userdata = $model->login($form->get('username'), $form->get('password') );

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

						// Insert user data in db
						$usermodel->insertUser( $request->get('username'), 
												$request->get('password'), 
												$request->get('email'), 
												$actkey
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
							if($usermodel->isPasswordCorrect($request->get('username'), $request->get('password'), $locator->get('Config')->get('SITESALT')))
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
		$message .= 'Please click the following link to activate your account' . "\n\r";
		$message .= 'Click this: ' . $activationlink . "\n\r"; 
		$message .= 'Thanks.';
		$from = 'From: skeleton blog';
		mail($email, $subject, $message, $from);
	}
	
	public function activate($locator){

		// get the activation key
		$activationkey = $this->request->get('id');
		
		$model = $this->_load('app')->model('users');
		$model->activate($activationkey);
		
		$this->response->setPartial('maincontent', 'user/activate', array('errmsg' => $model->getErrorMsg(' ')));
		
	}
	
	public function password($locator){
		
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();
		$request = $this->request;
		$errmsg = '';

		$form = new A_Model_Form();
		$field = new A_Model_Form_Field('email');
		//$field->addRule(new A_Rule_Notnull('email', 'Please fill in your email address.'));
		$field->addRule(new A_Rule_Email('email', 'Please provide a valid email address.'));
		$form->addField($field);
		// @todo: should we check in db if filled in username even exists
		
		//$model = $this->_load('app')->model('users');
		$usermodel = $this->_load('app')->model('users');
		
		if($request->isPost()){
			// If password forgot form is posted and is valid
			if($form->isValid($this->request)){
				// Retrieve email from user model and send email with istructions
				$userdata = $usermodel->findByEmail($request->get('email'));
				if(empty($userdata)){
					// no userdata exists with this email address
					$this->mailResetPasswordAttemptMessage($request->get('email'));
				} else {
					// userdata for this email adress exist
					
					// Create unique reset key
					$resetkey = md5(uniqid(rand(), true));
					// insert reset key in db
					$usermodel->insertResetkey($resetkey, $userdata['id']);
					
					// send email with link and reset key
					$resetlink = $locator->get('Config')->get('BASE') . 'user/resetpassword?id=' . $resetkey;
					$this->mailResetPasswordMessage($request->get('email'), $userdata['username'], $resetlink);
		
				}
				// Show page with instructions
				$this->response->setPartial('maincontent', 'user/password/password-instructions-send');
			} elseif($form->isSubmitted()){		// submitted form has errors
				$errmsg =  $form->getErrorMsg(' ');
				$this->response->setPartial('maincontent', 'user/password/password', array('errmsg' => $errmsg ));
			}
		} else {
			// Show password forgot page and form
			$this->response->setPartial('maincontent', 'user/password/password');
		}
		
	}
	
	private function mailResetPasswordAttemptMessage($email){
		$subject = 'Account access attempted';
		$message = 'You (or someone else) entered this email address when trying to change the password of a Skeleton app account.'. "\n\r";
		$message .= 'However, this email address is not in our database of registered users and therefore the attempted password change has failed' . "\n\r";
		$message .= 'If you are a Skeleton app member and were expecting this email, please	try again using the email address you gave when registering your account.' . "\n\r";
		$message .= 'If you are not a Skeleton app member, please ignore this email.' . "\n\r";
		$message .= 'For information about Skeleton app, please visit skeleton app dot com.'. "\n\r";
		$message .= 'Thanks.' . "\n\r";
		$message .= 'Skeleton app support team';
		$from = 'From: skeleton blog';
		return mail($email, $subject, $message, $from);
	}
			
	private function mailResetPasswordMessage($email, $username, $resetlink){
		$subject = 'Reset your password';
		$message = 'Hi, ' . $username . "\n\r";
		$message .= 'You (or someone else) entered this email address when trying to change the password of a Skeleton app account.'. "\n\r";
		$message .= 'If you are the one who tried to reset your password, please click the following link to reset your password' . "\n\r";
		$message .= 'Click this: ' . $resetlink . "\n\r"; 
		$message .= 'If you did not fill in your email address, please ignore this email.' . "\n\r";
		$message .= 'Thanks.' . "\n\r";
		$message .= 'Skeleton app support team';
		$from = 'From: skeleton blog';
		return mail($email, $subject, $message, $from);
	}
	
	public function resetpassword($locator) {
		// get the reset key
		$resetkey = $this->request->get('id');
		// Load usermodel
		$usersmodel = $this->_load('app')->model('users');
		
		// Check if resetkey is valid
		$result = $usersmodel->findResetkey($resetkey);
		if($result === true){
			// Show a form with which a user can reset his password
			$this->response->setPartial('maincontent', 'user/password/resetpasswordform', array('resetkey'=>$resetkey));
		} else {
			// invalid key provided
			$errorMsg = 'Sorry that\'s not a valid reset key';
			// Show a page with an error message
			$this->response->setPartial('maincontent', 'user/password/resetpassworderror', array('errmsg' => $errorMsg));
		}
	}
	
	public function setpassword($locator){
		$request = $this->request;
		$usermodel = $this->_load('app')->model('users');
		
		$form = new A_Model_Form();
		$field = new A_Model_Form_Field('password');
		$field->addRule(new A_Rule_Notnull('password', 'Please fill in the password.'));
		$field2 = new A_Model_Form_Field('passwordagain');
		$field2->addRule(new A_Rule_Notnull('passwordagain', 'Please fill in the passwordagain.'));
		$form->addRule(new A_Rule_Match('passwordagain', 'password', 'Fields password and passwordagain do not match'));
		$form->addField($field);
		$form->addField($field2);
		
		if($request->isPost()){
			$resetkey = $request->get('resetkey');
			if($form->isValid($this->request)){
				$resetkey = $request->get('resetkey');
				$result = $usermodel->findResetkey($resetkey);
				if($result === true){
					$usermodel->resetPassword($request->get('password'), $resetkey);	
					$this->response->setPartial('maincontent', 'user/password/resetpasswordsuccess');
					
				} else {
					$this->response->setPartial('maincontent', 'user/password/resetpassworderror', array('message'=>'reset failed'));
				}
			} else {
				$this->response->setPartial('maincontent', 'user/password/resetpasswordform', array('message'=>'reset form invalid', 'resetkey'=>$resetkey));
			}
		}
	}
	
	public function profile($locator){
		$session 	= $locator->get('Session');
		$user 		= $locator->get('UserSession');
		$session->start();
		$messages 	= array();
		$request 	= $this->request;
		
		// If user is not signed in don't show profile page but redirect to login?
		if (!$user->isLoggedIn()) {
			$this->_redirect($locator->get('Config')->get('BASE') . 'user/login/');	// build redirect URL back to this page		
		}
		
		// To show the profile we need the model
		$model = $this->_load('app')->model('users');
		$userdata = $model->find($user->get('id'));
		
		if($request->isPost()){ 
			$model->includeRules(array('firstname', 'lastname', 'email'));
			if(!$model->isValid($request)){
				$messages[] = $model->getErrorMsg("</li>\n<li>");
				$data = array(
					'firstname' => $request->get('firstname'),
					'lastname' 	=> $request->get('lastname'),
					'email' 	=> $request->get('email')
				);
				$this->response->setPartial('maincontent', 'user/profile', array( 'messages' => $messages, 'data'=>$data ));
			} else {
				$data = array(
					'firstname' => $model->get('firstname'),
					'lastname' 	=> $model->get('lastname'),
					'email' 	=> $model->get('email')
				);
				$user_id = $user->get('id');
				$model->updateUser($data, $user_id);
				$this->response->setPartial('maincontent', 'user/profile', array( 'messages' => $messages, 'data'=>$data ));
			}
			
		} else {
			
			$this->response->setPartial('maincontent', 'user/profile', array( 'messages' => $messages, 'data' => $userdata ));
		}
		
	}
	

	
}