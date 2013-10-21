<?php
/**
 * This is C_MyRecord.class.php
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
 * @see "/ordo/ORDataObject.class.php"
 */
$loader->requireOnce("/ordo/ORDataObject.class.php");
/**
 * @see 
 */
$loader->requireOnce("/includes/Grid.class.php");
/**
 * @see "/includes/Grid.class.php"
 */
$loader->requireOnce("/ordo/User.class.php");
/**
 * @see "includes/AHAHRowEditor.class.php"
 */
$loader->requireOnce("includes/AHAHRowEditor.class.php");
/**
 * @see "datasources/Patient_FullPlanList_DS.class.php"
 */
$loader->requireOnce("datasources/Patient_FullPlanList_DS.class.php");
/**
 * Controller to hold stuff that a patient can see on their record
 *
 * Long Description
 * Long Description  
 *
 * @package ydp
 */
class C_MyRecord extends Controller {

	/**
	 * display the basic PHR view 
         * 
	 * long comment for the class method below 
	 * 
 	 * @return mixed fetch() 
         */
	function actionDefault_view() {
		//$user =& $this->_me->get_user();

// forget the token functionality
/*		$token = $this->_pull_in('token');
		if(!$token){
			echo "Error: we need a token to function. This error should have been caught earlier";
			exit(0);
		}

		$patient_id = $this->_pull_in('patient_id');
		if(!$patient_id){
			echo "Error: we need a patient_id to function. This error should have been caught earlier";
			exit(0);
		}
*/
		$me =& Me::getInstance();
		$user =& $me->get_user();
		$patient_id = $user->get('person_id');
		$person =& ORDataObject::factory('Patient',$patient_id);
		$this->assign('patient_id',$patient_id);
		$this->assign('INBOX_LINK',Celini::link('inbox','MyMessages'));	
		$this->assign('SENT_LINK',Celini::link('sent','MyMessages'));	
		$this->assign('COMPOSE_LINK',Celini::link('compose','MyMessages'));	
		$this->_build_summary_data($patient_id);
		$this->_build_file_data($patient_id);
		//FRED move to config file
		//
		$report_id = 600232;
		$template_id = 600234;

		$this->_build_report_data($patient_id,$report_id,$template_id);

	//	$tokenobject =& ORDataObject::Factory('Token',null);
	//	$new_token = $tokenobject->new_random($patient_id);
	//	echo "the new token $new_token <br>";

// two hawiian
// one works
		//the token is valid and we need to display the proper template.
		$no_editing = false;
		$PlanListDS = new Patient_FullPlanList_DS($patient_id,false);
		$planRowEditor =& new AHAHRowEditor($PlanListDS,false);

		$this->assign_by_ref('planRowEditor',$planRowEditor);

		return $this->fetch(Celini::getTemplatePath("/myrecord/" . $this->template_mod . "_patient.html"));

	}
       	/**
	 * short comment 
         * 
	 * long comment for the class method below 
	 * 
 	 * @return string
         */
	function actionEmail_add() {

//Then add a similar email system to C_MyOutcomes.class.php to give access to the outcomes form
//After an outcome is filled out the YDP Summary should be sent. 
//Then make it send emails based on a cron job...
//Add in patient fact based authentication.

	
 
	if(!array_key_exists('patient_id',$_GET)){
		$this->messages->addmessage('You must have both a patient_id and a survey_id to send a survey...');

		return;
	}else{

		$patient_id = $_GET['patient_id'];
	} 
	ini_set("include_path", CELLINI_ROOT."/includes/phpmailer");
	require("class.phpmailer.php");

	$mail = new PHPMailer();
	$mail->From     = "YDP@yourdoctorprogram.com";
	$mail->FromName = "Your Doctor Program";
	$mail->WordWrap = 50;                              // set word wrap
	$mail->IsHTML(true);                               // send as HTML
	$mail->Subject  =  "YDP Summary Access";

	$patient =& ORDataObject::Factory('Patient',$patient_id);
	$this->assign_by_ref('patient',$patient);
	$mail->AddAddress($patient->get('email'),$patient->get('first_name')." ".$patient->get('last_name')); 
	

	$tokenobject =& ORDataObject::Factory('Token',null);
	$token = $tokenobject->new_random($patient_id);
	$tokenobject->persist();

	$server = $_SERVER['SERVER_NAME'];	

	$path_parts = split('/',$_SERVER['REQUEST_URI']);
	$instance_name = $path_parts[1];

	$fred_url = "http://localhost:81/kim_dunn/";
	$main_url = "https://$server/$instance_name/";
        $url_end = "index.php/menu/MyRecord/default?patient_id=$patient_id&token=$token";
	$link = $main_url.$url_end;
	$fred_link = $fred_url.$url_end;	

	$this->assign('patient_id',$patient_id);
	$this->assign('token',$token);
	$this->assign('link',$link);
	$this->assign('fred_link',$fred_link);


	$mail->Body     =  $this->fetch(Celini::getTemplatePath("/myrecord/record_email_html.html"));
	$mail->AltBody  =  $this->fetch(Celini::getTemplatePath("/myrecord/record_email_txt.html"));

	if(!$mail->Send())
	{
   		echo "Message was not sent <p>";
   		echo "Mailer Error: " . $mail->ErrorInfo;
   		exit;
	}

		return "Message has been sent";
	

	}


