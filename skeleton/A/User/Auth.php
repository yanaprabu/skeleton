<?php
include_once 'A/User/Access.php';

class A_User_Auth extends A_User_Access
{	protected $db;	protected $table='user';	protected $sequence='user';	protected $userid_func=void;	protected $password_func=void;	protected $success_url='';				// set to redirect on successful signin	protected $crypt_func='md5';	protected $no_password=false;				// set to true for userid only signin
	
	public function setDB ($db)
	{
		$this->db = $db;
		return $this;
	}
	
	public function setDBTable ($table)
	{
		if ($table){
			$this->table=$table;
		}
		return $this;
	}
	
	public function setDBFieldUserID ($field)
	{
		if ($field){
			$this->field_userid=$field;
		}
		return $this;
	}
	
	public function setDBFieldPassword ($field)
	{
		if ($field){
			$this->field_password=$field;
		}
		return $this;
	}
	
	public function setDBFieldSequence ($field)
	{
		$this->field_sequence=$field;
		return $this;
	}
	
	public function setSuccessRedirect ($url)
	{
		$this->success_url = $url;
		return $this;
	}
	
	public function setCryptFunction ($func)
	{
		$this->crypt_func = $func;
		return $this;
	}
	
	public function signin ($userid, $password)
	{
		$this->error = 0;
		if (function_exists($this->userid_func)) {
			$userid = call_user_func($this->userid_func, $userid);
		}
		if (function_exists($this->password_func)) {
			$password = call_user_func($this->password_func, $password);
		}
		if ($userid && ($password || $this->no_password)){
	
			if ($this->db){
				$sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->field_userid . "='$userid'";
	
				$result = $this->db->query($sql);
				if (DB::isError($result)) {
					$this->error = 3;
				} else {
					if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
						if ($userid == $row[$this->field_userid]) {
							if ($this->crypt_func) {
								$password = call_user_func($this->crypt_func, $password);
							}
	
							if (($row[$this->field_password] == $password) || $this->no_password) {
	# password match
								if ($this->field_accessdate) {
									$sql = 'UPDATE ' . $this->table . ' SET ' . $this->field_accessdate . '=NULL WHERE ' . $this->field_userid . "='$userid'";
									$result = $this->db->query($sql);
								}
		
								$this->merge($row);
								$this->redirect($this->success_url);
								return $row;
							}else{
	# password does not match
								$this->error = 6;
							}
						}else{
	# userid does not match result
							$this->error = 5;
						}
					}else{
	# fetch failed
						$this->error = 3;
					}
				}
			}else{
	# no database connection
				$this->error = 11;
			}
		} elseif ($userid){
	# no password
			$this->error = 2;
		} else {
	# no userid
			$this->error = 1;
		}
		return 0;
	}
	
	public function create ($userid, $password, $userdata=array())
	{
		$this->error = 0;
		if (function_exists($this->userid_func)) {
			$userid = call_user_func($this->userid_func, $userid);
		}
		if (function_exists($this->password_func)) {
			$password = call_user_func($this->password_func, $password);
		}
		if ($userid && $password){
			if ($this->db){
				$sql = "SELECT * FROM {$this->table} WHERE {$this->field_userid}='$userid'";
				$result = $this->db->query($sql);
				if (DB::isError($result)) {
	// error with select
					$this->error = 3;
				} else {
					if ($result->numRows() == 0){
	// clear if garbage passed
						if (! is_array($userdata)) {
							$userdata = array();
						}
						if ($this->crypt_func) {
							$password = call_user_func($this->crypt_func, $password);
						}
						$userdata[$this->field_userid] = $userid;
						$userdata[$this->field_password] = $password;
	// build SQL insert command
						$fields = '';
						$values = '';
	// if a sequence and field are specified then get the next value
						if ($this->field_sequence && $this->sequence) {
							$userdata[$this->field_sequence] = $this->db->nextId($this->sequence, true);
						}
						foreach ($userdata as $key => $val) {
							if ($fields) {
								$fields .= ',';
							}
							$fields .= $key;
							if ($values) {
								$values .= ',';
							}
							$values .= '"' . $val . '"';
						}
						$sql = "INSERT INTO {$this->table} ($fields) VALUES ($values)";
						$result = $this->db->query($sql);
						if (DB::isError($result)) {
	# userid not added
							$this->error = 9;
						} else {
	// sign-in
							$this->merge($userdata);
							$this->redirect($this->success_url);
							return $userdata;
						}
					}else{
	# userid exists
						$this->error = 4;
					}
				}
			}else{
	# no database connection
				$this->error = 11;
			}
		} elseif ($userid) {
	# no password
			$this->error = 2;
		} else {
	# no userid
			$this->error = 1;
		}
		return 0;
	}
	
	public function changePassword ($userid, $newpassword, $oldpassword='')
	{
		$this->error = 0;
		if (function_exists($this->userid_func)) {
			$userid = call_user_func($this->userid_func, $userid);
		}
		if (function_exists($this->password_func)) {
			$newpassword = call_user_func($this->password_func, $newpassword);
		}
		if ($userid && $newpassword){
			if ($this->db){
				$sql = "SELECT * FROM {$this->table} WHERE {$this->field_userid}='$userid'";
				$result = $this->db->query($sql);
				if (DB::isError($result)) {
	// error with select
					$this->error = 3;
				} else {
					if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
						$change = 1;
						if($oldpassword){
							$oldpassword=md5($oldpassword);
							if($oldpassword != $row[$this->field_password]){
								$change = 0;
							}
						}
						if($change){
							if ($this->crypt_func) {
								$newpassword = call_user_func($this->crypt_func, $newpassword);
							}
							$row[$this->field_password] = $newpassword;
							$sql = "UPDATE {$this->table} SET {$this->field_password}='{$row[$this->field_password]}' WHERE {$this->field_userid}='$userid'";
							$result = $this->db->query($sql);
							if (DB::isError($result)) {
	# error writing new password
								$this->error = 10;
							} else {
								$this->error = 0;
								return $row[$this->field_password];
							}
						}else{
	# old password did not match
							$this->error = 8;
						}
					}else{
	# userid not found
						$this->error = 7;
					}
				}
			}else{
	# no database connection
				$this->error = 11;
			}
		} elseif ($userid){
	# no password
			$this->error = 2;
		} else {
	# no userid
			$this->error = 1;
		}
		return 0;
	}
	
	/**
	 * Generate a password string of a given length from the character set string
	 * where characters and character ranges are separated by commas, for
	 * example "1,2,A-Z" would contain 1, 2 and all the upper case letters.
	 * Defaults to 10 character passwords with a few symbols, but no 1,l,0,O,Q
	 **/
	public function generatePassword($length=10, $charset='')
	{
		$password = '';
		if(empty($charset)){
			$charset = 'a-k,m-z,A-N,P,R-Z,2-9,-,+,=,$,%,&,*';
		}
		if($length > 0){
			$n = 0;
			$array = explode(',', $charset);
			$nranges = count($array);
			for($i=0; $i<$nranges; ++$i){
				if(strpos($array[$i], '-')){
					$ch1 = substr($array[$i], 0, 1);
					$ch2 = substr($array[$i], 2, 1);
					if($ch2 < $ch1){
						$ch2 = $ch1;
					}
				}else{
					$ch1 = substr($array[$i], 0, 1);
					$ch2 = $ch1;
				}
				for($ch=ord($ch1); $ch<=ord($ch2); ++$ch){
					$chars[$n++] = chr($ch);
				}
			}
		mt_srand((double) microtime() * 1000000);
		for($i=0; $i<$length; ++$i){
			$password .= $chars[mt_rand(0, $n-1)];
			}
		}
		return $password;
	}
	
	public function isError ()
	{
		return($this->error);
	}
	
	public function getMessage ($error=0)
	{
		$msg = array (
			0 => '',
			1 => 'no userid was given',
			2 => 'no password was given',
			3 => 'no record found',
			4 => 'the ID already exists',
			5 => 'the ID does not match',
			6 => 'the password does not match',
			7 => 'the ID was not found',
			8 => 'the old password did not match',
			9 => 'error occured while saving the user information',
			10=> 'error occured while saving the new password',
			11=> 'no database connection'
		);
	
		if ($error == 0) {
			return ($msg[$this->error]);
		} else {
			return ($msg[$error]);
		}
	}
	
	public function errmsg ($error=0)
	{
		return($this->getMessage($error));
	}

} // class A_User_Auth


/*

#
# Table structure for table `user`
#
CREATE TABLE user (
  id int NOT NULL,
  userid varchar(24) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  fname varchar(32) NOT NULL default '',
  lname varchar(32) NOT NULL default '',
  email varchar(64) NOT NULL default '',
  mailingList char(1) NOT NULL default 'N',
  verifyQuestion varchar(50) NOT NULL default '',
  verifyAnswer varchar(50) NOT NULL default '',
  access varchar(50) NOT NULL default '',
  data text NOT NULL default '',
  lastAccess timestamp(14) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY userid (id)
);

*/
