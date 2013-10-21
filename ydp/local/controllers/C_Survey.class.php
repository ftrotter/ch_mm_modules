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
 * @see 'includes/DateObject.class.php' 
 */
$loader->requireOnce('includes/DateObject.class.php');
/**
 * @see "datasources/Survey_List_DS.class.php"
 */
$loader->requireOnce("datasources/Survey_List_DS.class.php");
/**
 * @see "includes/phpmailer/class.phpmailer.php"
 */
$loader->requireOnce("includes/phpmailer/class.phpmailer.php");
/**
 * @see "ordo/Patient.class.php"
 */
$loader->requireOnce("ordo/Patient.class.php");

// old way....
//	ini_set("include_path", CELLINI_ROOT."/includes/phpmailer");
//	require("class.phpmailer.php");

/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class C_Survey extends Controller {

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* 
 	* @return void
        */
	function C_Survey ($template_mod = "general") {
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
 	* @return string
        */
	function actionNew_edit($encounter_id = 0) {

		if($encounter_id == 0){
			$encounter_id = $_GET['encounter_id'];
		}
	
		if($encounter_id == 0){

			return "No encounter_id, no survey created";
		}

		$encounter =& ORDataObject::factory('Encounter',$encounter_id);
		$patient_id = $encounter->get('patient_id');

		$survey =& ORDataObject::factory('Survey');	
		$forms_to_make = $survey->_load_enum("surveys_to_make_from_encounters");
		$forms_to_make = array_flip($forms_to_make);
		//hack
		$forms_to_make = array('1004813');
		//var_export($forms_to_make);
		$status_array = array_flip($survey->getSurveyStatusList());
		$unsent_status = $status_array['unsent'];

		foreach($forms_to_make as $form_id){
			if($form_id != 0){
				$survey_array = array();
				$survey_array['form_id']=$form_id;
				$survey_array['external_id']=$encounter->get('id');
				$survey_array['patient_id']=$patient_id;
				$survey_array['contacted']=0;
				$survey_array['status']=$unsent_status;//FRED hardcoded unsent.. should respect enum
				$survey_array['to_contact']=3;//FRED should be configurable!!
				$survey_array['next_contact_date']=$survey->getNextContactDate(3);//FRED configure
				$survey_array['survey_id']=0;//force a new object to be saved by erasing the id!!
				$survey->populate_array($survey_array);
				$survey->persist();//save to the db
			}
			
		} 

		return ("survey created for encounter_id = $encounter_id and patient_id $patient_id");

	}

	/**	
        * short comment
        *
	* long comment for the class method below
 	* 
 	* @return mixed fetch()
        */
	function actionDefault_view() {
		
// Display links to the forms for each of the surveys

	$user_id = $this->_me->get_id();
//	echo $user_id;

	echo "You are in default_action_view";
	
	return $this->fetch(Celini::getTemplatePath("/survey/" . $this->template_mod . "_default.html"));
	
	}

	/**	
        * short comment
        *
	* long comment for the class method below
 	* 
 	* @return void
        */
	function actionProcess_patientview() {
		
// Display links to the forms for each of the surveys

	$user_id = $this->_me->get_id();
	echo "user: $user_id";

	echo "You are in process_action_usage";
	
//	process form here...	



	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	* 
 	* @return mixed fetch()
        */
	function actionFillout_view($survey_id = 0) {
		
	$survey_id = $_GET['survey_id'];
	
	if(array_key_exists('form_data_id',$_GET)){
		$form_data_id = $_GET['form_data_id'];
	}else{
		$form_data_id = 0;
	}

	if($survey_id == 0){
		echo "You must have a survey_id to access this system...";
		exit();
	} 

	$survey =& ORDataObject::factory('Survey',$survey_id);
	$patient_id = $survey->get('patient_id');
	$status_array = array_flip($survey->getSurveyStatusList());
	$completed_status = $status_array['completed'];


	$myrecord_url = Celini::link('default','MyRecord');//this syntax avoids the main controller

	if($survey->get('status')==$completed_status){// this survey has been completed already
		echo "This Survey has already been completed";		
	//	header("Location: $myrecord_url");	
	//	exit();
	}

	$form_id = $survey->get('form_id');
	$this->assign('survey_id',$survey_id);
	

	//Taken from C_Form.class.php and then reduced 
	$form =& ORDataObject::factory('Form',$form_id);

	if(array_key_exists('process',$_POST)){//Then this form has been submitted...
	//so we say thankyou and send the patient to the summary page!!

		$data =& ORDataObject::factory('FormData');
		$data->populate_array($_POST);
		$data->set('form_id',$form_id);
			
		if(array_key_exists('external_id',$_POST)){
			$data->set('external_id',$_POST['external_id']);
       		}else{
		//	$data->set('external_id',$this->get('external_id','c_patient'));
		//not sure this applies
		}

		$data->set('last_edit',date('Y-m-d H:i:s'));
		$data->persist();


		
		$status_array = array_flip($survey->getSurveyStatusList());
		$completed_status = $status_array['completed'];
		$survey->set('status',$completed_status);
		$survey->persist();


		header("Location: $myrecord_url");		
		exit();	
	
	}else{// this form has not been submitted. we need to show the content of the survey
		//Taken from C_Form.class.php and then reduced 

		//echo "C_Survey: form_id =$form_id <br>";
		
		if ($form_id == false) {
			$ds =& $form->formList();
			$ds->template['name'] = '<a href="'.Celini::link('fillout').
						'patient_id={$patient_id}&survey_id={$survey_id}">{$name}</a>';
			$grid =& new cGrid($ds);
			$this->assign_by_ref('grid',$grid);
		}
		else {
			if (isset($this->form_data_id)) {
				$form_data_id = $this->form_data_id;
			}
			$this->assign('FORM_ACTION',Celini::link('fillout').
						"patient_id=$patient_id&survey_id=$survey_id&form_data_id=$form_data_id");
			$data =& ORDataObject::factory('FormData',$form_data_id);
			$this->assign_by_ref('form',$form);
			$this->assign_by_ref('data',$data);
			$this->secure_dir[] = APP_ROOT."/user/form/";
			$encounter =& ORDataObject::Factory('Encounter',$data->get('external_id'));

			$encounter =& ORDataObject::Factory('Encounter',$survey->get('external_id'));

			//Allow forms to save the user that filled them...
			// This is the modifications I made to make the form permenantly  editable...
			//which we do not want for a patient survey
		 // 	$user_id = $this->_me->get_id();
		//	$this->assign("patient_id",$patient_id);
      		//	$this->assign("user_id",$user_id);

			
			if($patient_id = $encounter->get('patient_id')){
				//this is an encounter form
				$this->assign("date_of_treatment",$encounter->get('date_of_treatment'));
			}else{
				//this is a patient form
				$patient_id = $data->get('external_id');
				$this->assign("date_of_treatment",false);
			}

		}
		return $this->fetch(Celini::getTemplatePath("/form/" . $this->template_mod . "_fillout.html"));
		//Note that this system works BECAUSE we are referring the the "form" template!!!
	}

	
//	display form code goes here...
	
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param int
 	* 
 	* @return mixed fetch()
        */

	function actionUserfillout_view($patient_id = 0, $survey_id = 0) {
		
	$patient_id = $_GET['patient_id'];
	$survey_id = $_GET['survey_id'];
	
	if(array_key_exists('form_data_id',$_GET)){
		$form_data_id = $_GET['form_data_id'];
	}else{
		$form_data_id = 0;
	}

	if($patient_id == 0 || $survey_id == 0){
		echo "You must have both a patient_id and a survey_id to access this system...";
		exit();
	} 

	$survey =& ORDataObject::factory('Survey',$survey_id);
	$status_array = array_flip($survey->getSurveyStatusList());
	$completed_status = $status_array['completed'];

//	if(array_key_exists('token',$_POST)){$token = $_POST['token'];}
//	if(array_key_exists('token',$_GET)){$token = $_GET['token'];}

//	$myrecord_url = Celini::link('default','MyRecord','My').//this syntax avoids the main controller
//			"patient_id=$patient_id&token=".$token;

	if($survey->get('status')==$completed_status){// this survey has been completed already
		echo "This Survey has already been completed";		
//		header("Location: $myrecord_url");
	
		exit();
	}

	$form_id = $survey->get('form_id');
	$this->assign('survey_id',$survey_id);
	

	//Taken from C_Form.class.php and then reduced 
	$form =& ORDataObject::factory('Form',$form_id);

	if(array_key_exists('process',$_POST)){//Then this form has been submitted...
	//so we say thankyou and send the patient to the summary page!!

		$data =& ORDataObject::factory('FormData');
		$data->populate_array($_POST);
		$data->set('form_id',$form_id);
			
		if(array_key_exists('external_id',$_POST)){
			$data->set('external_id',$_POST['external_id']);
       		}else{
		//	$data->set('external_id',$this->get('external_id','c_patient'));
		//not sure this applies
		}

		$data->set('last_edit',date('Y-m-d H:i:s'));
		$data->persist();


		
		$status_array = array_flip($survey->getSurveyStatusList());
		$completed_status = $status_array['completed'];
		$survey->set('status',$completed_status);
		$survey->persist();

		//header("Location: $myrecord_url");		
		//exit();
		return $this->fetch(Celini::getTemplatePath("/survey/" . $this->template_mod . "_manualfillout.html"));

	
	
	}else{// this form has not been submitted. we need to show the content of the survey
		//Taken from C_Form.class.php and then reduced 
		
		if ($form_id == false) {
			$ds =& $form->formList();
			$ds->template['name'] = '<a href="'.Celini::link('userfillout').
						'patient_id={$patient_id}&survey_id={$survey_id}">{$name}</a>';
			$grid =& new cGrid($ds);
			$this->assign_by_ref('grid',$grid);
		}
		else {
			if (isset($this->form_data_id)) {
				$form_data_id = $this->form_data_id;
			}
			$this->assign('FORM_ACTION',Celini::link('userfillout').
						"patient_id=$patient_id&survey_id=$survey_id&form_data_id=$form_data_id");
			$data =& ORDataObject::factory('FormData',$form_data_id);
			$this->assign_by_ref('form',$form);
			$this->assign_by_ref('data',$data);
			$this->secure_dir[] = APP_ROOT."/user/form/";
			
			$encounter =& ORDataObject::Factory('Encounter',$survey->get('external_id'));

			//Allow forms to save the user that filled them...
			// This is the modifications I made to make the form permenantly  editable...
			//which we do not want for a patient survey
		       	$user_id = $this->_me->get_id();
			$this->assign("patient_id",$patient_id);
      			$this->assign("user_id",$user_id);
			$this->assign('back_link',Celini::link('edit').
						"patient_id=$patient_id&survey_id=$survey_id");
			
			if($patient_id = $encounter->get('patient_id')){
				//this is an encounter form
				$this->assign("date_of_treatment",$encounter->get('date_of_treatment'));
			}else{
				//this is a patient form
				$patient_id = $data->get('external_id');
				$this->assign("date_of_treatment",false);
			}
			

		}

		return $this->fetch(Celini::getTemplatePath("/form/" . $this->template_mod . "_fillout.html"));
		//Note that this system works BECAUSE we are referring the the "form" template!!!
	}

	
//	display form code goes here...
	
	}



	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param int
 	* 
 	* @return mixed fetch()
        */	
	function actionList_edit($patient_id = 0,$encounter_id = 0) {
		
// Display links to the forms for each of the surveys
	if(array_key_exists('patient_id',$_GET)){$patient_id = $_GET['patient_id'];}else{
$patient_id = 0;}
	if(array_key_exists('encounter_id',$_GET)){$patient_id = $_GET['patient_id'];}else{
$encounter_id = 0;}


	$user_id = $this->_me->get_id();

	
	$surveyds =& new Survey_List_DS(array('patient_id' => $patient_id,'encounter_id' => $encounter_id));
	$formDataGrid =& new cGrid($surveyds);
			$formDataGrid->name = "surveyDataGrid";
			$formDataGrid->registerTemplate('id',
				'<a href="'.Celini::link('edit','Survey').
				'patient_id={$patient_id}&survey_id={$id}">{$id}</a>'
				);
				$formDataGrid->registerTemplate('patient_email',
				'<a href="'.Celini::link('email','Survey').
				'patient_id={$patient_id}&survey_id={$id}">{$patient_email}</a>'
				);

			$formDataGrid->pageSize = 50;
		//	$formDataGrid->setExternalId($user_id);
	
	$this->assign_by_ref('formDataGrid',$formDataGrid);


	return $this->fetch(Celini::getTemplatePath("/survey/" . $this->template_mod . "_list.html"));
	
	}
	/**
	* pass in a patient_id always. pass in a survey_id when you want to load one from the db...
	*
	*long description
	*
 	* @param int
 	* @param int
 	* 
 	* @return mixed
        */
	function edit_action_edit($patient_id = 0, $survey_id = 0){
		

	$processing = false;

	if($patient_id == 0){// form was called from another page
		if(array_key_exists('patient_id',$_POST)){//we are now processing the form..
			$patient_id = $_POST['patient_id'];
			$processing = true;
		}else{	
			$this->messages->addmessage('No Patient Id. You can only add surveys to a particular patient');
			return;
		}
	}

	$this->assign("FORM_ACTION", Celini::link(true));// link back here for processing!!!

	$this->assign('patient_id',$patient_id);

	$form =& ORDataObject::factory('Form');
	$formlist = $form->simpleFormList();
	$this->assign_by_ref('form_list',$formlist);

	$p = ORDataObject::Factory("patient",$patient_id);
	$encounter_ds = $p->loadDatasource('EncounterList');
	$encounter_multi_array = $encounter_ds->toArray();
	$this->assign_by_ref('encounter_multi_array',$encounter_multi_array);

	if($survey_id != 0){ // then we are being asked to load this survey to further edit... 



			$survey =& ORDataObject::factory('Survey',$survey_id);
			$encounter =& ORDataObject::factory('Encounter',$survey->get('external_id'));

			$provider =& ORDataObject::factory('Person',$encounter->get('treating_person_id'));

			$this->assign('MANUAL_COMPLETE_ACTION',
					Celini::link('userfillout','Survey').
			"patient_id=$patient_id&survey_id=$survey_id");
			$this->assign('status',$survey->get('status'));
			$this->assign('patient_name',$p->get('last_name').", ".$p->get('first_name'));
			$this->assign('provider_name',$provider->get('last_name').", ".$provider->get('first_name'));
			$this->assign('treatment_date',$encounter->get('date_of_treatment'));
			$this->assign('survey_id',$survey_id);
			$this->assign('form_id',$survey->get('form_id'));
			$this->assign('encounter_id',$survey->get('external_id'));
			$this->assign('to_contact',$survey->get('to_contact'));
			$this->assign('next_contact_date',$survey->get('next_contact_date'));



	}else{


		if(array_key_exists('survey_id',$_POST) && $_POST['survey_id']!=0){//then we are updating a survey

				

				$survey =& ORDataObject::factory('Survey',$_POST['survey_id']);

				$this->assign('survey_id',$_POST['survey_id']);
				$this->assign('form_id',$_POST['form_id']);
				$this->assign('encounter_id',$_POST['encounter_id']);
				$this->assign('to_contact',$_POST['to_contact']);
				$this->assign('next_contact_date',$_POST['next_contact_date']);
				$survey_array['form_id']=$_POST['form_id'];
				$survey_array['patient_id']=$patient_id;
				$survey_array['external_id']=$_POST['encounter_id'];
				$date_obj = DateObject::create($_POST['next_contact_date']);
				$my_date_string = $date_obj->toISO();
				$survey_array['next_contact_date']=$my_date_string;
				$survey_array['to_contact']=$_POST['to_contact'];
				// we do not change the number of times contacted in this process...
				$survey->populate_array($survey_array);
				$survey->persist();


				$this->messages->addmessage('Survey Updated...');
		}else{
			if(array_key_exists('form_id',$_POST)){//then the survey was filled out!! lets redisplay the values

				$this->assign('form_id',$_POST['form_id']);
				$this->assign('encounter_id',$_POST['encounter_id']);
				$this->assign('to_contact',$_POST['to_contact']);
				$this->assign('next_contact_date',$_POST['next_contact_date']);
	
				$survey_array['form_id']=$_POST['form_id'];
				$survey_array['patient_id']=$patient_id;
				$survey_array['external_id']=$_POST['encounter_id'];
			
				$date_obj = DateObject::create($_POST['next_contact_date']);
				$my_date_string = $date_obj->toISO();
				$survey_array['next_contact_date']=$my_date_string;
				// FRED: I am not sure why this needs to be here. Why is the Storage class not doing this??

				$survey_array['contacted']=0;// since this a new contact!!
				$survey_array['to_contact']=$_POST['to_contact'];

				$survey =& ORDataObject::factory('Survey');
				//var_export($survey_array);
				$survey->populate_array($survey_array);
				$survey->persist();
				$this->assign('survey_id',$survey->get('survey_id'));
				$this->messages->addmessage('New Survey Saved...');


			}
		}
	}

	
	return $this->fetch(Celini::getTemplatePath("/survey/" . $this->template_mod . "_edit.html"));
	
	}

	/**
	 *Short Description
	 *
	 *Long Description
 	 *
 	 * @param int
 	 * @param int
 	 * 
 	 * @return string
         */
	function actionEmail_add($patient_id = 0, $survey_id = 0) {

//Then add a similar email system to C_MyOutcomes.class.php to give access to the outcomes form
//After an outcome is filled out the YDP Summary should be sent. 
//Then make it send emails based on a cron job...
//Add in patient fact based authentication??

 	if($patient_id == 0 || $survey_id == 0){
		$this->messages->addmessage('You must have both a patient_id and a survey_id to send a survey...');

		return;
	} 


	$survey =& ORDataObject::Factory('Survey',$survey_id);
	if(!$survey->shouldSend()){
		return "This Survey should not be sent";
	}

//	ini_set("include_path", CELLINI_ROOT."/includes/phpmailer");
//	require("class.phpmailer.php");
	
	


	$mail = new PHPMailer();
	$mail->From     = "YDP@yourdoctorprogram.com";
	$mail->FromName = "Your Doctor Program";
	$mail->WordWrap = 50;                              // set word wrap
	$mail->IsHTML(true);                               // send as HTML
	$mail->Subject  =  "YDP Survey Request";

	$patient =& ORDataObject::Factory('Patient',$patient_id);
	$this->assign_by_ref('patient',$patient);
	$mail->AddAddress($patient->get('email'),$patient->get('first_name')." ".$patient->get('last_name')); 
	
/*
	$tokenobject =& ORDataObject::Factory('Token',null);
	$token = $tokenobject->new_random($patient_id);
	$tokenobject->persist();
*/
	//FRED configure!!

	$server = $_SERVER['SERVER_NAME'];	

	$path_parts = split('/',$_SERVER['REQUEST_URI']);
	$instance_name = $path_parts[1];

	$fred_url = "http://localhost:81/kim_dunn/";
	$main_url = "https://$server/ydppatient/";
        $url_end = "index.php/menu/Survey/fillout?patient_id=$patient_id&survey_id=$survey_id";
	$link = $main_url.$url_end;
	$fred_link = $fred_url.$url_end;	

	$this->assign('patient_id',$patient_id);
	$this->assign('token','notoken');
	$this->assign('link',$link);
	$this->assign('fred_link',$fred_link);


	$mail->Body     =  $this->fetch(Celini::getTemplatePath("/survey/survey_email_html.html"));
	$mail->AltBody  =  $this->fetch(Celini::getTemplatePath("/survey/survey_email_txt.html"));

	if(!$mail->Send())
	{
   		echo "Message was not sent <p>";
   		echo "Mailer Error: " . $mail->ErrorInfo;
   		exit;
	}
		$status_array = array_flip($survey->getSurveyStatusList());
		$sent_status = $status_array['sent'];
		$survey->set('status',$sent_status);
		$contacted = $survey->get('contacted');
		$contacted++;
		$survey->set('contacted',$contacted);
		$survey->persist();

		return "Message has been sent";
	

	}


	

	/**
	 * MyRecord does not require login.. .instead it requires a valid token...
	 *
	 * so this always returns false. Allowing anonymous access.
	 * 
	 * @param	string	$controller
	 * @param	string	$action
	 * @return	boolean	true - the user needs to be logged into view this action, false the user doesn't need to be logged in
	 */
	function requireLogin($controller, $action) {

		//So either you should have a valid login OR you should have a valid token.
                if(array_key_exists('token',$_GET)){
                        $tokenstring = $_GET['token'];
                }else{

                        if(array_key_exists('token',$_POST)){
                                $tokenstring = $_POST['token'];
                        }else{//we have not patient id so we need a login
                                return true;
                        }

                }

                if(array_key_exists('patient_id',$_GET)){
                        $patient_id = $_GET['patient_id'];
                }else{

                        if(array_key_exists('patient_id',$_POST)){
                                $patient_id = $_POST['patient_id'];
                        }else{//we have not patient id so we need a login
                                return true;
                        }
                }

		$sha1_token = sha1($tokenstring);
	
		$this->assign('token',$tokenstring);
		$this->assign('sha1_token',$sha1_token);
		$this->assign('patient_id',$patient_id);

		$tokenobject =& ORDataObject::Factory('Token',null);
		$token_valid = $tokenobject->is_valid($patient_id,$sha1_token);

		if($token_valid){//then we have an OK token so we do not need a login.
			return false;
		}else{//we have not token so we need a login
			return true;
		}
	}



}

?>
