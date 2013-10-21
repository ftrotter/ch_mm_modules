<?php

$loader->requireOnce('includes/NewCropData.class.php');
$loader->requireOnce('datasources/RegisterProvider_DS.class.php');
class C_NewCrop extends Controller {
	

	function C_NewCrop ($template_mod = "general") {

		parent::Controller();
		$this->template_mod = $template_mod;
	}

	function actionList_view($patient_id) {
		if(!is_numeric($patient_id) || $patient_id < 0){
			return "Error: Missing patient id";
		}	

		$this->_get_current_drug_info($patient_id,$this);

		return $this->view->render("list.html");
	}


	function actionShortlist_view($patient_id) {
		if(!is_numeric($patient_id) || $patient_id < 0){
			return "Error: Missing patient id";
		}	

		$this->_get_current_drug_info($patient_id,$this);

		return $this->view->render("shortlist.html");
	}









	function _get_current_drug_info($patient_id,$controller){
	$two_years_ahead = date('Y', strtotime("+2 year"));


 	$cred_array = $GLOBALS['config']['ERXCredentials'];
 	$wsdl_url = $GLOBALS['config']['ERXwsdl'];
	$user = $controller->_me->get_user();
	$user_id = $user->get('person_id');

	$params   = array(
        'credentials' => array(
                'PartnerName' => $cred_array['partnerName'],
                'Name' => $cred_array['name'],
                'Password' => $cred_array['password']),
        'accountRequest' => array(
                'AccountId' => $cred_array['accountId'],
                'SiteId' => $cred_array['siteId']
                ),
        'patientRequest' => array(
                'PatientId' => $patient_id
                ),
        'prescriptionHistoryRequest' => array(
                'StartHistory' => '2004-01-01T00:00:00.000',
                'EndHistory' => "$two_years_ahead-01-01T00:00:00.000",
                'PrescriptionStatus'          => 'C',
                'PrescriptionSubStatus'       => 'S',
                'PrescriptionArchiveStatus'       => 'N',
                ),
        'patientInformationRequester' => array(
                'UserType' => 'MidLevel',
                'UserId' => "$user_id"
                ),
      'patientIdType' => 'hellifiknow',
      'includeSchema' => 'Y',
	);
		
	$controller->assign('newcrop_show',Celini::link('show','NewCrop')."patient_id=$patient_id");
	$controller->assign('newcrop_list',Celini::link('list','NewCrop')."patient_id=$patient_id");
	$client = new SoapClient($wsdl_url);
	$meds    = $client->GetPatientFullMedicationHistory5($params);
	if(isset($meds->GetPatientFullMedicationHistory5Result->patientDrugDetail->PatientDrugDetail5)){
		$meds_obj_array = $meds->GetPatientFullMedicationHistory5Result->patientDrugDetail->PatientDrugDetail5;
	}else{
		$meds_obj_array = array();
	}
	$meds_array = array();

	//echo "<br><br><br><br>";
	//var_export($meds_obj_array);

	if(is_object($meds_obj_array)){
		$meds_obj_array = array($meds_obj_array);
	}


	foreach($meds_obj_array as $id => $obj_array){
		$tmp = array();
		foreach($obj_array as $name => $value){
			$tmp[$name] = $value;
		}
		$meds_array[] = $tmp;	
	}
		
	$controller->assign('meds_array',$meds_array);
	$this->_get_renewals_count($controller);
	$this->_get_current_allergy_info($patient_id, $controller);

	}


