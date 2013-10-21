<?php

class NewCropData{


	var $XMLObject;
	var $user_id;
	var $userObject;
	var $provider_id;
	var $providerObject;
	var $building_id;
	var $buildingObject;
	var $episode_id;
	var $patient_id;
	var $patientObject;
	var $valid;
	var $error_message;


	var $is_provider;
	var $is_staff;
	var $is_midlevel;
	var $is_doctor;




  	//drug variables must be set manually
	//after object creation
	var $PharmacistMessage;
	var $PharmacyIdentifier;
	var $DrugIdentifier;
	var $DrugIdentifierType;
	var $DespenseNumber;
	var $Dosage;
	var $RefillCount;
	var $Substitution;
	var $RenewalsResponseCode;
	var $ResponseDenyCode;


//object can be created with either ids or objects for convience 
function __construct($user,$building,$patient = 0,$episode = 0){


	$this->valid = true;

	if(is_object($user)){
		$this->userObject = $user;
		$this->user_id = $user->get('user_id');
	}else{
		if(is_int($user) && ($user != 0)){
			$this->user_id = $user;
			$this->userObject =& ORDataObject::Factory('User',$user_id); 
		}else{
			echo "Error: NewCropData.class.php: user argument must either be a user or a user object";
			exit();
		}
	}


	if(is_object($building)){
		$this->buildingObject = $building;
		$this->building_id = $building->get('building_id');
	}else{
		if(is_int($building) && $building != 0){
			$this->building_id = $user;
			$this->buildingObject =& ORDataObject::Factory('Building',$building_id); 
		}else{
			echo "Error: NewCropData.class.php: building argument must either be a building or a building object";
			exit();
		}
	}


	if(is_object($patient)){
		$this->patientObject = $patient;
		$this->patient_id = $patient->get('patient_id');
	}else{
		if(is_int($patient) && $patient != 0){
			//this does not seem to work??
			$this->patient_id = $patient;
			$this->patientObject =& ORDataObject::Factory('Patient',$patient); 
		}else{
			$this->patient_id = 0;
			// Patient is not a required datapoint
		
		}
	}


	if(isset($episode)){
		$this->episode_id = $episode;

	}


	$user_person_id = $this->userObject->get('person_id');
	$user_person  =& ORDataObject::Factory('Person',$user_person_id);


	$type = $user_person->get('type');
	$is_provider = false;
	$is_doctor = false;
	$is_staff = false;
	$is_midlevel = false;
	if($type == 2){
		$is_provider = true;
		$is_doctor = true;
	}

	if($type == 3){// need to implement staff access.
		$is_provider = true;
		$is_midlevel = true;
	}

	if($type == 4){// need to implement staff access.
		$is_provider = true;
		$is_staff = true;
	}

	if($type == 1){
	//this is a patient, madness!
		return(false);
	}

	$this->is_doctor = $is_doctor;
	$this->is_midlevel = $is_midlevel;
	$this->is_staff = $is_staff;
	$this->is_provider = $is_provider;

	$practice =& ORDataObject::factory('Practice',$building->get('practice_id'));
	
	$practice_array = $practice->toArray();
//echo "<br><br>";	var_export($practice_array);
	if(strlen($practice_array['address']['line1']) < 1){
                //practice address setting bug workaround.
                //var_export($building->toArray());
                $building_array = $building->toArray();
                $practice_array['address'] = $building_array['address'];
	}

	$this->providerObject =& ORDataObject::Factory('Provider',$user_person_id);
	$instance_name = $GLOBALS['config']['app_name'];

	/// Starting XML
	//from the example
	$starting_xml = "
<?xml version='1.0'?> <NCScript xmlns:xsd='http://www.w3.org/2001/XMLSchema'
          xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
          xmlns='http://secure.newcropaccounts.com/interfaceV7'></NCScript>";
	// does not throw an error.
	$starting_xml = "<?xml version='1.0' encoding='UTF-8'?><NCScript xmlns='http://secure.newcropaccounts.com/interfaceV7' xmlns:NCStandard='http://secure.newcropaccounts.com/interfaceV7:NCStandard' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'></NCScript>";

// This is to match the renewals.
/*	$starting_xml = "<?xml version='1.0'?><NCScript xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns='http://secure.newcropaccounts.com/interfaceV7'></NCScript>";
*/


	$this->XMLObject = new SimpleXMLElement($starting_xml);

	$cred_array = $GLOBALS['config']['ERXCredentials'];
	$Credentials = $this->XMLObject->addChild('Credentials');
	$Credentials->addChild('partnerName',$cred_array['partnerName']);
	$Credentials->addChild('name',$cred_array['name']);
	$Credentials->addChild('password',$cred_array['password']);
	$Credentials->addChild('productName',$cred_array['productName']);
	$Credentials->addChild('productVersion',$cred_array['productVersion']);


	if($is_provider){
		if($is_doctor){
			$UserRole = $this->XMLObject->addChild('UserRole');
			$UserRole->addChild('user','LicensedPrescriber');
			$UserRole->addChild('role','doctor');
		}
		if($is_staff){
			$UserRole = $this->XMLObject->addChild('UserRole');
			$UserRole->addChild('user','Staff');
			$UserRole->addChild('role','nurse');
		}
		if($is_midlevel){
			$UserRole = $this->XMLObject->addChild('UserRole');
			$UserRole->addChild('user','MidlevelPrescriber');
			$UserRole->addChild('role','midlevelPrescriber');
		}
	}

}

function get_button($type = 'prescribe'){//this is custom for YDP
					//Normal MirrorMed should use the smarty tag.
	
	// This is how we make this a template...
	//$data['Patient']['EpisodeIdentifier'] = '{$plan_type} #{$plan_id}';
	$data['Patient']['EpisodeIdentifier'] = $this->episode_id;
	$xml =	$this->get_NewCrop_data($type);
	$url = "secure.newcropaccounts.com";
	$action_url = $GLOBALS['config']['ERXurl'];
	$GLOBALS['loader']->requireOnce('lib/PEAR/XML/Serializer.php');




$_html_result = "	<form name='NewCropForm' method='post' action='$action_url/InterfaceV7/RxEntry.aspx'>
			<input type='hidden' id='RxInput' name='RxInput' value='$xml'>
			<input type='submit' name='$type'>
		</form>

";

        return $_html_result;

}


function get_auto_button($type = 'prescribe',$message = ''){//this is custom for YDP
					//Normal MirrorMed should use the smarty tag.
	
	// This is how we make this a template...
	//$data['Patient']['EpisodeIdentifier'] = '{$plan_type} #{$plan_id}';
	$data['Patient']['EpisodeIdentifier'] = $this->episode_id;
	$xml =	$this->get_NewCrop_data($type);
	$url = "secure.newcropaccounts.com";
	$action_url = $GLOBALS['config']['ERXurl'];
	$GLOBALS['loader']->requireOnce('lib/PEAR/XML/Serializer.php');



$_html_result = "	<form name='NewCropForm' method='post' target='NewCropFrame' action='$action_url/InterfaceV7/RxEntry.aspx'>
			<input type='hidden' id='RxInput' name='RxInput' value='$xml'>

		</form>
		<h3>$message</h3>
                <iframe NAME='NewCropFrame' width='100%' height='1800' frameborder='0'></iframe>


 	<SCRIPT language='JavaScript'>
	function submitform()
	{
  		document.NewCropForm.submit();
	}
	submitform();	
	</SCRIPT>

";

        return $_html_result;

}


function addDestination($destination){


	$Destination = $this->XMLObject->addChild('Destination');
	$Destination->addChild('requestedPage',$destination);

}


function addAccount(){

	$cred_array = $GLOBALS['config']['ERXCredentials'];
	$practice =& ORDataObject::factory('Practice',$this->buildingObject->get('practice_id'));
	
	$practice_array = $practice->toArray();


	if(    !(  //NOT!!
		isset($practice_array['address']['line1']) && strlen($practice_array['address']['line1']) > 1 &&
		isset($practice_array['address']['city']) && strlen($practice_array['address']['city']) > 1 &&
		isset($practice_array['address']['state']) && strlen($practice_array['address']['state']) > 1 &&
		isset($practice_array['address']['postal_code']) && strlen($practice_array['address']['postal_code']) > 1 && 
		isset($practice_array['phone_number']) && strlen($practice_array['phone_number']) > 1 &&
		isset($practice_array['fax']) && strlen($practice_array['fax']) > 1 
		)
	){
		$practice_array_string = print_r($practice_array,true);
		$this->valid = false;
		$this->error_message  .= "The Practice Address Must be set!!<br><br> $practice_array_string\n <br>";
		return(false);
	}	


	$Account = $this->XMLObject->addChild('Account');
		$Account->addAttribute('ID', $cred_array['accountId']);
		$Account->addChild('accountName',$practice_array['name']);
		$Account->addChild('siteID', $cred_array['siteId']);
		$AccountAddress = $Account->addChild('AccountAddress');
			$AccountAddress->addChild('address1',$practice_array['address']['line1']);
			$AccountAddress->addChild('address2',$practice_array['address']['line2']);
			$AccountAddress->addChild('city',$practice_array['address']['city']);
			$AccountAddress->addChild('state',$practice_array['address']['state']);
			$AccountAddress->addChild('zip',$practice_array['address']['postal_code']);
			$AccountAddress->addChild('country', 'US');
		$Account->addChild('accountPrimaryPhoneNumber',$practice_array['phone_number']);
		$Account->addChild('accountPrimaryFaxNumber',$practice_array['fax']);

}


function addLocation(){

	$cred_array = $GLOBALS['config']['ERXCredentials'];
	$practice_id = $this->buildingObject->get('practice_id');
	$building_id = $this->buildingObject->get('id');
	$building_name = $this->buildingObject->get('name');
	$practice =& ORDataObject::factory('Practice',$practice_id);
	
	$practice_array = $practice->toArray();

	//echo "<br><br><br><br> practice_id $practice_id building_id $building_id building_name $building_name";

	$instance_name = $GLOBALS['config']['app_name'];
	$Location = $this->XMLObject->addChild('Location');
	$Location->addAttribute('ID', $building_id);
	$Location->addChild('locationName',$building_name);
	$LocationAddress = $Location->addChild('LocationAddress');
			$LocationAddress->addChild('address1',$practice_array['address']['line1']);
			$LocationAddress->addChild('address2',$practice_array['address']['line2']);
			$LocationAddress->addChild('city',$practice_array['address']['city']);
			$LocationAddress->addChild('state',$practice_array['address']['state']);
			$LocationAddress->addChild('zip',$practice_array['address']['postal_code']);
			$LocationAddress->addChild('country', 'US');
		$Location->addChild('primaryPhoneNumber',$practice_array['phone_number']);
		$Location->addChild('primaryFaxNumber',$practice_array['fax']);
		$Location->addChild('pharmacyContactNumber',$practice_array['phone_number']);




}

function addLicensedPrescriber(){
	//This is a tad simplistic. 

	//Future versions of this should become aware of the concept of supervising doctor
	//But how to develop an interface for that?
	//It would have to be part of the module. 
	//It could either be choose once, choose for today or choose for every script.
	//This simplistic solution puts that over on NewCrop for the time being.
	
	if($this->is_doctor){
		$this->addDoctor();
	}

	if($this->is_midlevel){
		$this->addMidlevel();
	}

	if($this->is_staff){
		$this->addStaff();
	}



}

function addDoctor(){

	$LicensedPrescriber = $this->XMLObject->addChild('LicensedPrescriber');
	$LicensedPrescriber->addAttribute('ID', $this->user_id);

	$user_person_id = $this->userObject->get('person_id');
	$user_person  =& ORDataObject::Factory('Person',$user_person_id);

		$LicensedPrescriberName = $LicensedPrescriber->addChild('LicensedPrescriberName');
			$LicensedPrescriberName->addChild('last',$user_person->get('last_name'));
			$LicensedPrescriberName->addChild('first',$user_person->get('first_name'));
			$LicensedPrescriberName->addChild('middle',$user_person->get('middle_name'));
			$LicensedPrescriberName->addChild('prefix',$user_person->get('salutation'));
		$LicensedPrescriber->addChild('dea',$this->providerObject->get('dea_number'));
		$LicensedPrescriber->addChild('prescriberStatus','Active');
		$LicensedPrescriber->addChild('upin',$user_person->get('identifier'));
		$LicensedPrescriber->addChild('licenseState','TX');	
		$LicensedPrescriber->addChild('licenseNumber',$this->providerObject->get('state_license_number'));
		$LicensedPrescriber->addChild('npi',$this->providerObject->getIdentifierByType('NPI'));

}



function addStaff(){

	$Staff = $this->XMLObject->addChild('Staff');
	$Staff->addAttribute('ID', $this->user_id);

	$user_person_id = $this->userObject->get('person_id');
	$user_person  =& ORDataObject::Factory('Person',$user_person_id);

		$StaffName = $Staff->addChild('StaffName');
			$StaffName->addChild('last',$user_person->get('last_name'));
			$StaffName->addChild('first',$user_person->get('first_name'));
			$StaffName->addChild('middle',$user_person->get('middle_name'));
			$StaffName->addChild('prefix',$user_person->get('salutation'));
		$Staff->addChild('license',$user_person->get('identifier'));

}


function addMidlevel(){

	$MidlevelPrescriber = $this->XMLObject->addChild('MidlevelPrescriber');
	$MidlevelPrescriber->addAttribute('ID', $this->user_id);

	$user_person_id = $this->userObject->get('person_id');
	$user_person  =& ORDataObject::Factory('Person',$user_person_id);

		$LicensedPrescriberName = $MidlevelPrescriber->addChild('LicensedPrescriberName');
			$LicensedPrescriberName->addChild('last',$user_person->get('last_name'));
			$LicensedPrescriberName->addChild('first',$user_person->get('first_name'));
			$LicensedPrescriberName->addChild('middle',$user_person->get('middle_name'));
			$LicensedPrescriberName->addChild('prefix',$user_person->get('salutation'));
		$MidlevelPrescriber->addChild('dea',$this->providerObject->get('dea_number'));
		$MidlevelPrescriber->addChild('prescriberStatus','Active');
		$MidlevelPrescriber->addChild('upin',$user_person->get('identifier'));
		$MidlevelPrescriber->addChild('licenseState','TX');	
		$MidlevelPrescriber->addChild('licenseNumber',$this->providerObject->get('state_license_number'));
		$MidlevelPrescriber->addChild('npi',$this->providerObject->getIdentifierByType('NPI'));

}




function addNewPrescription(){

		$NewPrescription = $this->XMLObject->addChild('NewPrescription');
		$NewPrescription->addChild('pharmacyIdentifier',$this->PharmacyIdentifier);
		$NewPrescription->addChild('drugIdentifier',$this->DrugIdentifier);
		$NewPrescription->addChild('drugIdentifierType',$this->DrugIdentifierType);
		$NewPrescription->addChild('dispenseNumber',$this->DispenseNumber);
		$NewPrescription->addChild('dosage',$this->Dosage);
		$NewPrescription->addChild('refillCount',$this->RefillCount);
		$NewPrescription->addChild('substitution',$this->Substitution);
		$NewPrescription->addChild('pharmacistMessage',$this->PharmacistMessage);

}




function addPatient(){

	$genders = $this->patientObject->getGenderList();
	if(array_key_exists($this->patientObject->get('gender'),$genders)){
		$my_full_gender = $genders[$this->patientObject->get('gender')];
		$my_gender = $my_full_gender{0};
	}else{
		$my_gender = "U";
	}


	$patientAddress =& $this->patientObject->address();
	$patientNumber =& $this->patientObject->numberByType('Home');
	
	$practice =& ORDataObject::factory('Practice',$this->buildingObject->get('practice_id'));
	

	$practice_array = $practice->toArray();
	if(strlen($practice_array['address']['line1']) < 1){
                //practice address setting bug workaround.
                //var_export($building->toArray());
                $building_array = $this->buildingObject->toArray();
                $practice_array['address'] = $building_array['address'];
	}

	$patientZip = substr($patientAddress->get('zip'), 0 , 5);
	$user_person_id = $this->userObject->get('person_id');
	$provider  =& ORDataObject::Factory('Provider',$user_person_id);
	$instance_name = $GLOBALS['config']['app_name'];

	$date_object =& new DateObject();
	$date_object = $date_object->create($this->patientObject->get('date_of_birth'));
	$birthday = $date_object->toString('%Y%m%d');


	$Patient = $this->XMLObject->addChild('Patient');
	$Patient->addAttribute('ID', $this->patientObject->get('id'));
		$PatientName = $Patient->addChild('PatientName');
			$PatientName->addChild('last', $this->patientObject->get('last_name'));
			$PatientName->addChild('first', $this->patientObject->get('first_name'));
			$PatientName->addChild('middle', $this->patientObject->get('middle_name'));
	$Patient->addChild('medicalRecordNumber', $this->patientObject->get('id'));
	$Patient->addChild('socialSecurityNumber', $this->patientObject->get('identifier'));
	$Patient->addChild('memo', '');
		$PatientAddress = $Patient->addChild('PatientAddress');
			$PatientAddress->addChild('address1',$patientAddress->get('line1'));
			$PatientAddress->addChild('address2',$patientAddress->get('line2'));
			$PatientAddress->addChild('city',$patientAddress->get('city'));
			$PatientAddress->addChild('state',$patientAddress->get_stateInitials());
			$PatientAddress->addChild('zip',$patientZip);
			$PatientAddress->addChild('country', 'US');
		$PatientContact = $Patient->addChild('PatientContact');
			$PatientContact->addChild('homeTelephone',$patientNumber->get('number'));
		$PatientCharacteristics = $Patient->addChild('PatientCharacteristics');
			$PatientCharacteristics->addChild('dob',$birthday);
			$PatientCharacteristics->addChild('gender',$my_gender);
	
		if(isset($this->episode_id)){
			$EpisodeIdentifier= $Patient->addChild('EpisodeIdentifier', $this->episode_id);
		}

}

function get_NewCrop_data($type){

	switch($type){

		case 'prescribe':
			return($this->prescribe_xml());
			break;

		case 'manage':
			return($this->manage_xml());
			break;

		case 'renewal':
			return($this->renewal_xml());
			break;




	}

}	

function addPrescriptionRenewalResponse(){

		if(!isset($this->RenewalRequestIdentifier)){
				$this->valid = false;
				$this->error_message  .= "You must have the Renewal Request Identifier set<br>";
	
		}

		if(!isset($this->RenewalsResponseCode)){
				$this->valid = false;
				$this->error_message  .= "You must have the Renewal Response code set<br>";
	
		}

		if(!isset($this->RefillCount)){
				$this->valid = false;
				$this->error_message  .= "You must have the Refill Count set<br>";
	
		}

		if(!isset($this->DrugSchedule)){
				$this->valid = false;
				$this->error_message  .= "You must have the Drug Schedule set<br>";
	
		}


		$PrescriptionRenewalResponse = $this->XMLObject->addChild('PrescriptionRenewalResponse');
		$PrescriptionRenewalResponse->addChild('renewalRequestIdentifier',$this->RenewalRequestIdentifier);
		$PrescriptionRenewalResponse->addChild('responseCode',$this->RenewalsResponseCode);
		$PrescriptionRenewalResponse->addChild('refillCount',$this->RefillCount);
		$PrescriptionRenewalResponse->addChild('drugSchedule',$this->DrugSchedule);
		if(isset($this->ResponseDenyCode)){
			$PrescriptionRenewalResponse->addChild('responseDenyCode',$this->ResponseDenyCode);
		}

		if(isset($this->PharmacistMessage)){
			$PrescriptionRenewalResponse->addChild('pharmacistMessage',$this->PharmacistMessage);
		}

}



function renewal_xml(){


	$this->addDestination('renewal');
	$this->addAccount();
	$this->addLocation();
	$this->addLicensedPrescriber();
	$this->addPatient();
	$this->addPrescriptionRenewalResponse();


	//test invalidity
	//$this->valid = false;
	//$this->error_message = 'This is a test error message, just to see....';


		if($this->isValid()){
			return($this->asXML());
		}else{
			return($this->error_message);
		}

}




// This function builds a data structure that will result in proper eRx xml when the array2xml is run on it
function manage_xml(){


	$this->addDestination('manager');
	$this->addAccount();
	$this->addLocation();
	$this->addLicensedPrescriber();
//	$this->addPatient();


	//test invalidity
	//$this->valid = false;
	//$this->error_message = 'This is a test error message, just to see....';


		if($this->isValid()){
			return($this->asXML());
		}else{
			return($this->error_message);
		}

}


// This function builds a data structure that will result in proper eRx xml when the array2xml is run on it
function prescribe_xml(){


	$this->addDestination('compose');
	$this->addAccount();
	$this->addLocation();
	$this->addLicensedPrescriber();
	$this->addPatient();


	//test invalidity
	//$this->valid = false;
	//$this->error_message = 'This is a test error message, just to see....';


		if($this->isValid()){
			return($this->asXML());
		}else{
			return($this->error_message);
		}

}

function libxml_record_error($error)
{
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $this->error_message .= "<br><br><b>XML Warning $error->code</b>: ";
            break;
        case LIBXML_ERR_ERROR:
            $this->error_message .= "<br><br><b>XML Error $error->code</b>: ";
            break;
        case LIBXML_ERR_FATAL:
            $this->error_message .= "<br><br><b>XML Fatal Error $error->code</b>: ";
            break;
    }
    $this->error_message .= trim($error->message);
    if ($error->file) {
        $this->error_message .=    " in <b>$error->file</b>";
    }
    $this->error_message .= " on line <b>$error->line</b><br><br>\n";

}

