<?php
/**
 * This is C_YDPPlan.class.php
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
//$loader->requireOnce("ordo/VaDrug.class.php");
/**
 * @see "includes/Grid.class.php"
 */
$loader->requireOnce("includes/Grid.class.php");
/**
 * @see "includes/AHAHRowEditor.class.php" 
 */
$loader->requireOnce("includes/AHAHRowEditor.class.php");
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
/**
 * @see 'datasources/Message_PatientMessageDataList_DS.class.php'
 */
$loader->requireOnce('datasources/Message_PatientMessageDataList_DS.class.php');

//require_once APP_ROOT ."/local/datasources/Patient_PlanList_DS.class.php";

/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class C_YDPPlan extends Controller {

	/**
         * @var int
         */
	var $number_id = 0;
	/**
         * @var int
         */
	var $patient_id = 0;
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
	function C_YDPPlan ($template_mod = "general") {
		parent::Controller();
		$this->template_mod = $template_mod;

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
	function actionView($patient_id = 0){
		if($patient_id == 0){
		if($this->patient_id == 0){
			$patient_id =  $this->getorpost('patient_id');
		}else{
			$patient_id = $this->patient_id;
		}
		}
        	$session =& Celini::sessionInstance();
	        $session->set('last_patient_id',$patient_id);

		$person =& ORDataObject::factory('Patient',$patient_id);
		
		$this->assign('ADD_ACTION',Celini::link('add','YDPPlan')."patient_id=$patient_id");
		$this->assign('AUTO_AJAXACTION',Celini::link('auto','YDPPlan','blank')."patient_id=$patient_id");
		$this->assign('DOCUMENT_LINK',Celini::link('list','Document','menu')."id=$patient_id");
		$this->assign('PATIENT_MESSAGES_LINK',Celini::link('patient','Messages','menu')."id=$patient_id");

		$this->_report_setup($person);

		$planRowEditor =& new AHAHRowEditor($person->loadDatasource('FullPlanList'));

		$this->assign_by_ref('planRowEditor',$planRowEditor);
		$this->assign('patient_id',$patient_id);
		$this->assign('WEB_ROOT',WEB_ROOT);


                $messageDS = new Message_PatientMessageDataList_DS($person->get('person_id'));
                $messageGrid = new cGrid($messageDS);
                $messageGrid->name = "patientMessageGrid";
                $messageGrid->pageSize = 5;
                $messageGrid->indexCol = false;
                $messageGrid->setExternalId($person->get('id'));
                $this->assign_by_ref('patientMessageGrid',$messageGrid);



		return $this->fetch(Celini::getTemplatePath("/ydpplan/" . $this->template_mod . "_ydpplan.html"));
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
	function _report_setup($person){

	$provider = $person->get_defaultProviderPerson();
	$this->assign('provider',$provider);
	$this->assign('person',$person);
	$this->assign('DASHBOARD_LINK',Celini::link('view','PatientDashboard')."id=".$person->get('id'));
	$this->assign('MESSAGE_LINK',Celini::link('new','Messages')."patient_id=".$person->get('id'));



	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return mixed
        */
	function actionAdd_edit(){

		if($this->patient_id == 0){
			$patient_id =  $this->getorpost('patient_id');
		}else{
			$patient_id = $this->patient_id;
		}

		

		list($first_part, $second_part) = split('_',$_POST['autocomplete_hidden']);

		
		if(is_numeric($first_part)){

			$code_id = $first_part;
			$protocol_id = $second_part;
			$myCode =& ORDataObject::Factory('Code',$code_id);
			$code = $myCode->get('code');
			$code_text = $myCode->get('code_text');

			$code_description = "$code: $code_text";
		}else{

			$code_text = $second_part;
			$code_description =  "Free Text: $code_text";
			$code_id = 0;
                        $protocol = "Other";
                        $protocol_id = 15;
                        // TODO find a non-hardcoded way to do above...

		}

	

		$provider_id = $_POST['provider_id'];

		if($provider_id == 0){
			$provider_text = $_POST['provider_text'];
			$provider_phone_text = $_POST['provider_phone_text'];
			$provider_email_text = $_POST['provider_email_text'];

			$provider_text = $provider_text . "\n" . $provider_phone_text . "\n" .
						$provider_email_text;
		}else{

			$provider_text = '';
		}

		$my_protocol =& ORDataObject::Factory('YDPProtocol',$protocol_id);
		$protocol_name = $my_protocol->get('name');
		
		$my_plan_array = array(
			'patient_id' => $patient_id,
			'provider_id' => $provider_id,
			'provider_text' => $provider_text,
			'plan_type' => $protocol_id,
			'code_id' => $code_id,
			'plan_name' => "$code_description",
			'plan_status' => 1,
			'interventions' => $my_protocol->get('interventions'),
			'goals' => $my_protocol->get('treatment_goals'),
			'meds' => 'notes'
			);


		$plan =& ORDataObject::Factory('Plans');
		$plan->populate_array($my_plan_array);
		$this->patient_id = $patient_id;
		if($plan->persist()){
			$this->messages->addMessage('Plan Added');
			return $this->actionView();
		}else{
			$this->messages->addMessage('Plan Already Exists: Modify current plan.');

			return $this->actionView();
		}

		

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return string
        */
	function actionAuto(){

		if($this->patient_id == 0){
			$patient_id =  $this->getorpost('patient_id');
		}else{
			$patient_id = $this->patient_id;
		}
		$person =& ORDataObject::factory('Patient',$patient_id);


		$search_string = $this->getorpost('search_string');
		
		$sql = "SELECT 
				codes.code_id,
				codes.code_text,
				codes.code_text_short,
				codes.code,
				codes.code_type,
				codes.modifier,
				ydp_protocol.id as protocol_id,
				ydp_protocol.name
 	 		FROM `codes` 
			LEFT JOIN codes_to_protocol on codes_to_protocol.code_id = codes.code_id 
			LEFT JOIN ydp_protocol on ydp_protocol.id = codes_to_protocol.protocol_id 
			WHERE 
				`code_text` LIKE '%$search_string%' 
				OR `code` LIKE '%$search_string%' ";






//		syslog($sql);

		$my_codes = $this->_get_codes($sql);

		if(count($my_codes)<5){

			$search_array = split(' ',$search_string);
			$sql = "SELECT 
				codes.code_id,
				codes.code_text,
				codes.code_text_short,
				codes.code,
				codes.code_type,
				codes.modifier,
				ydp_protocol.id as protocol_id,
				ydp_protocol.name
 	 		FROM `codes` 
			LEFT JOIN codes_to_protocol on codes_to_protocol.code_id = codes.code_id 
			LEFT JOIN ydp_protocol on ydp_protocol.id = codes_to_protocol.protocol_id 
			WHERE ";
				
				$first = true;
				foreach($search_array as $term){
					if(!$first){
						$sql .= " AND ";
					}else{
						$first = false;
					}

					$sql .= " `code_text_short` LIKE '%$term%'"; 
					
				}

				//`code_text` LIKE '%$search_string%' 
				//OR `code` LIKE '%$search_string%' ";


			$my_codes = $my_codes + $this->_get_codes($sql);


		}

		$free_text_option = "<li id='freetext_$search_string' class='free_text_button'> <span class='informal'>Create a plan without an ICD code using the text </span>$search_string</li>";


	
		if(count($my_codes)>0){
			$return_me = "<ul>\n";
			foreach($my_codes as $this_code){
				$return_me .= "<li id='".$this_code['code_id']."_".$this_code['protocol_id']."'>";
				$return_me .= $this_code['code_type'].":".$this_code['code']." > ".$this_code['protocol'];
				$return_me .= "<span class='informal'> : ".$this_code['code_text']."</span>";
				$return_me .= "</li>";
			}
			$return_me .= "  $free_text_option  </ul>";

			return $return_me;
		}
	
		return "<ul><li id='0' class='no_results_button'><span class='informal'><font color='red'> No ICD codes or descriptions match '$search_string' </font> </span></li>  $free_text_option  </ul> ";	

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param array 
 	*
 	* @return array
        */
	function _get_codes($sql){


		 $db =& new clniDB();

	        $res = $db->execute($sql);
		$my_codes = array();
                while($res && !$res->EOF) {
			$array = $res->fields;
			$code =  $res->fields['code'];
			$code_id =  $res->fields['code_id'];
			$code_text =  $res->fields['code_text'];
			$protocol =  $res->fields['name'];
			$protocol_id =  $res->fields['protocol_id'];
			if(strlen($protocol) == 0){
				$protocol = "Other";
				$protocol_id = 15;	
			// TODO find a non-hardcoded way to do above...
			}

                        $my_codes[$code_id] = array (
					'code' => $code,
					'code_id' => $code_id,
					'code_text' => $code_text,
					'code_type' => 'ICD9',
					'protocol' => $protocol,
					'protocol_id' => $protocol_id,
					'full_array' => $res->fields
					);

                        $res->MoveNext();
                }
		
		return($my_codes);

	}



	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	*
 	* @return void
        */
	function actionDrugadd($patient_id){

		if($this->patient_id == 0){
			$patient_id =  $this->getorpost('patient_id');
		}else{
			$patient_id = $this->patient_id;
		}
		$plan_id = $this->getorpost('plan_id');
		$drug_key = $this->getorpost('drug_key');


		list($table_code, $drug_id, $plan_id) = split('_',$drug_key);

		$table_code_key = array(
			't' => 'va_tradenames',
			'p' => 'va_productname'
		); 
		
		$table = $table_code_key[$table_code];
		$newDrug =& Celini::newORDO('VaDrug');
		$newDrug->set('patient_id',$patient_id);
		$newDrug->set('external_id',$plan_id);
		$newDrug->set('table',$table);
		$newDrug->set('drug_id',$drug_id);
		$newDrug->persist();	

		header("HTTP/1.1 204 No Content");

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
	function actionDrugremove($va_drug_id){

		$va_drug_id =  $this->getorpost('va_drug_id');
		list($va_drug_id,$plan_id) = split('_',$va_drug_id);

		$newDrug =& Celini::newORDO('VaDrug',$va_drug_id);
		$newDrug->remove();	

		header("HTTP/1.1 204 No Content");

	}




	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	*
 	* @return int
        */
	function actionDrugresults($patient_id){

		$url = WEB_ROOT;

		if($this->patient_id == 0){
			$patient_id =  $this->getorpost('patient_id');
		}else{
			$patient_id = $this->patient_id;
		}
		$plan_id = $this->getorpost('plan_id');
		
		$newDrug =& Celini::newORDO('VaDrug');
		$to_return = $newDrug->getPrettyList($patient_id,$plan_id);
	
		return $to_return;	
		

	}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return string
        */
	function actionDrug(){

		if($this->patient_id == 0){
			$patient_id =  $this->getorpost('patient_id');
		}else{
			$patient_id = $this->patient_id;
		}
		$person =& ORDataObject::factory('Patient',$patient_id);

		 $db =& new clniDB();
		$plan_id = $this->getorpost('plan_id');
		$search_string = $this->getorpost('search_string');
	
		if(strlen($search_string) < 4){
			return '';		
		}

	//	$search_string = 'viagra';
	//	echo $search_string;
	
		$sql = "
SELECT 'tradename' as tablename , `id`, `name`
FROM `va_tradenames`
WHERE `name` LIKE '%$search_string%'
UNION
SELECT 'productname' as tablename, `id`, `name`
FROM `va_productname`
WHERE `name` LIKE '%$search_string%'";

	//	echo $sql;

	        $res = $db->execute($sql);
		$my_drugs = array();
                while($res && !$res->EOF) {
			$array = $res->fields;
			$drug =  $res->fields['name'];
			$drug_id =  $res->fields['id'];
			$chooser_id = $res->fields['tablename'][0]."_".$drug_id."_".$plan_id;
                        $my_drugs[] = array (
					'drug' => $drug,
					'drug_id' => $drug_id,
					'chooser_id' => $chooser_id,
					'full_array' => $res->fields
					);

                        $res->MoveNext();
                }


	//	var_export($my_drugs);
	
		if(count($my_drugs)>0){
			$return_me = "<ul>\n";
			foreach($my_drugs as $this_drug){
				$return_me .= "<li id='".$this_drug['chooser_id']."'>";
				$return_me .= $this_drug['drug'];
				$return_me .= "</li>";
			}
			$return_me .= "\n</ul>";

			return $return_me;
		}
	
		return '<ul><li>No Matches</li></ul>';	

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return string
        */
	function actionRow_edit(){

		$row_id = $_GET['row_id'];
		$col_id = $_GET['col_id'];
		
		$plan =& ORDataObject::Factory('Plans',$row_id);
		$patient_id =& $plan->get('patient_id');

		$main_controller = $GLOBALS['config']['menu_controller'];


		$form_link = Celini::link('RowSubmit','YDPPlan',$main_controller);
		$this->assign('form_link',  $form_link);
		$this->assign('provider_id',  $plan->get('provider_id'));
		$this->assign('patient_id',  $plan->get('patient_id'));
		$this->assign('plan_id', $row_id);
		
		switch($col_id){

		case "risk_factors":
			return $this->fetch(Celini::getTemplatePath("/ydpplan/" . $this->template_mod . "_risksform.html"));
		break;

		case "plan_status":
			$this->assign('current_status',$plan->get('plan_status'));	
			return $this->fetch(Celini::getTemplatePath("/ydpplan/" . $this->template_mod . "_statusform.html"));
		break;
	
		case "plan_name":
			$this->assign('current_plan_name',$plan->get('plan_name'));	
			return $this->fetch(Celini::getTemplatePath("/ydpplan/" . $this->template_mod . "_plannameform.html"));
		break;

		case "goals":
			$this->assign('current_goals',$plan->get('goals'));	
			return $this->fetch(Celini::getTemplatePath("/ydpplan/" . $this->template_mod . "_goalsform.html"));
		break;

		case "interventions":
			$this->assign('current_interventions',$plan->get('interventions'));	
			return $this->fetch(Celini::getTemplatePath("/ydpplan/" . $this->template_mod . "_interventionsform.html"));
		break;

		case "meds":
		//	$this->assign('current_interventions',$plan->get('medications'));	
			return $this->fetch(Celini::getTemplatePath("/ydpplan/" . $this->template_mod . "_medicationsform.html"));
		break;
		

		case "doc":
			return $this->fetch(Celini::getTemplatePath("/ydpplan/" . $this->template_mod . "_docform.html"));
		break;
	

		case "plan_type":
    		default:
			return "<div style='background-color:#FFFF99; border:2px solid #999999;'> no editing $col_id for $patient_id</div>";
    		break;


		}

		return "<form action='$form_link' method='POST'> I am getting row $row_id and col $col_id <INPUT type='submit' name='mysubmit' value='Click!'> </form>";


	}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return mixed
        */
	function actionRowSubmit_edit(){

		$process = false;
		if(array_key_exists('plan_id',$_POST)){
			$plan_id = $_POST['plan_id'];
			$col_id = $_POST['col_id'];
			$process = true;
		}
		$plan =& ORDataObject::Factory('Plans',$plan_id);
		if(array_key_exists('risks',$_POST)){
			$my_risks = implode(',',$_POST['risks']);
			$_POST['risk_factors'] = $my_risks;
		}
		$provider_id = $this->getorpost('provider_id');
		if($provider_id == 0){
			$provider_text = $this->getorpost('provider_text');
			$provider_phone_text = $this->getorpost('provider_phone_text');
			$provider_email_text = $this->getorpost('provider_email_text');

			$provider_text = $provider_text . "\n" . $provider_phone_text . "\n" .
						$provider_email_text;
		}else{

			$provider_text = '';
		}
		$_POST['provider_text'] = $provider_text;
		if($process){
			$plan->populate_array($_POST);
			$plan->persist();
		}




		$this->patient_id = $plan->get('patient_id');

		return $this->actionView();


	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string
 	*
 	* @return boolean
        */
	function getorpost($name){

		if(isset($_POST[$name])) //if its in POST return that...
			return($_POST[$name]);

 
		if(isset($_GET[$name])) //if its in GET return that...
 			return($_GET[$name]);

		return(false);
	}	

	






}

?>
