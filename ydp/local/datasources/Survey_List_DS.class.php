<?php
/**
 * This is C_My.class.php
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
 * @see 
 */
$loader->requireOnce( '/includes/Datasource_sql.class.php');
/**
 * The Datasource for listing Surveys	
 * @package ydp
 */
class Survey_List_DS extends Datasource_sql 
{
	var $_surveyStatus = null;
	
	
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
 	* @param array 
 	* 
 	* @return mixed 
        */	
	function Survey_List_DS($args) {

	
		if(is_array($args)){

			if(array_key_exists('patient_id',$args)){
					$patient_id = $args['patient_id'];
			}else{
					$patient_id =0;
			}
			if(array_key_exists('encounter_id',$args)){
					$encounter_id = $args['encounter_id'];
			}else{
					$encounter_id = 0;
			}
			if(array_key_exists('outstanding',$args)){
					$outstanding = $args['outstanding'];
			}else{
					$outstanding = false;
			}

		}else{
			$encounter_id = 0;
			$outstanding = false;	
			$patient_id = $args;
		}	

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


		$date_format = DateObject::getFormat();

		$this->setup(Celini::dbInstance(), 
			array(
				'cols' 	=> "
					survey.survey_id as id,
					survey.status,
					survey.patient_id as patient_id,
					concat_ws( ' ', p.last_name, p.first_name ) as patient_name,
					p.email as patient_email,
					form.name as form_name,
					date_format(e.date_of_treatment, '$date_format') as date_of_treatment,
					concat_ws( ' ', provider.last_name, provider.first_name ) as provider_name,
					date_format(survey.next_contact_date, '$date_format') as next_contact_date,
					contacted,
					to_contact
					",
				'from' 	=> "
					`survey`
					LEFT JOIN person p ON survey.patient_id = p.person_id
					LEFT JOIN form ON survey.form_id = form.form_id
					LEFT JOIN encounter e ON survey.external_id = e.encounter_id 
					LEFT JOIN person provider ON e.treating_person_id = provider.person_id
					",
				'where' => "$where_string",
			//	'orderby' => 'date_of_treatment DESC'
			),
			array(	
				'id' => "Survey ID",
				'status' => "Status",
				'patient_name' => 'Patient Name',
				'form_name' => 'Survey Form',
				'date_of_treatment' => 'Treatment Date',
				'provider_name' => 'Provider Name', 
			//	'next_contact_date' => 'Date of Next Contact', forms to big...
				'provider_name' => 'Treated By', 
				'contacted' => 'Trys',
				'to_contact' => 'Total Trys',
				'patient_email' => 'Email Survey'
				));

		//$this->orderHints['building'] = 'b.name';
		$this->registerFilter('status',array(&$this,'surveyStatus'));
	//	echo $this->preview();
	}
	
	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return void 
        */
	function _lookupSurveyStatusList() {
		if (!is_null($this->_surveyStatus)) {
			return;
		}
		
		$enum = ORDataObject::factory('Enumeration');
		$this->_surveyStatus = $enum->get_enum_list('survey_status');
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
	function surveyStatus($id) {
		$this->_lookupSurveyStatusList();


		if (isset($this->_surveyStatus[$id])) {
			return $this->_surveyStatus[$id];
		}else{
			return "status broken";
		}
		
	}

/*
//The SQL code that this is based on.
//This version shows you what is going on
SELECT 
survey.id,
concat_ws( ' ', p.last_name, p.first_name ) patient_name,
survey.patient_id,
form.name,
survey.form_id,
date_format(e.date_of_treatment, '%m/%d/%Y') as date_of_treatment,
concat_ws( ' ', provider.last_name, provider.first_name ) provider_name,
survey.external_id,
date_format(survey.next_contact_date, '%m/%d/%Y') as next_contact_date,
contacted,
to_contact
FROM `survey`
JOIN person p ON survey.patient_id = p.person_id
JOIN form ON survey.form_id = form.form_id
JOIN encounter e ON survey.external_id = e.encounter_id 
JOIN person provider ON e.treating_person_id = provider.person_id

//This version makes more of a report
SELECT 
survey.id,
concat_ws( ' ', p.last_name, p.first_name ) patient_name,
form.name as form_name,
date_format(e.date_of_treatment, '%m/%d/%Y') as date_of_treatment,
concat_ws( ' ', provider.last_name, provider.first_name ) provider_name,
date_format(survey.next_contact_date, '%m/%d/%Y') as next_contact_date,
contacted,
to_contact
FROM `survey`
JOIN person p ON survey.patient_id = p.person_id
JOIN form ON survey.form_id = form.form_id
JOIN encounter e ON survey.external_id = e.encounter_id 
JOIN person provider ON e.treating_person_id = provider.person_id


*/

}