function isValid(){
	
	if(!$this->valid){ //why bother with XML validation when there is some other problem??
		return($this->valid);
	}else{
		//everything else is OK lets do schema validation.

		//first we need a php DOM. 


		$dom = dom_import_simplexml($this->XMLObject);
//		echo "does the xml get in??<br>";
//		echo $dom->ownerDocument->saveXML();

		$NCScript = $GLOBALS['config']['NCScript.xsd'];
		//$temp_test = '/tmp/test.xml';
		//$tfh = fopen($temp_test,'w');
		//fwrite($tfh,$dom->ownerDocument->saveXML());

//		error_reporting(0); // I am handling errors for a second!!
		libxml_use_internal_errors(true);
		if(!$dom->ownerDocument->schemaValidate($NCScript)){
			$this->error_message = "
The XML did not validate. 
This usually means that A. you have some data missing from your request. or 
B. you have some unusual forbidden characters like #$%<> in your data. 
Either way, you should call support! Be sure to include the error below!";

			$errors = libxml_get_errors();
    			foreach ($errors as $error) {
        			$this->libxml_record_error($error);
    			}
    			libxml_clear_errors();
			error_reporting(E_ALL ^ E_NOTICE);// set error reporting back to normal!!
			return(false);
		}else{
			return(true);
		}	

	}
}

