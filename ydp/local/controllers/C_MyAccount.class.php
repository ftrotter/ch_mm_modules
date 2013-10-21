<?php
/**
 * This is C_MyAccount.class.php
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
 * Controller to hold stuff that a user can edit on there account
 * @package	ydp
 */
class C_MyAccount extends Controller {

	/**
	 * Update the password of the currently logged in user
	 * @global array
 	 * 
 	 * @return mixed fetch()
         */
	function actionPasswordedit_edit() {
		$user =& $this->_me->get_user();

		$this->assign_by_ref('user',$user);
		
		return $this->fetch(Celini::getTemplatePath("/user/" . $this->template_mod . "_password.html"));
	}
	
	/**
	 * Update the password of the currently logged in user
	 * @global array
 	 * 
 	 * @return void
         */
	function actionPasswordprocess_process() {
		$user =& $this->_me->get_user();

		if ($_POST['password']['current_password'] !== $user->get('password')) {
			$this->messages->addMessage('Current Password Incorrect');
			return "";
		}
		//SHA1 on change your own password...
		$user->set('password',$_POST['password']['password']);
		$user->persist();
		$this->messages->addMessage('Password Changed');
	}
}
?>
