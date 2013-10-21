<?php
/**
 * Object Relational Persistence Mapping Class for table: envelopes
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package messages
 */

/**#@+
 * Required Libs
old way
require_once CELLINI_ROOT.'/ordo/ORDataObject.class.php';
*/
//$loader->requireOnce('/ordo/ORDataObject.class.php');

/**#@-*/


/**
 * Object Relational Persistence Mapping Class for table: envelopes
 *
 * @package	messages
 */
class Envelopes extends ORDataObject {

	/**#@+
	 * Fields of table: envelopes mapped to class members
	 */
	var $id		= '';
	var $to_person		= '';
	var $from_person		= '';
	var $message_id		= '';
	var $when_sent		= 'NOW()';
	/**#@-*/


	/**
	 * Setup some basic attributes
	 * Shouldn't be called directly by the user, user the factory method on ORDataObject
      	* 
 	* @param boolean 
 	* 
 	* @return void 
        */
	function Envelopes($db = null) {
		parent::ORDataObject($db);	
		$this->_table = 'envelopes';
		$this->_sequence_name = 'sequences';	
	}

	/**
	 * Called by factory with passed in parameters, you can specify the primary_key of Envelopes with this
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
		parent::populate('envelope_id');
	}

	/**#@+
	 * Getters and Setters for Table: envelopes
	 */

	
	/**
	 * Getter for Primary Key: envelope_id
	* 
 	* @return void 
        */
	function get_envelope_id() {
		return $this->id;
	}

	/**
	 * Setter for Primary Key: envelope_id
	* 
 	* @param int 
 	* 
 	* @return void 
        */
	function set_envelope_id($id)  {
		$this->id = $id;
	}

	/**#@-*/
}
?>