function getError(){

	return($this->error_message);

}

function asXML(){

	$xml = $this->XMLObject->asXML();

         if(extension_loaded('tidy'))
         {
             $tidy_options = array(
                    'input-xml'    => true,
                    'output-xml'   => true,
                    'indent'       => true,
                    'indent-cdata' => true,
                    'wrap'         => false,
             );
             $tidy_options['input-xml'] = true; // MUST be true
             
             $tidy = new tidy();
             $tidy->parseString($xml, $tidy_options);
             $tidy->cleanRepair();
             
             return (string) $tidy; // by using typecasting we'll ensure it's a string and not an object that is returned
         }
         else {
             return $xml;
         }


}


function getRegisterButton($user,$building){

	$xml =	$this->registerLicensedPrescriberXML($user,$building);
		

	// This is how we make this a template...
	//$data['Patient']['EpisodeIdentifier'] = '{$plan_type} #{$plan_id}';
	//$data['Patient']['EpisodeIdentifier'] = $episode_identifier;

	$url = "secure.newcropaccounts.com";
	$action_url = $GLOBALS['config']['ERXurl'];
//	$GLOBALS['loader']->requireOnce('lib/PEAR/XML/Serializer.php');


	$user_id = $user->get('user_id');


$_html_result = "	<form name='NewCropForm_$user_id' id='NewCropForm_$user_id' method='post' target='NewCropFrame' action='$action_url/InterfaceV7/RxEntry.aspx'>
			<input type='hidden' id='RxInput' name='RxInput' value='$xml'>
			<input type='submit' id='button_$user_id' name='Register'>

		</form>



";

        return $_html_result;




}