	function _get_current_allergy_info($patient_id,$controller){
	$two_years_ahead = date('Y', strtotime("+2 year"));


 	$cred_array = $GLOBALS['config']['ERXCredentials'];
 	$wsdl_url = $GLOBALS['config']['ERXwsdl'];
	$user = $controller->_me->get_user();
	$user_id = $user->get('person_id');

	$params   = array(
        'credentials' => array(
                'PartnerName' => $cred_array['partnerName'],
                'Name' => $cred_array['name'],
                'Password' => $cred_array['password']),
        'accountRequest' => array(
                'AccountId' => $cred_array['accountId'],
                'SiteId' => $cred_array['siteId']
                ),
        'patientRequest' => array(
                'PatientId' => $patient_id
                ),
        'patientInformationRequester' => array(
                'UserType' => 'MidLevel',
                'UserId' => "$user_id"
                ),
      'patientIdType' => 'hellifiknow',
      'includeSchema' => 'Y',
	);
		
	$client = new SoapClient($wsdl_url);
	$allergy    = $client->GetPatientAllergyHistory2($params);
	if(isset($allergy->GetPatientAllergyHistory2Result->patientAllergyExtendedDetail->PatientAllergyExtendedDetail)){
		$allergy_obj_array = $allergy->GetPatientAllergyHistory2Result->patientAllergyExtendedDetail->PatientAllergyExtendedDetail;
	}else{
		$allergy_obj_array = array();
	}
	$allergy_array = array();

	//echo "<br><br><br><br>";
	//var_export($meds_obj_array);

	if(is_object($allergy_obj_array)){
		$allergy_obj_array = array($allergy_obj_array);
	}


	foreach($allergy_obj_array as $id => $obj_array){
		$tmp = array();
		foreach($obj_array as $name => $value){
			$tmp[$name] = $value;
		}
		$allergy_array[] = $tmp;	
	}

//	echo "<br><br><br>";
//	var_export($allergy_array);

		
	$controller->assign('allergy_array',$allergy_array);

	}





	function _get_renewals_count($controller){

	$two_years_ahead = date('Y', strtotime("+2 year"));
 	$cred_array = $GLOBALS['config']['ERXCredentials'];
 	$wsdl_url = $GLOBALS['config']['ERXwsdl'];
	$user = $controller->_me->get_user();
	$user_id = $user->get('user_id');
	$userProfile = new UserProfile($user->get('user_id'));
	$rid = $userProfile->getDefaultLocationId();


	$room = ORDataObject::factory('Room',$rid);		
	$building_id = $room->get('building_id');

	$building = ORDataObject::factory('Building',$building_id);
	$NewCropData = new NewCropData($user,$building);

	$db = new clniDb();


	$params   = array(
        'credentials' => array(
                'PartnerName' => $cred_array['partnerName'],
                'Name' => $cred_array['name'],
                'Password' => $cred_array['password']),
        'accountRequest' => array(
                'AccountId' => $cred_array['accountId'],
                'SiteId' => $cred_array['siteId']
                ),
        'locationId' => $building_id,
        'licensedPrescriberId' => $user_id,
                
 	);
		
	$client = new SoapClient($wsdl_url, array('trace' => 1));
//	echo "<br><br><br><br>";
//	var_export($renewalResults);

	try {
		$results    = $client->GetAllRenewalRequests($params);
	}  catch (SoapFault $soapFault) {
		$controller->messages->addMessage('SOAP Error!!');
		$controller->messages->addMessage(var_export($soapFault,true));

		echo var_export($soapFault,true);
	}
	



	$renewals_count = $results->GetAllRenewalRequestsResult->result->RowCount;
	$controller->assign('renewals_count',$renewals_count);
	$controller->assign('newcrop_renewals',Celini::link('getAllRenewals','NewCrop'));



	if(isset($results->GetAllRenewalRequestsResult->renewalSummaryArray)){
		$renewalSummaryArray = $results->GetAllRenewalRequestsResult->renewalSummaryArray;
		
	//	var_export($renewalSummaryArray);

	//	echo "<br><br>";
		foreach($renewalSummaryArray as $id => $obj_array){

			foreach($obj_array as $RenewalsSummary => $RenwalsSummaryArray){
		
			$tmp = array();
				foreach($RenwalsSummaryArray as $name => $value){
					$tmp[$name] = $value;
				}

			$renewals_array[] = $tmp;	
			}
		}
	//	var_export($renewals_array);

		$early_time = time();		

		foreach($renewals_array as $id => $renewal){
			$renewal_date = substr($renewal['ReceivedTimestamp'],0,9);
			$dob_year = substr($renewal['PatientDOB'],0,4);
			$dob_month = substr($renewal['PatientDOB'],4,2);
			$dob_day = substr($renewal['PatientDOB'],6,2);
			
			$time = strtotime($renewal['ReceivedTimestamp']);
			if($time < $early_time){
				$early_time = $time;
			}
		}

		$controller->assign('renewals_date_from',date("l \\t\h\e jS",$early_time));

		if($early_time < strtotime('-4 day')){
			// good lord these renewals are late!!
			//even more than a weekend!!
			$controller->assign('renewals_date_color','red');
		}else{
			$controller->assign('renewals_date_color','black');

		}


	}


	}


