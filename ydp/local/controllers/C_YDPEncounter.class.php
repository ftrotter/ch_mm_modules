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
 * @see "controllers/Controller.class.php"
 */
$loader->requireOnce("controllers/Controller.class.php");
/**
 * @see "ordo/ORDataObject.class.php"
 */
$loader->requireOnce("ordo/ORDataObject.class.php");
/**
 * @see "includes/Grid.class.php"
 */
$loader->requireOnce("includes/Grid.class.php");
/**
 * @see "includes/NewCropData.class.php"
 */
$loader->requireOnce("includes/NewCropData.class.php");
/**
 * @see "datasources/Plan_FormDataList_DS.class.php"
 */
$loader->requireOnce("datasources/Plan_FormDataList_DS.class.php");
/**
 * @see "lib/PEAR/SOAP/Client.php"
 */
$loader->requireOnce("lib/PEAR/SOAP/Client.php");
/**
 * @see "lib/PEAR/SOAP/Value.php"
 */
$loader->requireOnce("lib/PEAR/SOAP/Value.php");

//require_once APP_ROOT ."/local/datasources/Patient_PlanList_DS.class.php";
/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class C_YDPEncounter extends Controller {

	/**	
         * @var int
         */
	var $number_id = 0;
	/**	
         * @var int
         */
	var $address_id = 0;
	/**	
         * @var int
         */
	var $identifier_id = 0;
	/**	
         * @var int
         */
	var $insured_relationship_id = 0;
	/**	
         * @var int
         */
	var $person_person_id = 0;
	/**	
         * @var int
         */
	var $encounter_date_id = 0;
	/**	
         * @var int
         */
	var $encounter_value_id = 0;
	/**	
         * @var int
         */
	var $encounter_person_id = 0;
	/**	
         * @var int
         */
	var $payment_id = 0;
	/**	
         * @var int
         */
	var $patient_statistics_id = 0;
	/**	
         * @var int
         */
	var $coding;
	/**	
         * @var int
         */
	var $coding_parent_id = 0;
	/**	
         * @var int
         */
	var $note_id = 0;

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* 
 	* @return void 
        */
	function C_YDPEncounter ($template_mod = "general") {
		parent::Controller();
		$this->template_mod = $template_mod;

	}





	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string
 	* 
 	* @return mixed 
        */

	function actionAdd($encounter_id,$patient_id) {

		if(array_key_exists('process',$_POST)){//Then this form has been submitted...
				//So we create the 
			$this->assign('display_plan_edit',true);
	
			$plan =& ORDataObject::Factory('Plans');
			$plan->populate_array($_POST);
			if($plan->persist()){
				$planid = $plan->get('id');
				$edit_link = Celini::link('edit','Plan')."plan_id=$planid";
				//So we are editing now, lets us that code...
				return $this->actionEdit($encounter_id,$planid);		
			}else{//This is a duplicate plan for this patient 
			//	$this->messages->addMessage('ERROR: there is already a plan of this type for this patient');
			return $this->actionEdit($encounter_id,$plan->get('id'));
			}
		//	return $this->actionDefault_view($encounter_id);

		}else{
			$this->assign('PLAN_ACTION',Celini::link('add','Plan')."patient_id=$patient_id");
			$this->assign('patient_id',$patient_id);
			return $this->actionDefault_view($encounter_id);
		//	return $this->fetch(Celini::getTemplatePath("/plan/" . $this->template_mod . "_add.html"));
		}

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string
 	* 
 	* @return mixed 
        */
	function actionEdit($encounter_id,$plan_id) { 
		$this->assign('display_edit',true);
		$this->assign('display_status',false);
		$this->assign('display_add',false);
		return($this->_edit($encounter_id,$plan_id));
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string
 	* 
 	* @return mixed 
        */
	function actionStatus_edit($encounter_id,$plan_id) { 
		$this->assign('display_edit',false);
		$this->assign('display_status',true);
		$this->assign('display_add',false);
			if(array_key_exists('plan_status',$_POST)){
				$plan_id = $_POST['plan_id'];
				$plan =& ORDataObject::Factory('Plans',$plan_id);
				$plan->populate_array($_POST);
				$plan->persist();

			}

		return($this->_edit($encounter_id,$plan_id));
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string
 	* 
 	* @return mixed 
        */
	function _edit($encounter_id,$plan_id) {

		$process = false;
		if($plan_id != 0) {
			$process = true;
		}else{
			if(array_key_exists('plan_id',$_POST)){
				$plan_id = $_POST['plan_id'];
				$process = true;
			}
		}
		
		$plan =& ORDataObject::Factory('Plans',$plan_id);
		if($process){
			//var_export($_POST);
			$plan->populate_array($_POST);
			$plan->persist();
		}
		$this->assign('patient_id',$plan->get('patient_id'));
	         $session =& Celini::sessionInstance();
	         $session->set('last_patient_id',$plan->get('patient_id'));

		$this->assign('provider_id',$plan->get('provider_id'));
		$id = $plan->get('id');
		$this->assign('id',$id);
		$this->assign('plan_type',$plan->get('plan_type'));
		$this->assign('plan_name',$plan->get('plan_name'));
		$this->assign('plan_status',$plan->get('plan_status'));
		
		$protocol =& ORDataObject::Factory('YDPProtocol');
		$plan_content_form_id = $protocol->planform_from_type($plan->get('plan_type'));

	//	$plan_content_form_id = 600010;// could change per setup
				// Form/fillout?id=600010
		$this->assign('PLAN_LINK',Celini::link('fillout','Form','blank')."id=$plan_content_form_id&form_data_id=0&external_id=$id");
		$form_data_id = $plan->get_form_data_id();
		if($form_data_id > 0){

		$this->assign('PLAN_LINK',Celini::link('fillout','Form','blank')."id=$plan_content_form_id&form_data_id=$form_data_id&external_id=$id");
			
		}
		


		$plan_ds =& new Plan_FormDataList_DS($id);
		$formDataGrid =& new cGrid($plan_ds);
		$formDataGrid->name = "formDataGrid";
		$formDataGrid->registerTemplate('name','<a href="'.Celini::link('fillout','Form','blank').
				"id=$plan_content_form_id&".	
				'form_data_id={$form_data_id}'.
				"&external_id=$id".
				"\" target='plan_iframe' onclick=\"turn_on('plan_div')".
				'">{$name}</a>');
		$formDataGrid->pageSize = 10;
		$formDataGrid->setExternalId($plan->get('id'));
		$this->assign_by_ref('currentplanDataGrid',$formDataGrid);

		$this->assign('EDIT_ACTION',Celini::link('edit')."encounter_id=$encounter_id&plan_id=$plan_id");
		$this->assign('STATUS_ACTION',Celini::link('status')."encounter_id=$encounter_id&plan_id=$plan_id");
		$this->assign('ADD_ACTION',Celini::link('default')."encounter_id=$encounter_id&plan_id=$plan_id");


		return $this->actionDefault_view($encounter_id);


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
	function actionDefault_view($encounter_id) {
		
		if(array_key_exists('id',$_GET)){
			$encounter_id = $_GET['id'];
		}

	
		$encounter_id = $this->_enforcer->int($encounter_id); 
		$appointment_id = $this->_enforcer->int(0);
		$patient_id = $this->_enforcer->int(0);
		
		$valid_appointment_id = false;
		
		if (isset($this->encounter_id)) {
			$encounter_id = $this->encounter_id;
		}




		// check if an encounter_id already exists for this appointment
		if ($appointment_id > 0) {
			$valid_appointment_id = true;
			ORDataObject::factory_include('Encounter');
			$id = Encounter::encounterIdFromAppointmentId($appointment_id);
			if ($id > 0) {
				$encounter_id         = $id;
				$valid_appointment_id = false;
			} 
		}

		if ($encounter_id > 0) {
			$this->set('encounter_id',$encounter_id);
			$this->set('external_id', $this->get('encounter_id'));
		}
		//if ($encounter_id == 0 && $this->get('encounter_id') > 0) {
		//	$encounter_id = $this->get('encounter_id');
		//}	
		$this->set('encounter_id',$encounter_id);
		$encounter =& ORDataObject::factory('Encounter',$encounter_id,$this->get('patient_id'));
		if ($patient_id > 0) {
			$this->set('patient_id',$patient_id);
		}else{
			$this->set('patient_id',$encounter->get('patient_id'));
			$patient_id = $encounter->get('patient_id');
		}

	         $session =& Celini::sessionInstance();
	         $session->set('last_patient_id',$patient_id);


		$person =& ORDataObject::factory('Patient',$encounter->get('patient_id'));
		$this->assign_by_ref('selectedPatient',$person);
		$this->assign_by_ref('bob',$person);
		$building =& ORDataObject::factory('Building');

		$encounterDate =& ORDataObject::factory('EncounterDate',$this->encounter_date_id,$encounter_id);
		$encounterDateGrid = new cGrid($encounterDate->encounterDateList($encounter_id));
		$encounterDateGrid->name = "encounterDateGrid";
		$encounterDateGrid->registerTemplate('date','<a href="'.Celini::Managerlink('editEncounterDate',$encounter_id).'id={$encounter_date_id}&process=true">{$date}</a>');
		$this->assign('NEW_ENCOUNTER_DATE',Celini::managerLink('editEncounterDate',$encounter_id)."id=0&process=true");

		$encounterValue =& ORDataObject::factory('EncounterValue',$this->encounter_value_id,$encounter_id);
		$encounterValueGrid = new cGrid($encounterValue->encounterValueList($encounter_id));
		$encounterValueGrid->name = "encounterValueGrid";
		$encounterValueGrid->registerTemplate('value','<a href="'.Celini::Managerlink('editEncounterValue',$encounter_id).'id={$encounter_value_id}&process=true">{$value}</a>');
		$this->assign('NEW_ENCOUNTER_VALUE',Celini::managerLink('editEncounterValue',$encounter_id)."id=0&process=true");

		$encounterPerson =& ORDataObject::factory('EncounterPerson',$this->encounter_person_id,$encounter_id);
		$encounterPersonGrid = new cGrid($encounterPerson->encounterPersonList($encounter_id));
		$encounterPersonGrid->name = "encounterPersonGrid";
		$encounterPersonGrid->registerTemplate('person','<a href="'.Celini::Managerlink('editEncounterPerson',$encounter_id).'id={$encounter_person_id}&process=true">{$person}</a>');
		$this->assign('NEW_ENCOUNTER_PERSON',Celini::managerLink('editEncounterPerson',$encounter_id)."id=0&process=true");
		
		$payment =& ORDataObject::factory('Payment',$this->payment_id);
		if ($payment->_populated == false) {
			$payment->set('title','Co-Pay');
		}
		$payment->set("encounter_id",$encounter_id);
		$paymentGrid = new cGrid($payment->paymentsFromEncounterId($encounter_id));
		$paymentGrid->name = "paymentGrid";
		$paymentGrid->registerTemplate('amount','<a href="'.Celini::Managerlink('editPayment',$encounter_id).'id={$payment_id}&process=true">{$amount}</a>');
		$paymentGrid->registerFilter('payment_date', array('DateObject', 'ISOToUSA'));
		$this->assign('NEW_ENCOUNTER_PAYMENT',Celini::managerLink('editPayment',$encounter_id)."id=0&process=true");

		
		$appointments = $encounter->appointmentList();
		$appointmentArray = array("" => " ");
		foreach($appointments as $appointment) {
			$appointmentArray[$appointment['occurence_id']] = date("m/d/Y H:i",strtotime($appointment['appointment_start'])) . " " . $appointment['building_name'] . "->" . $appointment['room_name'] . " " . $appointment['provider_name'];
		}
		
		
		// If this is a saved encounter, generate the following:
		if ($this->get('encounter_id') > 0) {
			// Load data that has been stored
			$formData =& ORDataObject::factory("FormData");
			$formDataGrid =& new cGrid($formData->dataListByExternalId($encounter_id));
			$formDataGrid->name  = "formDataGrid";
			$formDataGrid->registerTemplate('name','<a href="'.Celini::link('data','Form').'id={$form_data_id}">{$name}</a>');
		// commenting this line out fixed 3602 
		//	$formDataGrid->pageSize = 10;
		// 	
			// Generate a menu of forms that are connected to Encounters
			$menu = Menu::getInstance();
			$connectedForms = $menu->getMenuData('patient',
				$menu->getMenuIdFromTitle('patient','Encounter Forms'));
			
			$formList = array();
			if (isset($connectedForms['forms'])) {
				foreach($connectedForms['forms'] as $form) {
					$formList[$form['form_id']] = $form['title'];
				}
			}
		}
		
		//if an appointment id is supplied the request is coming from the 
		//calendar and so prepopulate the defaults
		if ($appointment_id > 0 && $valid_appointment_id) {
			$encounter->set("occurence_id",$appointment_id);
			$encounter->set("patient_id",$this->get("patient_id"));
			if (isset($appointments[$appointment_id])) {
				$encounter->set("building_id",$appointments[$appointment_id]['building_id']);
			}
			if (isset($appointments[$appointment_id])) {
				$encounter->set("treating_person_id",$appointments[$appointment_id]['provider_id']);
			}
		}

		$patient_id = $this->get('patient_id');
	         $session =& Celini::sessionInstance();
	         $session->set('last_patient_id',$patient_id);
		$this->assign('patient_id',$patient_id);
		$insuredRelationship =& ORDataObject::factory('InsuredRelationship');
		$address =& ORDataObject::factory('PersonAddress',$this->address_id,$patient_id);
		$provider =& ORDataObject::factory('Provider',$encounter->get('treating_person_id'));

		$this->assign_by_ref('provider',$provider);
		$this->assign_by_ref('address',$address);
		$this->assign_by_ref('insuredRelationship',$insuredRelationship);
		$this->assign_by_ref('encounter',$encounter);
		$this->assign_by_ref('person',$person);
		$this->assign_by_ref('building',$building);
		$this->assign_by_ref('encounterDate',$encounterDate);
		$this->assign_by_ref('encounterDateGrid',$encounterDateGrid);
		$this->assign_by_ref('encounterPerson',$encounterPerson);
		$this->assign_by_ref('encounterPersonGrid',$encounterPersonGrid);
		$this->assign_by_ref('encounterValue',$encounterValue);
		$this->assign_by_ref('encounterValueGrid',$encounterValueGrid);
		$this->assign_by_ref('payment',$payment);
		$this->assign_by_ref('paymentGrid',$paymentGrid);
		$this->assign_by_ref('appointmentList',$appointments);
		$this->assign_by_ref('appointmentArray',$appointmentArray);
		$this->assign_by_ref('selectedPatient',$person);
			$this->assign('PLAN_ACTION',Celini::link('add','YDPEncounter')."encounter_id=$encounter_id&patient_id=$patient_id");
			$this->assign('patient_id',$patient_id);

	//	echo "patient id = $patient_id";

		$this->assign('DASHBOARD_LINK',Celini::link('dashboard','Patient')."id=".$this->get('patient_id'));
	//	$this->assign('FORM_ACTION',Celini::link('default',true,true,$encounter_id));
		$this->assign('ENCOUNTER_REPORT',Celini::link('report').
		'report_id=600232&report_template_id=600234&patient_id='.$this->get("patient_id").
		"&encounter_id=$encounter_id");
		$this->assign('FORM_FILLOUT_ACTION',Celini::link('fillout','Form'));
		$this->assign('SURVEY_ACTION',Celini::link('list','Survey').
					"patient_id=".$this->get('patient_id')."&".
					"encounter_id=$encounter_id");
		if ($encounter_id > 0 /*&& $encounter->get('status') !== "closed"*/) {
			//$this->coding->assign('FORM_ACTION',Celini::link('encounter',true,true,$encounter_id));
			//$this->coding->assign("encounter", $encounter);
		//	$codingHtml = $this->coding->actionUpdate_edit($encounter_id,$this->coding_parent_id);
		//	$this->assign('codingHtml',$codingHtml);
			$this->assign_by_ref('formDataGrid',$formDataGrid);
			$this->assign_by_ref('formList',$formList);
		}

		if ($encounter->get('status') === "closed") {
			ORDataObject::factory_include('ClearhealthClaim');
			$claim =& ClearhealthClaim::fromEncounterId($encounter_id);
		//	$this->assign('FREEB_ACTION',$GLOBALS['C_ALL']['freeb2_dir'] . substr(Celini::link('list_revisions','Claim','freeb2',$claim->get('identifier'),false,false),1));
		//	$this->assign('PAYMENT_ACTION',Celini::link('payment','Eob',true,$claim->get('id')));

			$this->assign('encounter_has_claim',false);
			if ($claim->_populated) {
				$this->assign('encounter_has_claim',true);
			}

			/* moved to menu
			// todo: get this without hard coding in the report and template id
			$exit_base_link = str_replace("main","PDF",Celini::link('report',true,true));
			$this->assign('EXIT_REPORT',$exit_base_link."report_id=17075&template_id=17077&encounter_id=".$encounter->get('id'));
			*/

			$this->assign('REOPEN_ACTION',Celini::link('reopen_encounter', true, true, $encounter->get('id')) . 'process=true');
		}
		else {
			ORdataObject::factory_include('ClearhealthClaim');
			$claim =& ClearhealthClaim::fromEncounterId($encounter_id);
			if ($claim->get('identifier') > 0) {
				$this->assign('claimSubmitValue', 'rebill');
			}
			else {
				$this->assign('claimSubmitValue', 'close');
			}
			//printf('<pre>%s</pre>', var_export($claim , true));
			//exit;
		}
			/* not currently in use
			$intake_base_link = str_replace("main","util",Celini::link('report',true,true));
			$this->assign('INTAKE_REPORT',$intake_base_link."report_id=17857&template_id=17859&encounter_id=".$encounter->get('id'));
			*/
		//Lets make a drug button!!
		$user = $this->_me->get_user();
		$building =& ORDataObject::factory('Building',$encounter->get('building_id'));
		$NewCropdata = new NewCropdata();
		$drug_array = $NewCropdata->get_NewCrop_data(&$person,&$user,&$building,1);
		$this->assign_by_ref('drug_data',$drug_array);

		$drug_button_template = $NewCropdata->get_button(&$person,&$user,&$building);
			$planGrid =& new cGrid($person->loadDatasource('FullPlanList'));
			$planGrid->name = "planGrid";
			$planGrid->registerTemplate('plan_type','<a href="'.Celini::link('edit','YDPEncounter')."encounter_id=".$encounter->get('id').'&plan_id={$plan_id}">{$plan_type}</a>');
			$planGrid->registerTemplate('plan_status','<a href="'.Celini::link('status','YDPEncounter')."encounter_id=".$encounter->get('id').'&plan_id={$plan_id}">{$plan_status}</a>');
		//	$planGrid->registerTemplate('meds','{$meds}<br>'."$drug_button_template");
			$planGrid->registerTemplate('meds','Not connected to NewCrop<br>');
			$planGrid->pageSize = 5;
			$planGrid->setExternalId($person->get('id'));

			$this->assign_by_ref('planGrid',$planGrid);

			$vitals_form = '1001887';
			$this->assign('VITALS_FORM',Celini::link('fillout','Form','blank')."id=$vitals_form&form_data_id=0&external_id=$encounter_id");
			$this->assign('PLAN_FORM',Celini::link('add','Plan','blank')."patient_id=".$this->get('patient_id'));
//http://localhost:81/kim_dunn/index.php/blank/Form/fillout?id=1710

		$this->assign('SELF_ACTION',Celini::link('default')."encounter_id=$encounter_id");
		$this->assign('PRINT_LINK',Celini::link('default','YDPEncounter','blank')."encounter_id=$encounter_id");
		$this->assign('SURVEY_LINK',Celini::link('list','Survey')."patient_id=".$this->get('patient_id')."&encounter_id=$encounter_id");
		$this->assign('MESSAGE_LINK',Celini::link('new','Messages')."patient_id=".$this->get('patient_id'));
		$vitals_done = false;
		$vitals_data_id = $this->_are_vitals_done($encounter_id,$vitals_form);
		if($vitals_data_id > 0){

			$vitals_done = true;
			$this->assign('EDIT_VITALS_LINK',Celini::link('fillout','Form','main')."id=$vitals_form&form_data_id=$vitals_data_id&external_id=$encounter_id");
			$this->assign('MAIN_VITALS_LINK',Celini::link('fillout','Form','main')."id=$vitals_form&form_data_id=0&external_id=$encounter_id");
	
		}
		$this->assign('vitals_done',$this->_are_vitals_done($encounter_id,$vitals_form));


		$this->_updateDrugs($this->get('patient_id'));
		


		return $this->fetch(Celini::getTemplatePath("/ydpencounter/" . $this->template_mod . "_ydpencounter.html"));	
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string
 	* 
 	* @return string
        */
	function _are_vitals_done($encounter_id,$vitals_form){

		$sql = "SELECT
			form_data.form_data_id, 
			storage_string.value_key as string_key,
			storage_string.value as string_value
			FROM form_data 
			LEFT JOIN storage_string ON storage_string.foreign_key = form_data.form_data_id
			LEFT JOIN storage_text ON storage_text.foreign_key = form_data.form_data_id
			LEFT JOIN storage_int ON storage_int.foreign_key = form_data.form_data_id
			WHERE form_data.form_id = $vitals_form and form_data.external_id = $encounter_id ORDER BY form_data.last_edit ASC";
		
	//	echo $sql;
       		 $db = $GLOBALS['config']['adodb']['db'];
		$results = $db->Execute($sql) or die ("Database Error: " . $this->_db->ErrorMsg());
		
		if(!($results && !$results->EOF)){
			return(false);
		}
		while ($results && !$results->EOF) {
			switch($results->fields['string_key']){
			case 'temp': $this->assign('temp',$results->fields['string_value']); break;
			case 'resp': $this->assign('resp',$results->fields['string_value']); break;
			case 'systolicbp': $this->assign('sbp',$results->fields['string_value']); break;
			case 'diastolicbp': $this->assign('dbp',$results->fields['string_value']); break;
			case 'pulse': $this->assign('pulse',$results->fields['string_value']); break;
			case 'weight': $this->assign('weight',$results->fields['string_value']); break;
			case 'height': $this->assign('height',$results->fields['string_value']); break;
			}
		
			$data_id = $results->fields['form_data_id'];
			$results->MoveNext();
		}
		return($data_id);
	}



	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* 
 	* @return void
        */
	function _updateDrugs($patient_id){

		return;

		if(array_key_exists('ERXurl',$GLOBALS['config'])){	
			$wsdl_url = $GLOBALS['config']['ERXurl'];
  	//	'http://preproduction.newcropaccounts.com/v7/webservices/update1.asmx?WSDL';
  	//	'https://secure.newcropaccounts.com/v7/webservices/update1.asmx?WSDL';
			$WSDL     = new SOAP_WSDL($wsdl_url); 
			$client   = $WSDL->getProxy(); 
	
		//echo($WSDL->generateProxyCode());

		//$user = $this->_me->get_user();
		$params   = array(
		//	'credentials' => array(
		//		'PartnerName' => 'demo',
		//		'Name' => 'demo',
		//		'Password' => 'demo'),
			'accountRequest' => array(
			'AccountId' => $GLOBALS['config']['app_name'],
			'SiteId' => $GLOBALS['config']['app_name']),
		'patientRequest' => array(
				'PatientId' => "$patient_id",
				),
		'prescriptionHistoryRequest' => array(
			'StartHistory' => '2006-01-01T00:00:00.000',
			'EndHistory' => '2020-01-01T00:00:00.000',
			'PrescriptionStatus' => 'C',
			'PrescriptionSubStatus' => 'S',
			'PrescriptionArchiveStatus' => 'N'),
	//	'patientInformationRequester' => array(
	//		'UserType' => 'doctor',
	//		'UserId' => 'demo'
	//			),
		'patientIdType' => ''
		);
	//TODO base this on calls to $user...
	$is_provider = true;
	if($is_provider){
		$params['patientInformationRequester']['UserType'] = 'doctor';
	}
	$params['patientInformationRequester']['UserId'] = 'demo';
	$params['credentials'] = $GLOBALS['config']['ERXSOAPCredentials'];

	/*,

	'patientInformationRequester' => array(
		'UserType' => 'doctor',
		'UserId' => 'demo'
			),
	'patientId' => 	'patientIdType' => '',

	'patientInformationRequester' => array(
		'UserType' => '',
		'UserID' => ''
		)
	*/


	//var_export($params);

	if(method_exists($client,'GetPatientFullMedicationHistory4')){
		$results    = $client->GetPatientFullMedicationHistory4($params['credentials'],
							$params['accountRequest'],
							$params['patientRequest'],
							$params['prescriptionHistoryRequest'],
							$params['patientInformationRequester'],
							$params['patientIdType']
							);
	}else{
		//FRED log something?
		return;
	}
	$drugs_by_plan = array();
	//echo $patient_id;
	if(isset($results->patientDrugDetail)){
		foreach($results->patientDrugDetail as $drug_row){
			$plan_string = $drug_row->EpisodeIdentifier;
			$plan_array = split('#',$plan_string);
			$plan_id = $plan_array[1];
		//	echo $plan_id." ";
			$drugs_by_plan[$plan_id][]=$drug_row;
		}
	}

	foreach($drugs_by_plan as $plan_id => $drug_rows){

		$html_for_plan = "<div style='font-size: small;'>";
		$plan =& ORDataObject::Factory('Plans',$plan_id);		
		foreach($drug_rows as $drug_row){
		//	var_export($drug_row);
			$html_for_plan .= $drug_row->DrugName." ".$drug_row->Strength." ".
				$drug_row->StrengthUOM." ".$drug_row->DosageForm." ".
				$drug_row->DosageNumberDescription." ".$drug_row->DosageFrequencyDescription
				." #".$drug_row->Dispense." ".$drug_row->PhysicianName."<br> ";
		}
		$html_for_plan .= "</div>";
		$plan->set('meds',$html_for_plan);
		$plan->persist();
	}
	}

}



}

?>
