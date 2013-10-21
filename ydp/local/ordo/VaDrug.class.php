<?php
/**
 * Object Relational Persistence Mapping Class for table: va_drug
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package ydp
 */
/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class VaDrug extends ORDataObject {

	/**#@+
	 * Fields of table: va_drug mapped to class members
	 *
	 * @var string  
	 */
	var $va_drug_id		= '';
	var $patient_id		= '';
	var $external_id	= '';
	var $table		= '';
	var $drug_id		= '';
	var $created_date		= '';
	var $removed_date		= '';
	var $active		= '';
	/**#@-*/
/*

ALTER TABLE `va_drug` ADD `created_date` DATETIME NOT NULL ,
ADD `removed_date` DATETIME NOT NULL ,
ADD `active` TINYINT NOT NULL DEFAULT '1';

*/

	/**
	 * DB Table
	 * @var string  
	 */
	var $_table = 'va_drug';

	/**
	 * Primary Key
	 * @var string  
	 */
	var $_key = 'va_drug_id';
	
	/**
	 * Internal Name
	 * @var string  
	 */
	var $_internalName = 'VaDrug';

	/**
	 * Handle instantiation
        *
	* long comment for the class method below 
	*
 	* @return void 
        */
	function VaDrug() {
		parent::ORDataObject();
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return void 
        */
	function persist(){

		if($this->va_drug_id == ''){// completely new!!
			$this->set('created_date',date('Y-m-d H:i:s'));
			$this->set('active',1);
		}

		parent::persist();
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return void 
        */
	function remove(){

		$this->set('removed_date',date('Y-m-d H:i:s'));
		$this->set('active',0);
		parent::persist();
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	*
 	* @return boolean 
        */
	function isInteraction($patient_id){

		//lots of sql magic
		return(false);

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	*
 	* @return boolean 
        */
	function getInteractionText($patient_id){

		//even more sql magic...
		return(false);

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param int
 	* @param int
 	*
 	* @return void 
        */
	function addDrug($patient_id, $table, $drug_id){

		$newDrug =& Celini::newORDO('VaDrug');
		$newDrug->set('patient_id',$patient_id);
		$newDrug->set('table',$table);
		$newDrug->set('drug_id',$drug_id);
		$newDrug->persist();	
	}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param int
 	*
 	* @return mixed
        */
	function getPrettyList($patient_id,$external_id = 0){

		$url = WEB_ROOT;

		$results_array = $this->getDrugList($patient_id,$external_id);
		$ulid = '';
		if(count($results_array)!=0){//we have results
			$first_result = $results_array[0];
			$planid = $first_result['external_id'];
		}
			$to_return = "<ul id='ul_$planid'>";
	//	var_export($results_array);
		$seen = array();
		foreach($results_array as $result){
			//TODO fix this hack!!
			if(!array_key_exists($result['name'],$seen)){
				$seen[$result['name']] = true;
				$to_return .= '<li id="'.$result['va_drug_id'].'_'.$planid.'" >';
				$to_return .= $result['name'];
				$to_return .= "<a href='#' onclick=\"removeEntry$planid('".$result['va_drug_id']."_$planid','".$result['name']."');return false;\">";
				$to_return .= "<img src='$url/images/stock_trash-16.png'></a>";
				$to_return .= '</li>';
			}
		}
		$to_return .= '</ul>';
		
		return $to_return;	


	} 


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param int
 	*
 	* @return array
        */
	function getDrugList($patient_id,$external_id = 0){

//  1002388
		$sql = "
SELECT 
va_drug.va_drug_id,
va_drug.drug_id,
va_drug.table,
va_drug.external_id,
va_drug.active,
va_p.name as product_name,
va_p.name as name,
0 as trade_id,
va_p.id as product_id
FROM `va_drug` 
LEFT JOIN va_productname as va_p on va_drug.drug_id = va_p.id
WHERE `patient_id` = '$patient_id'
AND va_drug.table = 'va_productname'
AND va_drug.active = 1
";

		if($external_id !=0){
			$sql .= "AND external_id = $external_id";
		}

	
		$results = $this->_db->Execute($sql) or die ("Database Error: " . $this->_db->ErrorMsg());

		while ($results && !$results->EOF) {
		
		$drugs[] = $results->fields;
		$results->MoveNext();
		}

			$sql = "
SELECT 
va_drug.va_drug_id,
va_drug.drug_id,
va_drug.table,
va_drug.external_id,
va_drug.active,
va_t.name as trade_name,
va_t.name as name,
va_t.id as trade_id,
va_t.product_id as product_id
 FROM `va_drug` 
LEFT JOIN va_tradenames as va_t on va_drug.drug_id = va_t.id
WHERE `patient_id` = '$patient_id'
AND va_drug.table = 'va_tradenames'
AND va_drug.active = 1
";


		if($external_id !=0){
			$sql .= "AND external_id = $external_id";
		}


	//	echo $sql;

		$results = $this->_db->Execute($sql) or die ("Database Error: " . $this->_db->ErrorMsg());


		while ($results && !$results->EOF) {
		
		$drugs[] = $results->fields;
		$results->MoveNext();
		}
		if(is_array($drugs)){
			return($drugs);
		}else{
			return(array());
		}

	}
	
}
?>
