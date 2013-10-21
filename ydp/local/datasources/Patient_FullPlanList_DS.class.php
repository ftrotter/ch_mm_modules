<?php
/**
 * This is Patient_FullPlanList_DS.class.php
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
class Patient_FullPlanList_DS extends Datasource_sql 
{

	/**
	 * Short Description
	 *
	 * Long Description
	 * Long Description  
	 *
         * @var boolean
         */	
	var $_planStatus = null;
	/**
	 * Short Description
	 *
	 * Long Description
	 * Long Description  
	 *
         * @var boolean
         */
	var $_planType = null;
	
	
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
	var $_internalName = 'Patient_FullPlanList_DS';
	
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
 	* @param boolean 
 	*
 	* @return void 
        */
	function Patient_FullPlanList_DS($patient_id,$edit = true) {
		

		settype($patient_id,'int');
		$this->_db =& Celini::dbInstance();
		
		$this->setup($this->_db,
			array(
				'cols' 	=> "plans.id as plan_id, 
					plans.patient_id,  
					0 as plan_type_ahahdisplay, 
					plans.plan_type as plan_type,
					plans.plan_name as plan_name,
					plans.plan_status as plan_status,
					COUNT( * ) AS plan_versions,
					plans.id as risk_factors,
					plans.provider_id as provider_id, 
					plans.provider_text, 
					concat_ws( ' ', provider.last_name, provider.first_name ) doc,
					plans.id as interventions, 
					0 as meds_ahahdisplay, 
					plans.patient_id as meds, 
					plans.id as goals",
				'from' 	=> "plans ".
					//	LEFT JOIN form_data ON form_data.external_id = plans.id
					"	LEFT JOIN person AS provider ON provider_id = provider.person_id
					",
				'where' => "
					patient_id = $patient_id
					",
				'groupby' => "plans.id",
				'orderby' => "plans.plan_status"
				
			),
			array(	'plan_type' => 'Personal Protocol',
				'plan_name' => 'Diagnosis',
				'plan_status' => 'Status',
			//	'plan_versions' => 'Vers',
				'risk_factors' => 'Risks',
				'interventions' => 'Interventions',
				'meds' => 'Medications',
				'goals' => 'Goals',
				'doc' => 'Physician'
		
			));

		$this->registerFilter('plan_status',array(&$this,'planStatus'));
		$this->registerFilter('plan_type',array(&$this,'planType'));
		if($edit){
			$this->registerFilter('meds',array(&$this,'medsedit'));
		}else{
			$this->registerFilter('meds',array(&$this,'medslist'));
		}
		$this->registerFilter('risk_factors',array(&$this,'riskFactors'));
		$this->registerFilter('interventions',array(&$this,'medsInterventions'));
		$this->registerFilter('goals',array(&$this,'goals'));
		$this->registerFilter('doc',array(&$this,'doc'));
	}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param array 
 	*
 	* @return string 
        */
	function medslist($patient_id,$row){

		$plan_id = $row['plan_id'];

		$url = WEB_ROOT;
		$newDrug =& Celini::newORDO('VaDrug');
		$drugs = $newDrug->getPrettyList($patient_id,$plan_id);

		return($drugs);


	}



	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param boolean 
 	*
 	* @return string
        */
	function medsedit($patient_id,$row){

		$plan_id = $row['plan_id'];

		$url = WEB_ROOT;
		$newDrug =& Celini::newORDO('VaDrug');
		$drugs = $newDrug->getPrettyList($patient_id,$plan_id);


	$html = "
<script language='javascript'>


	function setHidden(text, li){
		var hidden = document.getElementById('drugcomplete_hidden_$plan_id');
		hidden.value = li.id;
		var button = document.getElementById('drugcomplete_button_$plan_id');
		button.style.display = '';
	}



function addEntry$plan_id(source) {

	var current_value = document.getElementById(source).value;

		window.location.href = '$url/index.php/YDPPlan/drugadd?patient_id=' + $patient_id + '&plan_id=' + $plan_id +'&drug_key=' + current_value;

//	var url = '$url/index.php/YDPPlan/drugresults?patient_id=' + $patient_id + '&plan_id=' + $plan_id;



		setTimeout(\"callAHAH('$url/index.php/YDPPlan/drugresults?patient_id=$patient_id&plan_id=$plan_id','meds_display_$plan_id','loading drug list...','error...')\",1000 ); 
	//setTimeout postpones the call by a second... giving the server time to sort its head out...

	
	textField = document.getElementById('drugcomplete_$plan_id');
	textField.value = '';
	button = document.getElementById('drugcomplete_button_$plan_id');
	button.style.display = 'none';
	return false;
}

function removeEntry$plan_id(liNum, name) {
	if (confirm('Are you sure that you would like to remove ' +name+ ' from the list?')) {
		window.location.href = '$url/index.php/YDPPlan/drugremove?va_drug_id=' + liNum;
		var anchor = document.getElementById('ul_$plan_id');
	//	console.log('does logging work???');
	//	console.dir(anchor);
	//	console.dirxml(anchor);
	//	console.trace();
	//	alert('wellthis works');
		var deadli = document.getElementById(liNum);
		anchor.removeChild(deadli);
	}

	return false;
}

</script>

search for drugs<br>
<input type='text' id='drugcomplete_$plan_id' name='search_string' size=20/>
<div id='drugcomplete_choices_$plan_id' class='autocomplete'></div>

	<input type='hidden' id='drugcomplete_hidden_$plan_id' name='drugcomplete_hidden_$plan_id'>
	<input type='hidden' name='plan_id' value='$plan_id'>
	<input type='hidden' name='col_id' value='interventions'>

<input style='display: none' type='button' id='drugcomplete_button_$plan_id' 
		name='drugcomplete_button_$plan_id' value='Add'
		onclick=\"addEntry$plan_id('drugcomplete_hidden_$plan_id');\">


<script language='javascript'>

	new Ajax.Autocompleter('drugcomplete_$plan_id', 'drugcomplete_choices_$plan_id', '$url/index.php/YDPPlan/Drug?patient_id=$patient_id&plan_id=$plan_id', {afterUpdateElement : setHidden});

</script>

<div id='meds_display_$plan_id'>
$drugs
</div>

";

	
	return($html);



	}



	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param boolean 
 	*
 	* @return string 
        */
	function riskFactors($plan_id,$row){

/*
	$em =& Celini::enumManagerInstance();
	$risksList = $em->enumList('risks_other');
	$risks = $risksList->toArray();
*/
	//var_export($risks);
// The code below is the "correct" way to do it. But it relies on the tree type returning multi-dimensional-array

/*
	$risks_sysname_array = array();
	foreach($risks as $protocol_name => $risks_sub_array){

		$protocol_sysname =  strtolower(preg_replace('/\ /','',$protocol_name));
//		echo $protocol_sysname ."<br>";
		foreach($risks_sub_array as $key => $risk){
	
			$risk_sysname = strtolower(preg_replace('/\ /','',$risk));
			$risks_sysname_array[$protcol_sysname] = $risk_sysname;

		}
	}

*/
		
	$plan =& ORDataObject::Factory('Plans',$plan_id);
	$risks_string = $plan->get('risk_factors');
	$risks = split(',',$risks_string);
	if(count($risks)!=0){
		$html = '<ul>';
		foreach($risks as $risk){
			$html .= '<li>'. $risk . "</li>";

		}
		$html .= '</ul>';
		return($html);
	}else{
		return('*no risks defined*');
	}
	
	}



	
/*
	function riskFactors($plan_id){ // old form-based Risk factors system...
*/
/*
Risk Factors SQL
		SELECT 
                       storage_string.foreign_key,
			storage_string.value as string_value,
			storage_string.value_key
		FROM 
			plans 
		RIGHT JOIN 
			form_data ON form_data.external_id = plans.id 
		RIGHT JOIN 	
			storage_string ON storage_string.foreign_key = form_data.form_data_id
		WHERE 
			plans.id = 600271 AND
value_key LIKE 'risk_factors%'


*/
/*
	$sql = "
		SELECT 
                        storage_string.foreign_key,
			storage_string.value as string_value,
			storage_string.value_key
		FROM 
			plans 
		RIGHT JOIN 
			form_data ON form_data.external_id = plans.id 
		RIGHT JOIN 	
			storage_string ON storage_string.foreign_key = form_data.form_data_id
		WHERE 
			plans.id = $plan_id AND
			value_key LIKE 'risks_%'
		ORDER BY foreign_key DESC
		";

		$result = $this->_db->Execute($sql);
		$highest_form_id = 0;

		while($result && !$result->EOF) {
			$form_data_id = $result->fields['foreign_key'];
			$risk_factor = $result->fields['string_value'];
	
			if($form_data_id >= $highest_form_id){
			if($form_data_id != $highest_form_id){//then this is the row to pay attention to
				$highest_form_id = $form_data_id;
				$risk_factors = array();//ignore previous risk factors
			}

				if(strlen($risk_factor) < 1){
					//then we skip this
				}else{
					$risk_factors[]=str_replace("_"," ",$risk_factor);
				}
			
			}
			$result->MoveNext();
		}

		if(count($risk_factors)>0){
			$risk_string = implode('<br>',$risk_factors);
		}else{
			$risk_string = "";
		}
		return($risk_string);


	}

*/



	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param string 
 	*
 	* @return string
        */
	function doc($plan_id, $row){

		$provider_id = $row['provider_id'];
		if($provider_id == 0){
			return $row['provider_text'];

		}

		$doc = $row['doc'];
		$provider =& Celini::newORDO('Provider',$row['provider_id']);
		$skypeid = $provider->getIdentifierByType('skypeid');
		if($skypeid){
			$skype = $this->_skype_button($skypeid);
		}else{
			$skype = '';
		}
		return("$doc <br> $skype");
		return($doc);

	}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	*
 	* @return mixed 
        */
	function _skype_button($skype_id){


     	$use_javascript_skype_detection = true;   
	$detection_javascript = 
		"
		<script type='text/javascript'
		src='/pics/skypeCheck.js'>
		</script>
		";

	$detection_onclick = 
		"onclick='return skypeCheck();'";


	$ballon = 'http://mystatus.skype.com/balloon/';
	$bigclassic = 'http://mystatus.skype.com/bigclassic/';
	$smallclassic =	'http://mystatus.skype.com/smallclassic/';
	$smallicon =	'http://mystatus.skype.com/smallicon/';
	$mediumicon = 	'http://mystatus.skype.com/mediumicon/';
	$icon_proxy = 	'/pics/skypestatus.php?u=';
	// the icon proxy is needed to get the icon into https without a cert problem/https error

	$logo = $icon_proxy; // You can change the icon used here!!



	$contact = $skype_id;


		
		$return .= $detection_javascript . "\n\n";
		$onclick = $detection_onclick ;
		$logo = $logo . $contact;

	$return .= "	
			<!-- from the Plan Datasource -->
			<a href='skype:$contact?call' $onclick>
			<img src='$logo' style='border: none;' alt='skype' />
			</a>
		";
	
	$return = preg_replace('#\r?\n#','',$return);

	return($return); 

	}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	*
 	* @return string 
        */
	function medsInterventions($plan_id){

	$sql = "
		SELECT 
                        storage_text.foreign_key,
			storage_text.value as text_value
		FROM 
			storage_text 
		WHERE 
			storage_text.foreign_key = $plan_id AND
			storage_text.value_key = 'interventions'
		ORDER BY foreign_key DESC

		";

		$result = $this->_db->Execute($sql);

		if ($result && !$result->EOF) {
			$intervention = $result->fields['text_value'];
		}else{
			$intervention = "no interventions recorded";
		}	


		return($intervention);

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	*
 	* @return string 
        */
	function goals($plan_id){

/*
SQL for getting goals
SELECT 
storage_text.value as text_value
FROM plans 
RIGHT JOIN 
form_data ON form_data.external_id = plans.id 
RIGHT JOIN storage_text ON storage_text.foreign_key = form_data.form_data_id
WHERE patient_id = 600004 AND
storage_text.value_key = 'goals'
*/

	$sql = "
		SELECT 
                        storage_text.foreign_key,
			storage_text.value as text_value
		FROM 
			storage_text 
		WHERE 
			storage_text.foreign_key = $plan_id AND
			storage_text.value_key = 'goals'
		ORDER BY foreign_key DESC

		";

		$result = $this->_db->Execute($sql);

		if ($result && !$result->EOF) {
			$goals = $result->fields['text_value'];
		}else{
			$goals = "no goals recorded";
		}	

		return($goals);


	}


	
	 /**	
         * short comment
         *
	 * Load encounter_reason enum if necessary
	 *
	 * @access private 
 	 *
 	 * @return void 
         */
	function _lookupStatusList() {
		if (!is_null($this->_planStatus)) {
			return;
		}
		
		$enum = ORDataObject::factory('Enumeration');
		$this->_planStatus = $enum->get_enum_list('plan_status');
	}
	
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	*
 	* @return mixed
        */	
	function planStatus($id) {
		$this->_lookupStatusList();
		if (isset($this->_planStatus[$id])) {
			return $this->_planStatus[$id];
		}
	}
	/**	
        * short comment
        *
	* long comment for the class method below
 	*
 	* @return void 
        */
	function _lookupTypeList() {
		if (!is_null($this->_planType)) {
			return;
		}
		
		$enum = ORDataObject::factory('Enumeration');
		$this->_planType = $enum->get_enum_list('protocols');
	}
	
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	*
 	* @return string 
        */	
	function planType($id) {
		$this->_lookupTypeList();
		if (isset($this->_planType[$id])) {
			return $this->_planType[$id];
		}
	}



}