	function actionGetAllRenewals(){

	$two_years_ahead = date('Y', strtotime("+2 year"));
	$controller = $this;
 	$cred_array = $GLOBALS['config']['ERXCredentials'];
 	$wsdl_url = $GLOBALS['config']['ERXwsdl'];
	$user = $controller->_me->get_user();
	$user_id = $user->get('user_id');
	$userProfile = new UserProfile($user->get('user_id'));
	$rid = $userProfile->getDefaultLocationId();


	$room = ORDataObject::factory('Room',$rid);		
	$building_id = $room->get('building_id');

	$building = ORDataObject::factory('Building',$building_id);
	$NewCropData = new NewCropData($user,$building);

	$db = new clniDb();


	$params   = array(
        'credentials' => array(
                'PartnerName' => $cred_array['partnerName'],
                'Name' => $cred_array['name'],
                'Password' => $cred_array['password']),
        'accountRequest' => array(
                'AccountId' => $cred_array['accountId'],
                'SiteId' => $cred_array['siteId']
                ),
        'locationId' => $building_id,
        'licensedPrescriberId' => $user_id,
                
 	);
		
	$client = new SoapClient($wsdl_url, array('trace' => 1));
//	echo "<br><br><br><br>";
//	var_export($renewalResults);

	try {
		$results    = $client->GetAllRenewalRequests($params);
	}  catch (SoapFault $soapFault) {
		$this->messages->addMessage('SOAP Error!!');
		$this->messages->addMessage(var_export($soapFault,true));

		echo var_export($soapFault,true);
	}
	



	$results_count = $results->GetAllRenewalRequestsResult->result->RowCount;
	$this->assign('results_count',$results_count);

	if(isset($results->GetAllRenewalRequestsResult->renewalSummaryArray)){
		$renewalSummaryArray = $results->GetAllRenewalRequestsResult->renewalSummaryArray;
		
	//	var_export($renewalSummaryArray);

	//	echo "<br><br>";
		foreach($renewalSummaryArray as $id => $obj_array){

			foreach($obj_array as $RenewalsSummary => $RenwalsSummaryArray){
		
			$tmp = array();
				foreach($RenwalsSummaryArray as $name => $value){
					$tmp[$name] = $value;
				}

			$renewals_array[] = $tmp;	
			}
		}
	//	var_export($renewals_array);

		
		foreach($renewals_array as $id => $renewal){

			$fname = $renewal['PatientFirstName'];
			$lname = $renewal['PatientLastName'];
			$dob_year = substr($renewal['PatientDOB'],0,4);
			$dob_month = substr($renewal['PatientDOB'],4,2);
			$dob_day = substr($renewal['PatientDOB'],6,2);
			$sql_dob = "$dob_year-$dob_month-$dob_day";

			$patient_search_sql = "
 SELECT `person_id` , `last_name` , `first_name` , `middle_name` , `date_of_birth`
FROM `person`
WHERE `last_name` LIKE '$lname'
AND `first_name` LIKE '$fname'
AND `date_of_birth` = '$sql_dob'
";


		//echo $patient_search_sql . "<br>";

		$matching_patients = $db->getAssoc($patient_search_sql);	


		if(count($matching_patients) > 1){
			//then too many patients match. 
			//this is an error condition that must be manually dealt with for now.
	
			$match_url = '';
	
			foreach($matching_patients as $patient_id => $patient_array){
				$match_url .= "Patient #$patient_id <a href='". Celini::link('view','PatientDashboard')."id=$patient_id"
					."'> ".$patient_array['first_name'] . ", " . $patient_array['last_name'] . "</a> Matches <br>";


			}


			$renewals_array[$id]['match_result'] = 'More than one patient matches this renewal request: <br> No Action is possible contact your vendor';
			$renewals_array[$id]['match_url'] = $match_url;
			
			

		}

		if(count($matching_patients) == 1){

			$patient_id = key($matching_patients);
		
			$renewals_array[$id]['patient_url'] = Celini::link('PatientDashboard','view')."patient_id=$patient_id";

			//then too many patients match. 
			//this is an error condition that must be manually dealt with for now.
			$renewals_array[$id]['match_result'] = 'A single matching patient was found';
			$renewals_array[$id]['match_url'] = 
						"<a href='".
						Celini::link('Renewal','NewCrop').
						"response=Accept&patient_id=$patient_id&renewal_key=". $renewal['RenewalRequestGuid'] . 
						"'> Accept Renewal </a>  or ". 
						"<a href='". 
						Celini::link('Renewal','NewCrop').
						"response=Deny&patient_id=$patient_id&renewal_key=". $renewal['RenewalRequestGuid'] . 
						"'> Deny Renewal </a>"; 





		}

		if(count($matching_patients) == 0){
			//then too many patients match. 
			//this is an error condition that must be manually dealt with for now.

		

			$renewals_array[$id]['match_result'] = 'No Patients ';
			$renewals_array[$id]['match_url'] = 
					'<a target="_blank" href=" '.
					Celini::link('Add','Patient').
					'">Add This Patient</a>' .

					" or <a href='".
					Celini::link('Renewal','NewCrop').
					"response=UnableToProcess&renewal_key=". $renewal['RenewalRequestGuid'] .
					"'> Reject this renewal </a>";
		}



		}
		
		$this->assign('renewals_array',$renewals_array);
	}

	$response_display_txt = $NewCropData->xml_pretty_printer($client->__getLastResponse(),true);
	$request_display_txt = $NewCropData->xml_pretty_printer($client->__getLastRequest(),true);

	$this->view->assign_by_ref('response_txt',$response_display_txt);
	$this->view->assign_by_ref('request_txt',$request_display_txt);

	$this->view->assign('status',$results->GetAllRenewalRequestsResult->result->Status);
	$this->view->assign('message',$results->GetAllRenewalRequestsResult->result->Message);

	return($this->view->render("renewals.html"));	


	}



