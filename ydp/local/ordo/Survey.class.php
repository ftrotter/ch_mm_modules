<?php
/**
 * Object Relational Persistence Mapping Class for table: survey
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package ydp
 */

/**#@+
 * Required Libs
 * @see '/ordo/ORDataObject.class.php'
 */
$loader->requireOnce('/ordo/ORDataObject.class.php');
/**
 * Object Relational Persistence Mapping Class for table: survey
 *
 * @package	ydp
 */
class Survey extends ORDataObject {
	/**#@+
	 * Fields of table: survey mapped to class members
         * @var string
	 */
	var $id		= '';
	var $patient_id		= '';
	var $form_id		= '';
	var $external_id		= '';
	var $status		= '';
	var $next_contact_date		= '';
	var $contacted		= '';
	var $to_contact		= '';
	/**#@-*/

	/**
	 * Short Description
	 *
         * @var boolean
         */
	var $_ssCache 		= false;

	/**
	 * Setup some basic attributes
	 * Shouldn't be called directly by the user, user the factory method on ORDataObject
	 *
 	 * @param boolean 
 	 * 
 	 * @return void 
         */
	function Survey($db = null) {
		parent::ORDataObject($db);	
		$this->_table = 'survey';
		$this->_sequence_name = 'sequences';	
	}

	/**
	 * Called by factory with passed in parameters, you can specify the primary_key of Survey with this
	*
 	* @param int 
 	* 
 	* @return void 
        */
	function setup($id = 0) {
		if ($id > 0) {
			$this->set('id',$id);
			$this->populate();
		}
	}

	/**
	 * Populate the class from the db
 	* 
 	* @return void 
        */
	function populate() {
		parent::populate('survey_id');
	}

	/**#@+
	 * Getters and Setters for Table: survey
	 */

	
	/**
	 * Getter for Primary Key: survey_id
 	 * 
 	 * @return int 
         */
	function get_survey_id() {
		return $this->id;
	}

	/**
	 * Setter for Primary Key: survey_id
	 *
 	 * @param int
 	 * 
 	 * @return int 
         */
	function set_survey_id($id)  {
		$this->id = $id;
	}
	/**
	 * Setter for Primary Key: survey_id
	 *
 	 * @param int
 	 * 
 	 * @return int 
         */
	function getNextContactDate($days_out = 3){

		$time_to_contact  = mktime(0, 0, 0, date("m")  , date("d")+$days_out, date("Y"));
		$date_to_contact = date("Y-m-d",$time_to_contact);
		return($date_to_contact);
	}
	/**#@-*/
	/**
	 * short description
 	 * 
 	 * @return array 
         */
	function getSurveyStatusList() {
		$list = $this->_load_enum('survey_status',false);
		return array_flip($list);
	}
	
	/**
	 * Cached lookup for encounter_reason
	 *
 	 * @param int
 	 * 
 	 * @return int 
         */
	function lookupSurveyStatus($id) {
		if ($this->_ssCache === false) {
			$this->_ssCache = $this->getSurveyStatusList();
		}
		if (isset($this->_ssCache[$id])) {
			return $this->_ssCache[$id];
		}
	}
	/**
	 * short description
 	 * 
 	 * @return boolean 
         */
	function shouldSend(){
		
		if($this->contacted > $this->to_contact){
			return false;
		}

		$status = $this->lookupSurveyStatus($this->id);
		if(strcmp('unsent',$status)==0){
			return true;
		}else{
			if(	strcmp('canceled',$status)==0||
				strcmp('completed',$status)==0){// then this survey is done!!
					return false;
			}else{
					return true; // right now this just means the status=sent and we are resending...
			}
		}
	}

}
?>
