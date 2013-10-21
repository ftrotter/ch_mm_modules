<?php

$wsdl_url = 
  'http://preproduction.newcropaccounts.com/v7/webservices/update1.asmx?WSDL';

$params   = array(
	'credentials' => array(
		'PartnerName' => 'demo',
		'Name' => 'demo',
		'Password' => 'demo'),
	'accountRequest' => array(
		'AccountId' => 'mirrormed',
		'SiteId' => 'mirrormed'
		),
	'patientRequest' => array(
		'PatientId' => '10646038'
		),
	'prescriptionHistoryRequest' => array(
		'StartHistory' => '2004-01-01T00:00:00.000',
		'EndHistory' => '2009-01-01T00:00:00.000',
    		'PrescriptionStatus'          => 'C',
    		'PrescriptionSubStatus'       => 'S',
    		'PrescriptionArchiveStatus'       => 'N',
		),
	'patientInformationRequester' => array(
		'UserType' => 'MidLevel',
		'UserId' => '1000001'
		),
      'patientIdType' => 'hellifiknow',
      'includeSchema' => 'Y',
);

$client = new SoapClient($wsdl_url);
$meds    = $client->GetPatientFullMedicationHistory5($params);
/*

   'GetPatientFullMedicationHistory5Result' => 
  stdClass::__set_state(array(
     'result' => 
    stdClass::__set_state(array(
       'Status' => 'OK',
       'Message' => '',
       'XmlResponse' => '',
       'RowCount' => 6,
       'Timing' => 0,
    )),
     'patientDrugDetail' => 
    stdClass::__set_state(array(
       'PatientDrugDetail5' => 
      array (


*/


$meds_array = $meds->GetPatientFullMedicationHistory5Result->patientDrugDetail->PatientDrugDetail5;

/*
     'AccountName' => 'mirrormed',
     'ExternalAccountID' => 'mirrormed',
     'SiteID' => 'mirrormed',
     'FullName' => 'tester  test',
     'ExternalPatientID' => '10646038',
     'PrescriptionDate' => '8/10/2008 2:45:00 PM',
     'DrugID' => '240035',
     'DrugTypeID' => 'F',
     'DrugName' => 'Advil',
     'Strength' => '100',
     'StrengthUOM' => 'mg',
     'DosageNumberDescription' => '1',
     'DosageForm' => 'Tablet',
     'Route' => 'Oral',
     'DosageFrequencyDescription' => 'as directed',
     'Dispense' => '1',
     'TakeAsNeeded' => 'N',
     'DispenseAsWritten' => 'N',
     'Refills' => '1',
     'Status' => 'C',
     'SubStatus' => 'S',
     'ArchiveStatus' => 'N',
     'PrescriptionGuid' => '42a80984-fe39-4fcc-9b7a-de668bf254db',
     'OrderGuid' => '',
     'PrescriptionNotes' => '',
     'ExternalPhysicianID' => '1000001',
     'PhysicianName' => 'Bryan d Visser',
     'DateMovedToPreviousMedications' => ' ',
     'HealthPlanID' => '',
     'HealthplanTypeID' => 'S',
     'FormularyCoverage' => '',
     'FormularyStatus' => '',
     'PatientID' => '',
     'PatientIDType' => '',
     'ExternalPrescriptionID' => '',
     'EpisodeIdentifier' => '0',
     'EncounterIdentifier' => '',
     'ExternalSource' => '',




*/


foreach($meds_array as $id => $med){

	echo "Drug! \n";
	foreach($med as $name => $item){

		echo "\t$name = $item \n";

	}


}

?>