	function actionRegisterSite(){


	 	$cred_array = $GLOBALS['config']['ERXCredentials'];
 		$wsdl_url = $GLOBALS['config']['ERXDoctorwsdl'];

		$user =& User::fromPersonId($person_id);
		$userProfile = new UserProfile($user->get('user_id'));
		$building = ORDataObject::factory('Building',$building_id);

		if($building_id == 0){
			$rid = $userProfile->getDefaultLocationId();
			$room = ORDataObject::factory('Room',$rid);		
			$building_id = $room->get('building_id');
		}

		$building = ORDataObject::factory('Building',$building_id);
		$NewCropData = new NewCropData($user,$building);




	$params   = array(
        'credentials' => array(
                'PartnerName' => $cred_array['partnerName'],
                'Name' => $cred_array['name'],
                'Password' => $cred_array['password']),
        'accountRequest' => array(
                'AccountId' => $cred_array['accountId'],
                'SiteId' => $cred_array['siteId']
                ),
	'xmlIn' => $register_xml
	);

		$client = new SoapClient($wsdl_url, array('trace' => 1));
		try {
			$results = $client->RegisterLicensedPrescriber($params);
		}  catch (SoapFault $soapFault) {
			$this->messages->addMessage('SOAP Error!!');
			$this->messages->addMessage(var_export($soapFault,true));

			echo var_export($soapFault,true);
		}

		$response_display_txt = $NewCropData->xml_pretty_printer($client->__getLastResponse(),true);
		$request_display_txt = $NewCropData->xml_pretty_printer($client->__getLastRequest(),true);
	
		$this->view->assign_by_ref('response_txt',$response_display_txt);
		$this->view->assign_by_ref('request_txt',$request_display_txt);

		$this->view->assign('status',$results->RegisterLicensedPrescriberResult->result->Status);
		$this->view->assign('message',$results->RegisterLicensedPrescriberResult->result->Message);
		return($this->view->render("soapcall.html"));



//		$status = $results->RegisterLicensedPrescriberResult->result->Status;
//		$message = $results->RegisterLicensedPrescriberResult->result->Message;


//		return("Status = $status <br> Message = $message");

	}


// This function registers a provider with surescripts
// This registration takes place on a per-location basis.
// If given no arguement the system registers the location set for the provider in the user account
// if the building id is given, then it registers the provider with that building.
// The function should also list the registration status for this provider with each location id
// that has been used at this account/site with a link back to this function for registration.
// inorder to register a provider, the RegisterLicensedPrescriber SOAP call is used
// however at subsequent locations, the 


