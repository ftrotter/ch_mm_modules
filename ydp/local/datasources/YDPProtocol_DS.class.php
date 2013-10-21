<?php
/**
 * This is YDPProtocol_DS.class.php
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
 * @see '/includes/Datasource_sql.class.php'
 */

$loader->requireOnce('/includes/Datasource_sql.class.php');

/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class YDPProtocol_DS extends Datasource_sql 
{
	/**
	 * Short Description
	 *
	 * Long Description
	 * Long Description  
	 *
         * @var boolean
         */
	var $_encounterReasons = null;
	
	
	/**
	 * Stores the case-sensative class name for this ds and should be considered
	 * read-only.
	 *
	 * This is being used so that the internal name matches the filesystem
	 * name.  Once BC for PHP 4 is no longer required, this can be dropped in
	 * favor of using get_class($ds) where ever this property is referenced.
	 *
	 * @var string
	 */
	var $_internalName = 'Survey_List_DS';
	
	/**
	 * The default output type for this datasource.
	 *
	 * @var string
	 */
	var $_type = 'html';
	
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param int
 	* @param boolean
 	* 
 	* @return void 
        */	
	function YDPProtocol_DS($patient_id = 0, $encounter_id = 0, $outstanding = false) {
		
		settype($patient_id,'int');
		$where = array();

		if($patient_id > 0){
			$where[] = "survey.patient_id = $patient_id";
		}

		if($encounter_id > 0){
			$where[] = "e.encounter_id = $encounter_id";
		}	

		if($outstanding){//then we need to check to see if a form has been filled out
			echo "FRED need to create outstanding logic";
			//add logic to query to find forms_data that references the survey
			//only include surveys that do not have form data
			//only include surveys where contacted = to_contact
		}
	

		$where_string = "";

		foreach($where as $where_item){
			
			if(strcmp($where_string,"")==0){// this is the first where
				$where_string = $where_item;
			}else{
				$where_string .= " AND $where_item";
			}

		}
/*
based on
SELECT `id` , `name` , `file_name` , `active` , `type`
FROM `ydp_protocol`

*/

		$date_format = DateObject::getFormat();

		$this->setup(Celini::dbInstance(), 
			array(
				'cols' 	=> "
					id,
					name,
					file_name ,
					active ,
					type 
					",
				'from' 	=> "
					`ydp_protocol`
					",
			//	'where' => "$where_string",
			//	'orderby' => 'date_of_treatment DESC'
			),
			array(	
				'id' => "Protocol ID",
			//	'name' => 'Protocol Name',
				'file_name' => 'Save File',
				'active' => 'De/Re Activate',
				));

		//$this->orderHints['building'] = 'b.name';
		//$this->registerFilter('encounter_reason',array(&$this,'encounterReason'));
		//echo $ds->preview();
	}
	


}

