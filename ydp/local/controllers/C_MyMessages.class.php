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
 * @see "ordo/ORDataObject.class.php"
 */
$loader->requireOnce("ordo/ORDataObject.class.php");
/**
 * @see "includes/Grid.class.php"
 */
$loader->requireOnce("includes/Grid.class.php");
/**
 * @see "datasources/Message_MessageDataList_DS.class.php"
 */
$loader->requireOnce("datasources/Message_MessageDataList_DS.class.php");
/**
 * @see "datasources/Message_InboxDataList_DS.class.php"
 */
$loader->requireOnce("datasources/Message_InboxDataList_DS.class.php");
/**
 * @see "datasources/Message_SentDataList_DS.class.php"
 */
$loader->requireOnce("datasources/Message_SentDataList_DS.class.php");
/**
 * Controller to hold stuff that a patient can see on thier record
 *
 * Long Description
 * Long Description  
 *
 * @package	ydp
 */
class C_MyMessages extends Controller {

        /**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string
 	* @param int
	* 
 	* @return void 
        */
	function _patient_to_list($patient_id,$thread_id = 0){

		// patients should be able to email several groups of people
		// first they should always be able to get to thier provider.
		// second they should always be able to get to anyone who is responsible for a plan
		// they should also be able to get to anyone who is talking on a current thread.

		if($thread_id != 0){
			$thread =& ORDataObject::factory('Threads',$thread_id);
			$to_list = $thread->getPersonList();

		}else{
			$to_list = array();
		}

		$patient =& ORDataObject::factory('Patient',$patient_id);
		$provider_id = $patient->get('default_provider');
		//echo "provider_id = $provider_id<br>";

		
		$user =& ORDataObject::factory('User');
		$user =& $user->fromId($provider_id);
		$person_id = $user->get_person_id();
		//echo "person_id = $person_id<br>";
		$person =& ORDataObject::factory('Person',$person_id);
		$to_list[$person_id] = "My_Doctor: ".$person->get('last_name').", ".$person->get('first_name');
		$plan =& ORDataObject::factory('Plans');		
		$plan_to_list = $plan->getPersonList($patient_id);
		if(is_array($plan_to_list)){
			$to_list = $to_list + $plan_to_list;
		}

		unset($to_list[$patient_id]);// patients should not be able to send messages to self.

		return($to_list);	

	}

        /**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string
	* 
 	* @return mixed fetch()
        */
        function actionThread_view($thread_id) {

		
		$thread_id = $_GET['thread_id'];
	
		$me =& Me::getInstance();
		$user =& $me->get_user();
		$patient_id = $user->get('person_id');
		if(!$patient_id){
			echo "Error: we need a patient_id to function. This error should have been caught earlier";
			exit(0);
		}

		$this->assign('thread_id',$thread_id);

		$to_list = $this->_patient_to_list($patient_id,$thread_id);
		$this->assign_by_ref('to_list',$to_list);	

		$SEND_ACTION = Celini::link('new','MyMessages');
		$this->assign_by_ref('SEND_ACTION',$SEND_ACTION);		
		$thread =& ORDataObject::factory('Threads');
		$message_array = $thread->getMessagesArray($thread_id,$patient_id);
/*
		foreach($message_array as $key => $message){
			$summary_text = eregi_replace("&nbsp;", " ", strip_tags($message_array[$key]['content']));
			$summary_text = substr($summary_text,0,80);
			$message_array[$key]['summary_text'] =  $summary_text;
		}*/
		foreach($message_array as $key => $message){
		//	var_export($message); echo "<br>";
			$summary_text = eregi_replace("&nbsp;", " ", strip_tags($message_array[$key]['content']));
			$summary_text = substr($summary_text,0,80);
			$message_array[$key]['summary_text'] =  $summary_text;
			if($message_array[$key]['seen'] != 1){
				$message_array[$key]['show'] = true;
			}else{
				$message_array[$key]['show'] = false;
			} 
		}
		$message_array[$key]['show'] = true;// always show the last message, read or not!!
		$this->assign_by_ref('message_array',$message_array);
		$subject = $message_array[$key]['subject'];
		$this->assign('subject',$subject);	
		$thread->markThreadSeen($thread_id,$patient_id);		
        	return $this->fetch(Celini::getTemplatePath("/mymessages/" . $this->template_mod . "_thread.html"));
        }

	/**
	* displays patient messages
	*
	* long comment for the class method below 
	*
 	* @return mixed fetch()
        */
	function actionSent_view() {

		$me =& Me::getInstance();
		$user =& $me->get_user();
		$patient_id = $user->get('person_id');
		if(!$patient_id){
			echo "Error: we need a patient_id to function. This error should have been caught earlier";
			exit(0);
		}

		$message_ds =& new Message_SentDataList_DS($patient_id);
		$messageGrid =& new cGrid($message_ds);
		$messageGrid->name = "inboxDataGrid";
		$messageGrid->pageSize = 30;
		$thread_link_template = Celini::link('thread','MyMessages').'thread_id={$thread_id}';
		$subject_template = "<a href='$thread_link_template'".'>{$subject}</a>';
		$subject_template .= ' ({$message_number})';
		$messageGrid->registerTemplate('subject',$subject_template);
		$messageGrid->registerTemplate('to_person','{$to_last},{$to_first}');
		$messageGrid->registerTemplate('from_person','{$from_last},{$from_first}');
		$this->assign_by_ref('inboxDataGrid',$messageGrid);
	
		return $this->fetch(Celini::getTemplatePath("/mymessages/" . $this->template_mod . "_sent.html"));

	}


