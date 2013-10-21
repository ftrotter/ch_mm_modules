<?php
/**
 * Object Relational Persistence Mapping Class for table: plans
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
 * Object Relational Persistence Mapping Class for table: plans
 *
 * Long Description
 * Long Description  
 *
 * @package
 */
class Plans extends ORDataObject {

	/**#@+
	 * Fields of table: plans mapped to class members
	 *
         * @var string
	 */
	var $id		= '';
	var $patient_id		= '';
	var $provider_id	= '';
	var $provider_text	= '';//ALTER TABLE `plans` ADD `provider_text` VARCHAR( 150 ) NOT NULL AFTER `provider_id` ;
	var $plan_type		= '';
	var $code_id		= ''; //ALTER TABLE `plans` ADD `code_id` INT( 10 ) NOT NULL AFTER `plan_type` ;
	var $plan_name		= '';
	var $plan_status	= '';
	/**#@-*/
	/**
	 * Fields of table: plans mapped to class members
	 *
         * @var array
         */
        var $storage_metadata = array(
                'int' => array(),
                'string' => array(),
                'date' => array(),
                'text' => array(
                        'meds' => '',
                        'goals' => '',
                        'interventions' => '',
                        'risk_factors' => ''
                )
        );




	/**
	 * Setup some basic attributes
	 *
	 * Shouldn't be called directly by the user, user the factory method on ORDataObject
	 *
 	 * @param boolean
 	 * 
 	 * @return void 
         */
	function Plans($db = null) {
		parent::ORDataObject($db);	
		$this->_table = 'plans';
		$this->_sequence_name = 'sequences';	
	}

	/**
	 * Called by factory with passed in parameters, you can specify the primary_key of Plans with this
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
		parent::populate('id');
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed 
        */
	function getPlanType(){
		$plan_types = $this->_load_enum('protocols',false);
		$plan_types = array_flip($plan_types);
		return($plan_types[$this->plan_type]);
			
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param array 
 	* 
 	* @return mixed 
        */
	function saveRisks($risk_array){

		$my_risks = '';
	//	var_export($risk_array);
		foreach($risk_array as $risk){

			$my_risks .= ',' .$risk;			
		}

		echo $my_risks;
		$this->storage_metadata['text']['risk_factors'] = $my_risks;
		$this->persist();
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed 
        */
	function getRisks(){
		return(split(',',$this->risk_factors));
	}



	/**
	 * Getters and Setters for Table: plans
        *
	* long comment for the class method below 
	* 
 	* @return mixed 
        */
	function persist(){
		

		$patient_id = $this->patient_id;
		$plan_type  = $this->plan_type;
		$plan_name = $this->plan_name;
		if($patient_id == 0){
			trigger_error("ERROR: Plans.class.php This script requires a patient_id");
			return(false);
		}

		$is_other = false;

		$plan_types = $this->_load_enum('protocols',false);
		$plan_types = array_flip($plan_types);
		if(strcmp(strtolower($plan_types[$plan_type]),"other") == 0){
			$is_other = true;
	//		echo "This is an other plan";
		}else{
	//		echo "this is a normal plan";
		}

	//	echo "patient_id = $patient_id plan_type = $plan_type<br>";
	
		$sql = "SELECT 
			COUNT(*) as count,
			id,
			plan_status,
			plan_name,
			meds
			FROM plans ";

		$sql .= " WHERE (patient_id = $patient_id AND plan_type = $plan_type) ";
		if($is_other){$sql .= "AND plan_name = \"$plan_name\"";}
		$sql .= " GROUP BY id";
		
	//	echo $sql;

		$results = $this->_db->Execute($sql) or die ("Database Error: " . $this->_db->ErrorMsg());
		
		$count = $results->fields['count'];

	//	echo "<br> count is $count<br>";

		if($count == 0){// then this is a new plan
			return(parent::persist());
		}else{
			$changing_status = $results->fields['plan_status'] != $this->plan_status;// || strcmp($results->fields['meds'],$this->meds) != 0;

			if($is_other){// only when the type is other do we respect names as different plans
				$changing_status = $changing_status || 
					strcmp($results->fields['plan_name'],$this->plan_name) != 0;
			}

			if($this->id == $results->fields['id']){
					//then we are changing the status of an existing entry
				return(parent::persist());
			}else{
		
				//then we are trying to create a second plan of this type, which is only ok 
				//if the plan type is other and thenonly if the name is different...

			
				$this->id = $results->fields['id'];
				$this->populate();
				return false;
			}
		}
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed 
        */
	function get_form_data_id(){

		$plan_id = $this->id;

		$sql = "
			SELECT * 
			FROM `form_data` 
			LEFT JOIN plans ON form_data.external_id = plans.id
			WHERE plans.id = $plan_id
			ORDER BY form_data.last_edit DESC
		";


		$results = $this->_db->Execute($sql) or die ("Database Error: " . $this->_db->ErrorMsg());
		
		if($results){
			return($results->fields['form_data_id']);
		}else{
			return(0);//which will create new data!!
		}

	}

	//For a given patient return an array of person_id => "Person Name" for everyone on thier plans...
	//

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @param int 
	* 
 	* @return mixed 
        */
	function getPersonList($patient_id) {

		
		$sql = "SELECT 
			concat_ws(' ',person.salutation,person.first_name,person.last_name) as name,
			plan_type,
			provider_id
			FROM `plans`
			JOIN person ON person.person_id = plans.provider_id
			WHERE patient_id = $patient_id AND
			plans.provider_id != 0";

		$plan_types = $this->_load_enum('protocols',false);
		$plan_types = array_flip($plan_types);
		//var_export($plan_types);
 		$results = $this->_db->Execute($sql) or die ("Database Error: " . $this->_db->ErrorMsg());
                while ($results && !$results->EOF) {
		//	var_export($results->fields);
                      		$returnArray[$results->fields['provider_id']] = $plan_types[$results->fields['plan_type']].": ".$results->fields['name']; 
                        $results->MoveNext();
                }
	//	var_export($returnArray);
		return $returnArray;
	}
	
	/**
	 * Getter for Primary Key: id
        *
	* long comment for the class method below 
	* 
 	* @return mixed 
        */
	function get_id() {
		return $this->id;
	}

	/**
	 * Setter for Primary Key: id
	* long comment for the class method below 
	* 
 	* @param int 
	* 
 	* @return mixed 
        */
	function set_id($id)  {
		$this->id = $id;
	}

	
}
?>