	function actionRegisterIndividual($person_id,$building_id = 0){


	 	$cred_array = $GLOBALS['config']['ERXCredentials'];
 		$wsdl_url = $GLOBALS['config']['ERXDoctorwsdl'];

		$user =& User::fromPersonId($person_id);
		$userProfile = new UserProfile($user->get('user_id'));
		if($building_id == 0){
			$rid = $userProfile->getDefaultLocationId();
			$room = ORDataObject::factory('Room',$rid);		
			$building_id = $room->get('building_id');
		}

		$building = ORDataObject::factory('Building',$building_id);
		$NewCropData = new NewCropData($user,$building);

		$register_xml = $NewCropData->registerLicensedPrescriberXML($user,$building);

		$params   = array(
       	 	'credentials' => array(
       	        	'PartnerName' => $cred_array['partnerName'],
                	'Name' => $cred_array['name'],
                	'Password' => $cred_array['password']),
        	'accountRequest' => array(
                	'AccountId' => $cred_array['accountId'],
                	'SiteId' => $cred_array['siteId']
                ),
		'xmlIn' => $register_xml
		);

		$client = new SoapClient($wsdl_url, array('trace' => 1));
		try {
			$results = $client->RegisterLicensedPrescriber($params);
		}  catch (SoapFault $soapFault) {
			$this->messages->addMessage('SOAP Error!!');
			$this->messages->addMessage(var_export($soapFault,true));

			echo var_export($soapFault,true);
		}

		$response_display_txt = $NewCropData->xml_pretty_printer($client->__getLastResponse(),true);
		$request_display_txt = $NewCropData->xml_pretty_printer($client->__getLastRequest(),true);
	
		$this->view->assign_by_ref('response_txt',$response_display_txt);
		$this->view->assign_by_ref('request_txt',$request_display_txt);

		$this->view->assign('status',$results->RegisterLicensedPrescriberResult->result->Status);
		$this->view->assign('message',$results->RegisterLicensedPrescriberResult->result->Message);
		return($this->view->render("soapcall.html"));



//		$status = $results->RegisterLicensedPrescriberResult->result->Status;
//		$message = $results->RegisterLicensedPrescriberResult->result->Message;


//		return("Status = $status <br> Message = $message");

	}





	//This is a testing function to generate test renewals in preproduction
	//This should not be used in production environment.
	function actionTestRenewals($person_id){

	if(!isset($_GET['patient_id'])){
		//we need a patient id... ask for one
		$link = Celini::link('testRenewals','NewCrop')."/person_id=$person_id";

		return "
To create a test renewals for a patient at NewCrop enter the id of the test patient here. <br> 
<form method='GET' action='$link'>
<input type='hidden' name='person_id' value='$person_id'>
<input type='text' name='patient_id'><br>
<input type='submit'>
</form>";
	}else{

		$patient_id = $_GET['patient_id'];

	}
	$controller = $this;
	$two_years_ahead = date('Y', strtotime("+2 year"));

	$patient =& Celini::newOrdo('Patient',$patient_id);
 	$cred_array = $GLOBALS['config']['ERXCredentials'];
 	$wsdl_url = $GLOBALS['config']['ERXUpdate1wsdl'];
	$user =& User::fromPersonId($person_id);
	$user_id = $user->get('person_id');
	$userProfile = new UserProfile($user->get('user_id'));
	$rid = $userProfile->getDefaultLocationId();
	$room = ORDataObject::factory('Room',$rid);
	$building_id = $room->get('building_id');
	$building = ORDataObject::factory('Building',$building_id);

	$NewCropData = new NewCropData($user,$building,$patient);

	// data values taken from the example here...
	//http://preproduction.newcropaccounts.com/InterfaceV7/GenTestRenewalFDB.xml
	$NewCropData->PharmacyIdentifier = '1231212';
	$NewCropData->DrugIdentifier = '228042';	
	$NewCropData->DrugIdentifierType = 'FDB';	
	$NewCropData->DispenseNumber = '30';	
	$NewCropData->Dosage = 'Take 1 in the morning';	
	$NewCropData->RefillCount = '2';	
	$NewCropData->Substitution = 'SubstitutionAllowed';	
	$NewCropData->PharmacistMessage = 'No child proof caps please';	


	$renewal_xml = $NewCropData->requestTestRenewalsXML();

	$params   = array(
        'credentials' => array(
                'PartnerName' => $cred_array['partnerName'],
                'Name' => $cred_array['name'],
                'Password' => $cred_array['password']),
        'accountRequest' => array(
                'AccountId' => $cred_array['accountId'],
                'SiteId' => $cred_array['siteId']
                ),
	'xmlIn' => $renewal_xml,
	'createCurrentMedFromRxInfo' => 'true',
	'originalPrescriptionFillDate' => '2008-08-10T00:00:00.000',

	);

//	echo($NewCropData->xml_pretty_printer($renewal_xml,true));
//	return($NewCropData->xml_pretty_printer($renewal_xml));

		
	$client = new SoapClient($wsdl_url, array('trace' => 1));

	try {
		$results    = $client->GenerateTestRenewalRequest($params);
	} catch (SoapFault $soapFault) {
		$this->messages->addMessage('SOAP Error!!');
		$this->messages->addMessage(var_export($soapFault,true));	
	}
	
	$response_display_txt = $NewCropData->xml_pretty_printer($client->__getLastResponse(),true);
	$request_display_txt = $NewCropData->xml_pretty_printer($client->__getLastRequest(),true);
	
	$this->view->assign_by_ref('response_txt',$response_display_txt);
	$this->view->assign_by_ref('request_txt',$request_display_txt);

	$this->view->assign('status',$results->GenerateTestRenewalRequestResult->Status);
	$this->view->assign('message',$results->GenerateTestRenewalRequestResult->Message);
	return($this->view->render("soapcall.html"));
	//<br><br><br><br> status = $status <br> message = $message");
	

	}




