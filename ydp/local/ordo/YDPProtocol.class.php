<?php
/**
 * Object Relational Persistence Mapping Class for table: ydp_protocol
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package ydp
 */
/**
 * Required Libs
 */
$loader->requireOnce('/ordo/ORDataObject.class.php');


/**
 * Object Relational Persistence Mapping Class for table: ydp_protocol
*
* Long Description
* Long Description  
*
* @package ydp
*/
class YDPProtocol extends ORDataObject {

	/**#@+
	 * Fields of table: ydp_protocol mapped to class members
	 *
         * @var string
         */
	var $id		= '';
	var $name		= '';
	var $file_name		= '';
	var $plan_file_name		= '';
	var $active		= 1;
	var $type		= '';
	var $age		= 'adult';
	var $history_targets		= '';
	var $risk_factors		= '';
	var $outcome_goals		= '';
	var $treatment_goals		= '';
	var $medications		= '';
	var $lab_studies		= '';
	var $imaging_studies		= '';
	var $referral		= '';
	var $interventions		= '';
	/**#@-*/


	/**
	 * Setup some basic attributes
	 * Shouldn't be called directly by the user, user the factory method on ORDataObject
	*
 	* @param boolean 
 	*
 	* 
 	* @return void 
        */
	function YDPProtocol($db = null) {
		parent::ORDataObject($db);	
		$this->_table = 'ydp_protocol';
		$this->_sequence_name = 'sequences';	
	}

	/**
	 * Called by factory with passed in parameters, you can specify the primary_key of Ydp_protocol with this
	*
 	* @param int
 	*
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
		parent::populate('id');
	}

	/**#@+
	 * Getters and Setters for Table: ydp_protocol
	 */

	
	/**
	 * Getter for Primary Key: id
 	* 
 	* @return void 
        */
	function get_id() {
		return $this->id;
	}

	/**
	 * Setter for Primary Key: id
 	*
 	* @param int
 	* 
 	* @return void 
        */
	function set_id($id)  {
		$this->id = $id;
	}
	/**
	 * short description
 	*
 	* @param string
 	* 
 	* @return string
        */
	function planform_from_type($type){

	$ydp = new YDPProtocol();
	$sql = "SELECT `plan_file_name` FROM `ydp_protocol` WHERE type = $type";
	$result = $ydp->_Execute($sql);
	if($result && !$result->EOF){
		return($result->fields['plan_file_name']);
	}else{
		return('600010');//the OTHER file... we hope...
	}
	}

	/**
	 * short description
 	*
 	* @param string
 	* 
 	* @return int
        */
	function form_from_type($type){

	$ydp = new YDPProtocol();
	$sql = "SELECT `file_name` FROM `ydp_protocol` WHERE type = $type";
	$result = $ydp->_Execute($sql);
	if($result && !$result->EOF){
		return($result->fields['file_name']);
	}else{
		return('0');//the OTHER file... we hope...
	}
	}


	/**
	 * short description
 	* 
 	* @return array
        */
	function idList(){

	$ydp = new YDPProtocol();
	$sql = "SELECT `id` FROM `ydp_protocol` WHERE 1";
	$result = $ydp->_Execute($sql);
	while($result && !$result->EOF){
		$id_array[] = $result->fields['id'];
		$result->MoveNext();	
	}

	return $id_array; 	

	}

	/**#@-*/
}
?>
