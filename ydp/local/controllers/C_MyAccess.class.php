<?php
/**
 * This is C_MyAccess.class.php
 *
 * This does .......
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package ydp
 */
/**
 * This is aloader
 *
 * @see "/ordo/ORDataObject.class.php"
 */
$loader->requireOnce("/ordo/ORDataObject.class.php");
/**
 * Controller to hold stuff that a patient can see on their record
 *
 * @package ydp
 */
class C_MyAccess extends PHRController {

        /**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string
 	*
 	* @return mixed fetch()
        */
        function actionDefault_patientview($patient_user_name = '',$password = '') {

		$this->assign('TOP_ACTION',Celini::link('default','MyAccess','my'));
		$this->assign('PASSWORD_LINK',Celini::link('auth1','MyAccess','my'));

		if(array_key_exists('password',$_POST)){// then this is the submission!!

			$patient_user_name = $_POST['username'];
			$password = $_POST['password'];

			$patientUser =& ORDataObject::factory('PatientUser');
			$person_id = $patientUser->verify($patient_user_name,$password);
			if($person_id){
				//setup token... 
				//determine if this is the first login...
					//return first_login action
				//otherwise send over the MyRecord 
				$patient =& ORDataObject::factory('Patient',$person_id);
				$tokenobject =& ORDataObject::Factory('Token',null);
				$token = $tokenobject->new_random($person_id);
				$tokenobject->persist();
				$cookies_broke = false;
				if(!setcookie("patient_id",$person_id,0,"/")){
					$cookies_broke = true;			
				}
				if(!setcookie("token",$token,0,"/")){
					$cookies_broke = true;
				}
				if($cookies_broke){
					$this->assign('error',"ERROR: This application requires cookies to be enabled, and was not able to set a cookie.</a>");	
				}
				$phr_home = Celini::link('default','MyRecord','my')."patient_id=$person_id&token=$token";
				header("Location: $phr_home");	
			//	$this->assign('error','login worked');

			}else{
				$not_yet_clinical = $patientUser->isNotYetClinical($patient_user_name,$password);
				if($not_yet_clinical){
					return $this->fetch(Celini::getTemplatePath("/myaccess/" . $this->template_mod . "_go_see_doctor_first.html"));

				}else{

					$this->assign('error',"ERROR: That username and password do not match a patient in our system. Do you need <a href='$password_link'>Help with your password?</a>. This system will only work AFTER you have visited your YDP doctor for the first time!");	
					// Display problem logging in action!!!	
				}
			}
		}	

		return $this->fetch(Celini::getTemplatePath("/myaccess/" . $this->template_mod . "_login.html"));
		
        }

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed fetch()
        */
	function actionGetid_patientview(){
		$this->assign('FORM_ACTION',Celini::link('getid','MyAccess','my'));

	if(array_key_exists('email',$_POST)){
		$email = $_POST['email'];

		$patientUser =& ORDataObject::factory('PatientUser');

		if(strstr($email,'@')){
			list($userName, $mailDomain) = split("@", $email); 
			if(checkdnsrr($mailDomain,'MX')){
				$person_id = $patientUser->sendid($email);
				$this->messages->addMessage('If this email is associated with a YDP user an email has been sent with the YDP user id!');	
			}else{
				//not a good domain name...
				$this->messages->addMessage('Not a valid domain name');	
			}
		}else{
			// no @ sign 
			$this->messages->addMessage('Not a valid email');	
		}

	}//no POST

		return $this->fetch(Celini::getTemplatePath("/myaccess/" . $this->template_mod . "_getid.html"));




	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed fetch()
        */
	function actionAuth1_patientview(){
		$this->assign('FORM_ACTION',Celini::link('auth1','MyAccess','my'));

	if(array_key_exists('process',$_POST)){
		$socsec = $_POST['socsec'];
		$username = $_POST['username'];

		$patientUser =& ORDataObject::factory('PatientUser');
		$person_id = $patientUser->socsec_verify($username,$socsec);

		if($person_id){ // authenticated OK
			$this->assign('socsec',$socsec);
			$this->assign('username',$username);
			$this->assign('person_id',$person_id);
	
			return($this->auth2_action_patientview());

		}else{
			$this->assign('error','ERROR: YDP does not have a social security record and username matching those you entered. Please try again or contact support');


		}
	}//no POST
		return $this->fetch(Celini::getTemplatePath("/myaccess/" . $this->template_mod . "_auth1.html"));

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed fetch()
        */
	function actionAuth2_patientview(){
		$this->assign('FORM_ACTION',Celini::link('auth2','MyAccess','my'));

	if(array_key_exists('process',$_POST)){
		$socsec = $_POST['socsec'];
		$username = $_POST['username'];
		//echo "social $socsec user $username";
		$patientUser =& ORDataObject::factory('PatientUser');
		$person_id = $patientUser->socsec_verify($username,$socsec);

		if($person_id){ // authenticated OK
			$this->assign('socsec',$socsec);
			$this->assign('username',$username);
			$this->assign('person_id',$person_id);
			$patientUser = $patientUser->fromPerson($person_id);
			$security_question = $patientUser->get('security_question');
			$this->assign('security_question',$security_question);
			$security_answer = strtolower($patientUser->get('security_answer'));
			$this->assign('security_answer',$security_answer);
			if(array_key_exists('answer',$_POST)){

				$users_security_answer = strtolower($_POST['answer']);
				if(strcmp($users_security_answer,$security_answer)==0){//they know the answer!!		
					$this->assign('answer',$users_security_answer);
					//echo "maiden name match";
					return $this->manage_action_patientview();	
				}
			}



		}else{
			$this->assign('error','ERROR: That does not match the answer to your secret question');


		}
	}//no POST
		return $this->fetch(Celini::getTemplatePath("/myaccess/" . $this->template_mod . "_auth2.html"));

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return boolean true
        */
	function _fully_authorized(){

		if(array_key_exists('process',$_POST)){
			$socsec = $_POST['socsec'];
			$username = $_POST['username'];
			$answer = $_POST['answer'];
			$patientUser =& ORDataObject::factory('PatientUser');
			$person_id = $patientUser->socsec_verify($username,$socsec);
			$patientUser = $patientUser->fromPerson($person_id);
			$security_question = $patientUser->get('security_question');
			if(strcmp($answer,$answer)==0){//they know the answer!!		
				$this->assign('answer',$answer);
				$this->assign('socsec',$socsec);
				$this->assign('username',$username);
				return(true);
			}


		}else{
			echo "There is an error in this page, please contact support";
			return(false);
		}


	}//function end

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed fetch()
        */
	function actionManage_patientview(){
		$this->assign('EMAIL_ACTION',Celini::link('manageemail','MyAccess','my'));
		$this->assign('PASSWORD_ACTION',Celini::link('managepassword','MyAccess','my'));

		if($this->_fully_authorized()){
				return $this->fetch(Celini::getTemplatePath("/myaccess/" . $this->template_mod . "_manage.html"));
		}else{

		//nothing...
		}
	}
	
	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed fetch()
        */
	function actionManageemail_patientview(){

		$this->assign('FORM_ACTION',Celini::link('manageemail','MyAccess','my'));

		if($this->_fully_authorized()){
		
			if(array_key_exists('new_email_1',$_POST)){
				$new_email_1 = $_POST['new_email_1'];
				$new_email_2 = $_POST['new_email_2'];
				$socsec = $_POST['socsec'];
				$username = $_POST['username'];
				$answer = $_POST['answer'];

				if(strcmp($new_email_1,$new_email_2)==0 
						&& strcmp($new_email_1,'')!=0 ){
					if(strstr($new_email_1,'@')){
					list($userName, $mailDomain) = split("@", $new_email_1); 
					if(checkdnsrr($mailDomain,'MX')){
						// save new email
						$patientUser =& ORDataObject::factory('PatientUser');
						$person_id = $patientUser->socsec_verify($username,$socsec);
						$patient =& ORDataObject::factory('Patient',$person_id);
						$patient->set('email',$new_email_1);
						$patient->persist();
						$this->messages->addMessage('Email Changed.');
						return($this->default_action_patientview());
					}else{
					$this->messages->addMessage('Invalid Email. domain does not work. Try again.');	
					}
					}else{
					$this->messages->addMessage('Invalid Email. no @ sign. Try again');	

					}
				}else{
					$this->messages->addMessage('Email Mismatch, try again.');	
				}

			}
			return $this->fetch(Celini::getTemplatePath("/myaccess/" . $this->template_mod . "_manageemail.html"));

		}else{

		//nothing
		}


	}//function end

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed fetch()
        */
	function actionManagepassword_patientview(){

		$this->assign('FORM_ACTION',Celini::link('managepassword','MyAccess','my'));


		if($this->_fully_authorized()){
		
			if(array_key_exists('new_password_1',$_POST)){
				$new_password_1 = $_POST['new_password_1'];
				$new_password_2 = $_POST['new_password_2'];	
				$socsec = $_POST['socsec'];
				$username = $_POST['username'];
				$answer = $_POST['answer'];

				$password_ok = true;

					if(!strcmp($new_password_1,$new_password_2)==0){
						$this->messages->addMessage('Password Mismatch, try again.');
						$password_ok = false;
					}

					if(strlen($new_password_1)<5){
						$this->messages->addMessage('Password Must be longer than 5 digits.');	
						$password_ok = false;
					}

					if(strcmp(strtolower($new_password_1),strtolower($username))==0){
						$this->messages->addMessage('Password is too similar to username.');	
						$password_ok = false;
					}



					if($password_ok){
						$patientUser =& ORDataObject::factory('PatientUser');
						$person_id = $patientUser->socsec_verify($username,$socsec);
						$patientUser = $patientUser->fromPerson($person_id);

						$patientUser->set('password',$new_password_1);
						$patientUser->persist();
						$this->messages->addMessage('Password Changed.');
						return($this->default_action_patientview());
					}

			}
			return $this->fetch(Celini::getTemplatePath("/myaccess/" . $this->template_mod . "_managepassword.html"));

		}else{

		//nothing
		}


	}//function end



	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string 
 	* 
 	* @return boolean 
        */
	function requireLogin($controller,$action){//otherwise no one would be able to login.
	//	echo "in MyAccess requirelogin";
		return(false);
	}


}
?>