	function actionRegisterList_edit() {

		$user = $this->_me->get_user();
	
		$ds =& new RegisterProvider_DS();
		$grid =& new cGrid($ds);
		$grid->name = "userGrid";
		$this->view->assign_by_ref('grid',$grid);
		return $this->view->render("user.html");

	}



	function actionShow_view() {

	//	$myGet = var_export($_GET, true);
	//	$myPost = var_export($_POST, true);
	//	$bob = $_GET['bob'];
	//	$this->assign('myGet',$myGet);
        //	$this->assign('myPost',$myPost);

		if($this->GET->exists('patient_id')){
			$patient_id = $this->GET->getTyped('patient_id','int'); 
		
		}else{

			if($this->POST->exists('patient_id')){
				$patient_id = $this->POST->getTyped('patient_id','int');
			}
	
		}
	

		if(!isset($patient_id)){
			$patient_id = $this->get('patient_id','c_patient');
		}
	
		if($this->GET->exists('episode_id')){
			$episode_id = $this->GET->get('episode_id'); 
		
		}else{

			if($this->POST->exists('episode_id')){
				$episode_id = $this->POST->get('episode_id');
			}
	
		}

		
		if(isset($patient_id)){

        		$this->assign('patient_id',$patient_id);
        	
		}else{
			$this->messages->addMessage('This function requires a patient_id');
			//return;
		}	
		if(isset($episode_id)){

        		$this->assign('episode_id',$episode_id);
		}else{
			$episode_id = 0;
		}
		$person =& ORDataObject::factory('Patient',$patient_id);
		$user = $this->_me->get_user();
		$user_id = $user->get('user_id');
		if($user_id == 1){
			return("Error: you can't very well prescribe as admin... can you??");

		}

		$encounter =& ORDataObject::factory('Encounter');
		$encounter_ds = $encounter->encounterList($patient_id);
		$encounter_array = $encounter_ds->toArray();
		//var_export($encounter_array);
		$biggest_encounter_id = 0;
		foreach($encounter_array as $key => $this_encounter){

			if(isset($this_encounter['building'])){
				if($this_encounter['encounter_id'] > $biggest_encounter_id){
					$biggest_encounter_id = $this_encounter['encounter_id'];
			}
		}
		}
		if($biggest_encounter_id == 0){

			$ps =& Celini::newOrdo('PatientStatistics',$patient_id);
			$building_id = $ps->get('registration_location');
			if($building_id == 0){

				$this->messages->addMessage('In order to prescribe, this patient must have an encounter with a building assigned to it, or have a registration location set.');
				return;
			}

			$building =& ORDataObject::factory('Building',$building_id);

		}else{
		//echo "biggest_encounter_id = $biggest_encounter_id";	
			$encounter =& ORDataObject::factory('Encounter',$biggest_encounter_id);
			$building =& ORDataObject::factory('Building',$encounter->get('building_id'));
		}
	//	$episode_identifier =& ORDataObject::factory('EpisodeID', 

		$NewCropData = new NewCropData($user,$building,$person,$episode_id);
	//	$drug_array = $NewCropData->get_NewCrop_data(&$person,&$user,&$building);
	//	$this->assign_by_ref('drug_data',$drug_array);

		$drug_button_template = $NewCropData->get_auto_button();
	
		if( ! $NewCropData->isValid()){

			$this->messages->addMessage($NewCropData->getError());
			return;		
		}else{
			$this->assign('drug_button', $drug_button_template);
			return $this->view->render("show.html");
		}



        }