	/**
	* displays patient messages
	*
	* long comment for the class method below 
	*
 	* @return mixed fetch()
        */
	function actionInbox_view() {

		$me =& Me::getInstance();
		$user =& $me->get_user();
		$patient_id = $user->get('person_id');
		if(!$patient_id){
			echo "Error: we need a patient_id to function. This error should have been caught earlier";
			exit(0);
		}
/*
		$message_ds =& new Message_InboxDataList_DS($patient_id);
		$messageGrid =& new cGrid($message_ds);
		$messageGrid->name = "inboxDataGrid";
		$messageGrid->pageSize = 30;
		$thread_link_template = .'thread_id={$thread_id}';
		$subject_template = "<a href='$thread_link_template'".'>{$subject}</a>';
		$subject_template .= ' ({$message_number})';
		$messageGrid->registerTemplate('subject',$subject_template);
		$messageGrid->registerTemplate('to_person','{$to_last},{$to_first}');
		$messageGrid->registerTemplate('from_person','{$from_last},{$from_first}');
		$this->assign_by_ref('inboxDataGrid',$messageGrid);*/

		$message_ds =& new Message_InboxDataList_DS($patient_id);
		$messageGrid =& new cGrid($message_ds);
		$messageGrid->name = "inboxDataGrid";
		$messageGrid->pageSize = 30;
		$thread_link_template = Celini::link('thread','MyMessages').'thread_id={$thread_id}';
		$subject_template = '{$bold}';
		$subject_template .= "<a href='$thread_link_template'".'>{$subject}</a>';
		$subject_template .= ' ({$message_number})';
		$subject_template .= '{$unbold}';
		
		$messageGrid->registerTemplate('id',$subject_template);
		$messageGrid->registerTemplate('source','{$bold}{$source}{$unbold}');
		$this->assign_by_ref('inboxDataGrid',$messageGrid);
	
		return $this->fetch(Celini::getTemplatePath("/mymessages/" . $this->template_mod . "_inbox.html"));

	}




	/**
	* short comment
	*
	* long comment for the class method below 
	*
 	* @return mixed fetch()
        */
	function actionNew_view() {
        

		$me =& Me::getInstance();
		$user =& $me->get_user();
		$patient_id = $user->get('person_id');
		if(!$patient_id){
			echo "Error: we need a patient_id to function. This error should have been caught earlier";
			exit(0);
		}

		
		$patient =& ORDataObject::factory('Patient',$patient_id);
		$this_person_id = $patient->get('person_id');
		$to_list = $this->_patient_to_list($patient_id);
		$this->assign_by_ref('to_list',$to_list);

		if(array_key_exists('process',$_POST)){

		$to = $_POST['to'];
		$from = $this_person_id;
		//echo $this_person_id;
		$content = $_POST['message']; 
		$message =& ORDataObject::Factory('MMMessages');

	//	var_export($_POST);
		//if we have a subject, its a new thread... 
		//if we have a thread_id its a new message on an old thread..
		if(array_key_exists('subject',$_POST)){
			$subject = $_POST['subject'];
			$message->send_new_thread($to,$from,$subject,$content);
			$this->messages->addMessage('New Message Sent');
			return $this->actionInbox_view();
		}

		if(array_key_exists('thread_id',$_POST)){
			$thread_id = $_POST['thread_id'];
			$message->send_old_thread($to,$from,$thread_id,$content);
			$this->messages->addMessage('Message Sent');
			return $this->actionInbox_view();
		}		
		}

		$this->assign('FORM_ACTION',Celini::link('new','MyMessages'));	

        	return $this->fetch(Celini::getTemplatePath("/mymessages/" . $this->template_mod . "_send.html"));
        


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
        $url_end = "index.php/My/MyMessages/default?patient_id=$patient_id&token=$token";
	$link = $main_url.$url_end;
	$fred_link = $fred_url.$url_end;	

	$this->assign('patient_id',$patient_id);
	$this->assign('token',$token);
	$this->assign('link',$link);
	$this->assign('fred_link',$fred_link);


	$mail->Body     =  $this->fetch(Celini::getTemplatePath("/mymessages/record_email_html.html"));
	$mail->AltBody  =  $this->fetch(Celini::getTemplatePath("/mymessages/record_email_txt.html"));

	if(!$mail->Send())
	{
   		echo "Message was not sent <p>";
   		echo "Mailer Error: " . $mail->ErrorInfo;
   		exit;
	}

		return "Message has been sent";
	

	}







}
?>