	/**
	 * This overrides the report action view for this controller. 
	 * It is intended to further limit what reports are available... 
	 * 
 	 * @return mixed fetch()
         */
	function actionPatientreport_patientview() {
		$loader->requireOnce("/includes/Grid.class.php");
		$loader->requireOnce("/includes/ReportFilter.class.php");


                if(array_key_exists('template_id',$_GET)){
			$template_id = $_GET['template_id'];
                }else{

                        if(array_key_exists('template_id',$_POST)){
				$template_id = $_POST['template_id'];
                        }else{
				$template_id = 0;
			}//its ok not to have a template_id

                }


                if(array_key_exists('report_id',$_GET)){
			$report_id = $_GET['report_id'];
                }else{

                        if(array_key_exists('report_id',$_POST)){
				$report_id = $_POST['report_id'];
                        }else{//we have not patient id so we need a login
				trigger_error("ERROR we must have a report_id",E_USER_ERROR); 
				exit();
                        }

                }


	//	echo "report_id $report_id template_id $template_id";
		$r =& ORDataObject::factory('Report',$report_id);
		$templates = $r->get('templates');
		if (isset($templates[$template_id])) {
			$template_name = $templates[$template_id]['name'];
			if ($templates[$template_id]['is_default'] === 'no') {
				$template = APP_ROOT."/user/report_templates/$template_id.tpl.html";
				if (!file_exists($template)) {
					$template = "default";
				}
			}
			else {
				$template = "default";
			}
		}
		else {
			$template_name = "Default Template";
			$template = "default";
		}

		$this->assign("TOP_ACTION", Celini::link('report')."report_id=$report_id");
		$this->assign("REPORT_ACTION", Celini::link('report')."report_id=$report_id&template_id=$template_id");
		if (!isset($_GET['gridMode'])) {
			$mode = "htmldoc";
			if (isset($GLOBALS['config']['pdfGenerator'])) {
				$mode = $GLOBALS['config']['pdfGenerator'];
			}
			$this->assign('PDF_ACTION',str_replace(array('index.php/main','index.php/util'),'index.php/PDF',$_SERVER['REQUEST_URI'])."&gridMode=$mode");
			$this->assign('PRINT_ACTION',str_replace('index.php/main','index.php/util',$_SERVER['REQUEST_URI'])."&gridMode=$mode");
		}
		$this->assign("report",$r);
		$this->assign("template_name",$template_name);

		$queries = $r->get('exploded_query');
		$reports = array();
		if (count($queries) == 0) {
			$queries['default'] = $r->get('query');
		}
		foreach($queries as $key => $query) {
			if (strstr($key,',')) {
				$flags = explode(',',$key);
				$key = array_shift($flags);
				$reports[$key]['flags'] = $flags;
			}
			$reports[$key]['filter'] =& new ReportFilter($query);
		//This generates an notice but is still good code
		$errorlevel=error_reporting();
		error_reporting($errorlevel & ~E_NOTICE);
			$reports[$key]['filter']->setAction($this->_tpl_vars['REPORT_ACTION']);
		
		// Return to standard error reporting...
		error_reporting($errorlevel);

			$reports[$key]['ds'] =& $reports[$key]['filter']->getDatasource();
			foreach($reports[$key]['filter']->dsFilters as $k => $val) {
				if (strstr($val[0],'&')) {
					$tmp = explode('&',$val[0]);

					if ($tmp[1] == 'ds') {
						$val[0] = array($reports[$key]['ds'],$tmp[0]);
					}
					else {
						$val[0] = array(${$tmp[1]},$tmp[0]);
					}
				}
				$reports[$key]['ds']->registerFilter($k,array_shift($val),$val);
			}

			if (isset($flags) && in_array('class',$flags)) {
				$extra = $reports[$key]['filter']->extraData;
				$c = "Report_".$extra['class'];
				if (!class_exists($c)) {
					if (file_exists( APP_ROOT."/local/includes/$c.class.php")) {
						require_once APP_ROOT."/local/includes/$c.class.php";
					}
				}

				if (!class_exists($c)) {
					trigger_error("Unable to load class $c for dataset $key",E_USER_ERROR); 
				}

				$o = new $c();
				foreach($extra as $k => $v) {
					$o->$k = $v;
				}

				$reports[$key]['ds'] =& $o->getDs();
			}

			$reports[$key]['grid'] =& new cGrid($reports[$key]['ds']);
			$reports[$key]['grid']->pageSize = 30;
			$reports[$key]['grid']->name = $key;
			if ($key != 'default') {
				$reports[$key]['title'] = $key;
			}
			
			// Setup export
			$reports[$key]['grid']->setExternalId($report_id);
			$reports[$key]['grid']->setExportAction('export_report');
			$reports[$key]['grid']->setExtraURI('&name=' . $reports[$key]['grid']->name);
			$reports[$key]['ds']->_type = 'html';
		}
		$this->assign_by_ref("reports",$reports);

		if ($template === "default") {
			return $this->fetch(Celini::getTemplatePath("/report/" . $this->template_mod . "_view.html"));	
		}
		else {
			foreach(array_keys($reports) as $key) {
				$this->assign_by_ref($key.'_filter',$reports[$key]['filter']);
				$this->assign_by_ref($key.'_extra',$reports[$key]['filter']->extraData);

				$this->assign_by_ref($key.'_title',$reports[$key]['title']);
				$this->assign_by_ref($key.'_ds',$reports[$key]['ds']);
				$this->assign_by_ref($key.'_grid',$reports[$key]['grid']);
				$this->assign_by_ref($key.'_flags',$reports[$key]['flags']);
				//var_dump($key);
			}

			return $this->fetch($template);
		}
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
	function _build_summary_data($patient_id)
	{
		$patient =& ORDataObject::Factory('Patient',$patient_id);
		$provider_user	=& User::fromId($patient->get('default_provider'));
		$provider =& ORDataObject::Factory('Person',$provider_user->get('person_id'));
		$address =& $patient->address();
		$provider_address =& $provider->address();
		$number =& $patient->numberByType('Home');
		$provider_number =& $provider->numberByType('Work');
		$this->assign_by_ref('provider',$provider);
		$this->assign_by_ref('patient',$patient);
		$this->assign_by_ref('number',$number);
		$this->assign_by_ref('provider_number',$provider_number);
		$this->assign_by_ref('address',$address);
		$this->assign_by_ref('provider_address',$provider_address);
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
	function _build_file_data($patient_id){

		
		//HACKME to use another app root... 
		// or maybe do some softlinks.
		$dir = APP_ROOT ."/user/documents/$patient_id/";

		if (is_dir($dir)) {
			$dh  = opendir($dir);
			while (false !== ($filename = readdir($dh))) {
			if(!(strcmp($filename,"..")==0||strcmp($filename,".")==0))
			   	$file_links[$filename] = 
					Celini::link('download','MyDownloads')
					."patient_id=$patient_id&file=$filename";
			}
			$this->assign("file_links",$file_links);
		}
//	$ds->template['last_edit'] = '<a href="'.Celini::link('data').'id={$form_data_id}">{$last_edit}</a>'
//	$back_link = Celini::link('dashboard','patient',true,$data->get('external_id'));

	}	

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string
 	* @param string
 	*
 	* @return void 
        */
	function _build_report_data($patient_id,$report_id,$template_id){

		return;
/*
		// Get list of encounters...
		// foreach encounter build a link...
		$patient = ORDataObject::Factory("patient",$patient_id);
		$encounter_ds = $patient->loadDatasource('EncounterList');
		$encounter_list = $encounter_ds->toArray();

		$report_links = array();

		foreach($encounter_list as $encounter){
			$label = $encounter['date_of_treatment']." - ".$encounter['encounter_id']." - ".$encounter['treating_person'];
			
			$report_links[$label] =  
			"patientreport?".
			"report_id=$report_id&template_id=$template_id&patient_id=$patient_id".
			"&encounter_id=".$encounter['encounter_id'];
		}

		$this->assign("report_links",$report_links);

*/

	}	

}
?>