	function actionManage_view() {

	//	$myGet = var_export($_GET, true);
	//	$myPost = var_export($_POST, true);
	//	$bob = $_GET['bob'];
	//	$this->assign('myGet',$myGet);
        //	$this->assign('myPost',$myPost);

		$user = $this->_me->get_user();
		//var_export($encounter_array);
		$user_id = $user->get('person_id');
		$userProfile = new UserProfile($user->get('user_id'));
		$rid = $userProfile->getDefaultLocationId();
		$room = ORDataObject::factory('Room',$rid);
		$building_id = $room->get('building_id');
		$building = ORDataObject::factory('Building',$building_id);
		$NewCropData = new NewCropData($user,$building);
	//	$drug_array = $NewCropData->get_NewCrop_data(&$person,&$user,&$building);
	//	$this->assign_by_ref('drug_data',$drug_array);

		$drug_button_template = $NewCropData->get_auto_button('manage');
	
		if( ! $NewCropData->isValid()){

			$this->messages->addMessage($NewCropData->getError());
			return;		
		}else{
			$this->assign('drug_button', $drug_button_template);
			return $this->view->render("show.html");
		}



        }

	function actionRenewal_view($response,$patient_id,$renewal_key) {

	//	$myGet = var_export($_GET, true);
	//	$myPost = var_export($_POST, true);
	//	$bob = $_GET['bob'];
	//	$this->assign('myGet',$myGet);
        //	$this->assign('myPost',$myPost);



		$user = $this->_me->get_user();
		//var_export($encounter_array);
		$user_id = $user->get('person_id');
		$userProfile = new UserProfile($user->get('user_id'));
		$rid = $userProfile->getDefaultLocationId();
		$room = ORDataObject::factory('Room',$rid);
		$building_id = $room->get('building_id');
		$building = ORDataObject::factory('Building',$building_id);
		$patient =& ORDataObject::Factory('Patient',$patient_id);
		$NewCropData = new NewCropData($user,$building,$patient);


		$NewCropData->RenewalRequestIdentifier = $renewal_key;


		if(strcmp(strtolower($response),'accept') == 0){
			$NewCropData->RenewalsResponseCode = 'Accept';
			$NewCropData->RefillCount = '1';	
			$NewCropData->DrugSchedule = 'None';	
		}

		if(strcmp(strtolower($response),'deny') == 0){
			$NewCropData->RenewalsResponseCode = 'Deny';
			$NewCropData->RefillCount = '0';	
			$NewCropData->DrugSchedule = 'None';	
//			$NewCropData->ResponseDenyCode = 'PatientHasRequestedRefillTooSoon';
		}

		if(strcmp(strtolower($response),'unabletoprocess') == 0){
			$NewCropData->RenewalsResponseCode = 'UnableToProcess';
			$NewCropData->RefillCount = '0';	
			$NewCropData->DrugSchedule = 'Unknown';	
//			$NewCropData->ResponseDenyCode = 'PatientHasRequestedRefillTooSoon';
		}

		$this->assign('link_back',"<a href='".Celini::link('getAllRenewals','NewCrop')."'>Return to Renewals List</a><br>");

//		$NewCropData->PharmacistMessage;

		$drug_button_template = $NewCropData->get_auto_button('renewal');
	
		if( ! $NewCropData->isValid()){

			$this->messages->addMessage($NewCropData->getError());
			return;		
		}else{
			$this->assign('drug_button', $drug_button_template);
			return $this->view->render("show.html");
		}



        }



}
?>
