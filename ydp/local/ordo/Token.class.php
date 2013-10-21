<?php
/**
 * Object Relational Persistence Mapping Class for table: token
 *
 * Long Description
 * Long Description  
 * 
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package ydp
 */
/**
 * Required Libs
 * @see '/ordo/ORDataObject.class.php'
 */
$loader->requireOnce('/ordo/ORDataObject.class.php');


/**
 * Object Relational Persistence Mapping Class for table: token
*
* Long Description
* Long Description  
*
* @package ydp
*/
class Token extends ORDataObject {

	/**#@+
	 * Fields of table: token mapped to class members
	 *	 
 	 * Long Description  
	 *
         * @var string
         */
	var $token_id		= '';
	var $patient_id		= '';
	var $start_date		= '';
	var $end_date		= '';
	var $sha1_token		= '';
	/**#@-*/


	/**
	 * Short Description
	 *
	 * Setup some basic attributes
	 * Shouldn't be called directly by the user, user the factory method on ORDataObject
	*
 	* @param int
 	* 
 	* @return void 
        */
	function Token($db = null) {
		parent::ORDataObject($db);	
		$this->_table = 'token';
		$this->_sequence_name = 'sequences';	
	}

	/**
	 * Short Description
	 *
	 * Called by factory with passed in parameters, you can specify the primary_key of Myrecord_token with this
	*
 	* @param int
 	* 
 	* @return void 
        */
	function setup($id = 0) {
		if ($id > 0) {
			$this->set('token_id',$id);
			$this->populate();
		}
	}

	/**
	 * Populate the class from the db 
 	* @return void 
        */
	function populate() {
		parent::populate('token_id');
	}

	/**
	 * Create and save a new token using a random string. Return the string.
 	* @param int
 	* @param int
 	* @param int
 	* 
 	* @return string 
        */
	function new_random($patient_id=0, $start_date=0,$end_date=0) {

		if($patient_id == 0){ echo "Error in Token.class.php no patient id"; return;}
		$this->set('patient_id',$patient_id);
		
		$new_token = $this->_randomkeys(32);
		$this->set('sha1_token', sha1($new_token));


		if($start_date==0){
			$start_date =  date("Y-m-d");
		}
		$this->set('start_date', $start_date);

		if($end_date==0){
//TODO This value come from a configuration file???
			$months_valid = 2;
			$end_date = date("Y-m-d",
				mktime(0, 0, 0, date("m")+$months_valid, date("d"),  date("Y"))
			);
		}
		$this->set('end_date', $end_date);

		return($new_token);


	}




	/**
	 * Based on a patient id and a token id is this token valid?
         * 	
	 * @param int
	 * @param int
 	 * 
 	 * @return boolean 
         */
	function is_valid($patient_id,$token) {

		if(!strlen($token)==40){//then its not a SHA1 token... lets make it one...
			$token = sha1($token);
		}

			$res = $this->_execute("SELECT * FROM token WHERE patient_id = '$patient_id' AND
sha1_token = '$token'");

		$token_id = $res->fields['token_id'];
		$patient_id = $res->fields['patient_id'];
		$db_sha1_token = $res->fields['sha1_token'];
		$start_date = $res->fields['start_date'];
		$end_date = $res->fields['end_date'];
	//	$canceled = $res->fields['canceled'];
	//	echo $token_id;

		if(strcmp($db_sha1_token,$token)==0){// then it is the right token value...
			
			//if statement to check if after the start date

			//if statement to check if before end date
	
			//if statment to see if token has been revoked???
	
			return true;
		}

            return false;
	
	}

	/**
	 * Short Description
	 *
	 * Long Description  
         * 	
	 * @param int
 	 * 
 	 * @return string 
         */
  	function _randomkeys($length)
  	{
		$key = "";
   		$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
  	 	for($i=0;$i<$length;$i++)
   		{
     			$key .= $pattern{rand(0,35)};
   		}
   		return $key;
  	}


	/**#@+
	 * Getters and Setters for Table: token
	 */

	

	/**#@-*/
}
?>