function registerLicensedPrescriberXML($user,$building){

	// First we need to calculate basic prescribing permissions...

	$this->addDestination('ws-register-licensedPrescriber');
	$this->addAccount();
	$this->addLocation();
	$this->addLicensedPrescriber();

	return($this->asXML());
	

}




function requestTestRenewalsXML(){


	$this->addDestination('ws-gen-test-renewal');
	$this->addAccount();
	$this->addLocation();
	$this->addLicensedPrescriber();
	$this->addPatient();
	$this->addNewPrescription();
	return($this->asXML());

}


function xml_pretty_printer($xml, $html_output=FALSE)
{
	$xml_obj = new SimpleXMLElement($xml);
	$xml_lines = explode("\n", str_replace("><",">\n<",$xml_obj->asXML()));
	$indent_level = 0;
	
	$new_xml_lines = array();
	foreach ($xml_lines as $xml_line) {
		if (preg_match('#^(<[a-z0-9_:-]+((\s+[a-z0-9_:-]+="[^"]+")*)?>.*<\s*/\s*[^>]+>)|(<[a-z0-9_:-]+((\s+[a-z0-9_:-]+="[^"]+")*)?\s*/\s*>)#i', ltrim($xml_line))) {
			$new_line = str_pad('', $indent_level*4) . ltrim($xml_line);
			$new_xml_lines[] = $new_line;
		} elseif (preg_match('#^<[a-z0-9_:-]+((\s+[a-z0-9_:-]+="[^"]+")*)?>#i', ltrim($xml_line))) {

			$new_line = str_pad('', $indent_level*4) . ltrim($xml_line);
			$indent_level++;
			$new_xml_lines[] = $new_line;
		} elseif (preg_match('#<\s*/\s*[^>/]+>#i', $xml_line)) {
			$indent_level--;
			if (trim($new_xml_lines[sizeof($new_xml_lines)-1]) == trim(str_replace("/", "", $xml_line))) {
				$new_xml_lines[sizeof($new_xml_lines)-1] .= $xml_line;
			} else {
				$new_line = str_pad('', $indent_level*4) . $xml_line;
				$new_xml_lines[] = $new_line;
			}
		} else {
			$new_line = str_pad('', $indent_level*4) . $xml_line;
			$new_xml_lines[] = $new_line;
		}
	}
	
	$xml = join("\n", $new_xml_lines);
	return ($html_output) ? '<pre>' . $this->xmlspecialchars($xml) . '</pre>' : $xml;
}

function xmlspecialchars($text) {
   return str_replace('&#039;', '&apos;', htmlspecialchars($text, ENT_QUOTES, 'UTF-8',false));
}


}





?>
