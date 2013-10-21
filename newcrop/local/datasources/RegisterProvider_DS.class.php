<?php
$loader->requireOnce('/includes/Datasource_sql.class.php');
$loader->requireOnce('/includes/NewCropData.class.php');

/**
 * A datasource of all of the users of the system.
 */
class RegisterProvider_DS extends Datasource_sql 
{
	/**#@+
	 * @access private
	 */
	var $_internalName = 'User_DS';
	var $_type = 'html';
	var $_typeCache = array();	
	/**##@-*/
	
	/**
	 * Handle instantiation
	 */
	function RegisterProvider_DS() {
		$db =& Celini::dbInstance();
		$this->setup(
			$db,
			array(
				'cols' => '
					p.person_id as person_id,
					p.person_id as second_person_id,
					p.person_id as id,	
					p.last_name,
					p.first_name,
					pt.person_type,
					IF(p.inactive = 0, "Yes", "No") AS active,
					u.username, u.user_id',
				'from' => '
					person AS p
					INNER JOIN person_type AS pt USING(person_id)
					INNER JOIN user AS u USING(person_id)',
				'where' => '
					pt.person_type = 2
					'
				//'orderby' => 'p.last_name, p.first_name'
			),
			array(
				'last_name' => 'Last Name',
				'first_name' => 'First Name',
				'person_type' => 'Type',
				'username' => 'Username',
				'active' => 'Active',
				'person_id' => 'Register',
				'second_person_id' => 'Register',
				'id' => 'Status'
			)
		);
		
		$this->registerFilter('person_type',array(&$this, '_lookupType'));
		$this->registerFilter('last_name', array(&$this, '_actionEditLink'));
		$this->registerFilter('person_id', array(&$this, '_newcropRegisterLink'));
		$this->registerFilter('second_person_id', array(&$this, '_newcropTestRenewalsLink'));
		$this->registerFilter('id', array(&$this, '_newcropStatus'));
	}
	

	/**
	 * Shows the registration status with NewCrop for a given person_id
	 *
	 */
	function _newcropStatus($value) {

		return($value);

	}


	/**
	 * creates the button for registering a given provider id
	 *
	 */
	function _newcropRegisterLink($person_id) {


		$link = Celini::link('registerIndividual','NewCrop')."person_id=$person_id";

		return("<a href='$link'>Register</a>");

	}

	/**
	 * creates the button for testing renewals for a given id.
	 *
	 */

	function _newcropTestRenewalsLink($person_id) {


		$link = Celini::link('testRenewals','NewCrop')."person_id=$person_id";

		return("<a href='$link'>Test Renewals</a>");

	}



	
	/**
	 * Looks up person type enum value
	 *
	 * @param  string
	 * @return string
	 * @access private
	 */
	function _lookupType($value) {
		if (!isset($this->_typeCache[$value])) {
			$em =& Celini::enumManagerInstance();
			$this->_typeCache[$value] = $em->lookup('person_type', $value);
		}
		return $this->_typeCache[$value];
	}
	
	
	/**
	 * Formats link to edit a given user
	 *
	 * @param  string
	 * @return return
	 * @access private
	 */
	function _actionEditLink($value, $rowValues) {
		$url = Celini::link('edit', 'User') . 'id=' . $rowValues['person_id'];
		return 'Edit User: <a href="' . $url . '">' . $value . '</a>';
	}
}

?>
