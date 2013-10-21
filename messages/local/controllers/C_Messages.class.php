<?php

/*
//require_once CELLINI_ROOT."/controllers/Controller.class.php";
require_once APP_ROOT."/local/datasources/Message_MessageDataList_DS.class.php";
require_once APP_ROOT."/local/datasources/Message_InboxDataList_DS.class.php";
require_once APP_ROOT."/local/datasources/Message_SentDataList_DS.class.php";
*/

//$loader->requireOnce('controllers/Controller.class.php');
/**
 * This is C_My.class.php
 *
 * This does .......
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package messages
 */
/**#@+
 *
 * Required Libs
 */
$loader->requireOnce('datasources/Message_InboxDataList_DS.class.php');
$loader->requireOnce('datasources/Message_AllboxDataList_DS.class.php');
$loader->requireOnce('datasources/Message_MessageDataList_DS.class.php');
$loader->requireOnce('datasources/Message_SentDataList_DS.class.php');
$loader->requireOnce('datasources/Message_AllSentDataList_DS.class.php');
$loader->requireOnce('datasources/Message_PatientMessageDataList_DS.class.php');
$loader->requireOnce('datasources/Message_PatientMergeDataList_DS.php');
/**#@-*/
/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package messages
*/
class C_Messages extends Controller {
	/**
	 * Short Description
	 *
	 * Long Description  
	 *
         * @var string
         */
	var $patient_controller = 'PatientDashboard';
	/**
	 * Short Description
	 *
	 * Long Description  
	 *
         * @var string
         */
	var $patient_action = 'view';

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	*
 	* @return void 
        */
	function C_Messages ($template_mod = "general") {
		parent::Controller();
		$this->template_mod = $template_mod;

		if(isset($GLOBALS['config']['messages']['patient_summary_controller']))
		{
			$this->patient_controller = $GLOBALS['config']['messages']['patient_summary_controller'];
			$this->patient_action = $GLOBALS['config']['messages']['patient_summary_action'];
		}
		


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
	function actionDateMerge_view($patient_id) {


//		echo "patient_id = $patient_id";
$sql = '

CREATE TEMPORARY TABLE temptable 
SELECT 
       	patient_note_id as id, 
       	"note" AS source, 
       	`note` AS content, 
	`note_date` AS date
FROM patient_note;';

$sql2 ='

INSERT INTO temptable( id, source, content, date )
SELECT 
	message_id as id,
	"message" AS source, 
	content AS content, 
	created AS date
FROM mmmessages;
';

$sql3 = '
SELECT *
FROM temptable
ORDER BY DATE DESC;
';

//echo "<br> $sql $sql2 $sql3";
//		$db =& new clniDB();
//		$db->execute($sql);
//		$db->execute($sql2);
//		$array = $db->getAssoc($sql3);
//echo "<br><br><br>";
//		var_export($array);


                $mergeDS = new Message_PatientMergeDataList_DS($patient_id);
                $mergeGrid = new cGrid($mergeDS);
                $mergeGrid->name = "patientMergeGrid";
                $mergeGrid->pageSize = 40;
                $mergeGrid->indexCol = false;
                $mergeGrid->setExternalId($patient_id);
                $this->assign_by_ref('mergeGrid',$mergeGrid);

		return $this->view->render("merge.html");



	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return mixed 
        */
	function actionMarkDone_edit() {

		$done = $_GET['done']; 
		$message_id = $_GET['message_id'];
		$message =& ORDataObject::Factory('MMMessages',$message_id);
		if($done > 0){
			$message->mark_done();
		}else{
			$message->mark_notdone();
		}

		return($this->actionInbox_view());

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
	function actionPatient_view($patient_id){

		$p =& Celini::newORDO('Patient', $patient_id);
	
                $messageDS = new Message_PatientMessageDataList_DS($p->get('person_id'));
                $messageGrid = new cGrid($messageDS);
                $messageGrid->name = "patientMessageGrid";
                $messageGrid->pageSize = 5;
                $messageGrid->indexCol = false;
                $messageGrid->setExternalId($p->get('id'));
                $this->assign_by_ref('patientMessageGrid',$messageGrid);

		$this->set('patient_id',$patient_id,'c_patient');
		return $this->view->render("patient.html");


	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param int
 	*
 	* @return array 
        */
	function _user_to_list($person_id,$thread_id = 0){

		// patients should be able to email several groups of people
		// first they should always be able to get to thier provider.
		// second they should always be able to get to anyone who is responsible for a plan
		// they should also be able to get to anyone who is talking on a current thread.
		//$patient =& ORDataObject::factory('Patient',$patient);
		$person =& ORDataObject::factory('Person');
		$to_list = $person->getPersonList(0);
		//unset($to_list[$person_id]);// you cannot send clinical messages to yourself

		//var_export($to_list);

		// For now however they can simply email any user...
		if($thread_id != 0){
			$thread =& ORDataObject::factory('Threads',$thread_id);
			$to_list =  $thread->getPersonList() + $to_list;
		}
		// this moves my own user to the very end of the select box...
		$me = $to_list[$person_id];
		unset($to_list[$person_id]);
		$to_list = $to_list + array($person_id => $me);
		return($to_list);

		


	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return mixed
        */
	function actionDefault_view() {
		
		return $this->actionInbox_view();
	
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return string
        */
	function actionRick_view(){

		$this->assign('VAREXPORT_ACTION',Celini::link('varexport','Messages',true));	

               	return $this->fetch(Celini::getTemplatePath("/messages/" . $this->template_mod . "_rick.html"));

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return string
        */
	function actionUserList_view(){
		//Called by AutoSuggest.


           //     if(array_key_exists('message',$_POST)){
           //             $content = $_POST['message'];
            //    }
                if(array_key_exists('search',$_POST)){
                        $search = $_POST['search'];
                }else{
			$search = '';
		}
		$this_user = $this->_me->get_user();		
		$this_person_id = $this_user->get('person_id');
		$users = $this->_user_to_list($this_person_id);	
	
		$match_users = array();
		
		foreach($users as $id => $name){
		if(strpos(strtolower($name),strtolower($search)) !== false)
			$match_users[$id] = $name;
			
		}

		$html = "<ul>";
		if(count($match_users) > 0){
			foreach($match_users as $id => $name){
			$html .= "<li id='$id'> $name </li>";
			}
		}else{ // no users found
			$html .= "<li id='0'><font color='red'> No Users Matching </font>'$search'<font color='red'> found</font> </li>"; 
		}
		$html .= "</ul>";
		return $html;



	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return string
        */
	function actionVarexport_view(){

		var_export($_POST);
		return "";
	}	

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return string
        */
        function actionInbox_view() {

		$this_user = $this->_me->get_user();		
		$this_person_id = $this_user->get('person_id');
	//	echo "person_id = $this_person_id";
		if($this_person_id == 0){$this_person_id = 1;} // for the admin user.		

		$message_ds =& new Message_InboxDataList_DS($this_person_id);
		$messageGrid =& new cGrid($message_ds);
		$messageGrid->name = "inboxDataGrid";
		$messageGrid->pageSize = 30;
		$thread_link_template = Celini::link('thread','Messages').'thread_id={$thread_id}';
		$subject_template = '{$bold}';
		$subject_template .= "<a href='$thread_link_template'".'>{$subject}</a>';
		$subject_template .= ' ({$message_number})';
		$subject_template .= '{$unbold}';
		
		$messageGrid->registerTemplate('id',$subject_template);
		$messageGrid->registerTemplate('source','{$bold}{$source}{$unbold}');
	//	$messageGrid->registerTemplate('when_sent','{pretty_date string=$when_sent}');

		$inbox_view = Celini::link('inbox','Messages').'view=open';
		$this->assign('OPENINBOX_LINK',$inbox_view);

		$inbox_all = Celini::link('inbox','Messages').'view=all';
		$this->assign('ALLINBOX_LINK',$inbox_all);


		$patient_link = Celini::link($this->patient_action,$this->patient_controller).'id={$patient_id}';
		$messageGrid->registerTemplate('patient_id','{$bold}'."<a href='$patient_link'>".'{$patient_last}, {$patient_first}</a>{$unbold}');
		$this->assign_by_ref('inboxDataGrid',$messageGrid);
        	
		$message_ds =& new Message_SentDataList_DS($this_person_id);
		$messageGrid =& new cGrid($message_ds);
		$messageGrid->name = "sentDataGrid";
		$messageGrid->pageSize = 30;
		$thread_link_template = Celini::link('thread','Messages').'thread_id={$thread_id}';
		$subject_template = '';
		$subject_template .= "<a href='$thread_link_template'".'>{$subject}</a>';
		$subject_template .= ' ({$message_number})';
		$subject_template .= '';

		$patient_link = Celini::link($this->patient_action,$this->patient_controller).'id={$patient_id}';
		$messageGrid->registerTemplate('patient_id','{$bold}'."<a href='$patient_link'>".'{$patient_last}, {$patient_first}</a>{$unbold}');

		
		$messageGrid->registerTemplate('id',$subject_template);
		$messageGrid->registerTemplate('source','{$bold}{$source}{$unbold}');
		$this->assign_by_ref('sentDataGrid',$messageGrid);






		return $this->fetch(Celini::getTemplatePath("/messages/" . $this->template_mod . "_inbox.html"));

 }




	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return string
        */
        function actionAllbox_view() {

		$this_user = $this->_me->get_user();		
		$this_person_id = $this_user->get('person_id');
	//	echo "person_id = $this_person_id";
		if($this_person_id == 0){$this_person_id = 1;} // for the admin user.		

		$message_ds =& new Message_AllboxDataList_DS($this_person_id);
		$messageGrid =& new cGrid($message_ds);
		$messageGrid->name = "inboxDataGrid";
		$messageGrid->pageSize = 30;
		$thread_link_template = Celini::link('thread','Messages').'thread_id={$thread_id}';
		$subject_template = '{$bold}';
		$subject_template .= "<a href='$thread_link_template'".'>{$subject}</a>';
		$subject_template .= ' ({$message_number})';
		$subject_template .= '{$unbold}';
		
		$messageGrid->registerTemplate('id',$subject_template);
		$messageGrid->registerTemplate('source','{$bold}{$source}{$unbold}');
	//	$messageGrid->registerTemplate('when_sent','{pretty_date string=$when_sent}');

		$inbox_view = Celini::link('inbox','Messages').'view=open';
		$this->assign('OPENINBOX_LINK',$inbox_view);

		$inbox_all = Celini::link('inbox','Messages').'view=all';
		$this->assign('ALLINBOX_LINK',$inbox_all);
		
		$patient_link = Celini::link($this->patient_action,$this->patient_controller).'id={$patient_id}';
		$messageGrid->registerTemplate('patient_id','{$bold}'."<a href='$patient_link'>".'{$patient_last}, {$patient_first}</a>{$unbold}');
		$this->assign_by_ref('allboxDataGrid',$messageGrid);
        	
		return $this->fetch(Celini::getTemplatePath("/messages/" . $this->template_mod . "_allbox.html"));

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
        function actionThread_view($thread_id) {
		
		$this->assign('thread_id',$thread_id);

		$this_user = $this->_me->get_user();		
		$this_person_id = $this_user->get('person_id');
		$to_list = $this->_user_to_list($this_person_id,$thread_id);
		$this->assign_by_ref('to_list',$to_list);	



		$SEND_ACTION = Celini::link('new','Messages');
		$this->assign('SEND_ACTION',$SEND_ACTION);		
		$thread =& ORDataObject::factory('Threads',$thread_id);

		$current_people = $thread->getPersonList();
		//var_export($current_people);
		$this->assign_by_ref('current_people',$current_people);

		$message_array = $thread->getMessagesArray($thread_id,$this_person_id);
		//var_export($message_array);
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
		$priority = $message_array[$key]['priority'];
		$this->assign('priority',$priority);
		$thread->markThreadSeen($thread_id,$this_person_id);

		$patient_id = $thread->get('patient_id');
		$this->set('patient_id',$patient_id,'c_patient');
		
        	return $this->fetch(Celini::getTemplatePath("/messages/" . $this->template_mod . "_thread.html"));
        }


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return string
        */
        function actionSent_view() {

		$this_user = $this->_me->get_user();		
		$this_person_id = $this_user->get('person_id');
	//	echo "person_id = $this_person_id";
		if($this_person_id == 0){$this_person_id = 1;}		

		$message_ds =& new Message_AllSentDataList_DS($this_person_id);
		$messageGrid =& new cGrid($message_ds);
		$messageGrid->name = "sentDataGrid";
		$messageGrid->pageSize = 30;
		$thread_link_template = Celini::link('thread','Messages').'thread_id={$thread_id}';
		$subject_template = '';
		$subject_template .= "<a href='$thread_link_template'".'>{$subject}</a>';
		$subject_template .= ' ({$message_number})';
		$subject_template .= '';

		$patient_link = Celini::link($this->patient_action,$this->patient_controller).'id={$patient_id}';
		$messageGrid->registerTemplate('patient_id','{$bold}'."<a href='$patient_link'>".'{$patient_last}, {$patient_first}</a>{$unbold}');

		
		$messageGrid->registerTemplate('id',$subject_template);
		$messageGrid->registerTemplate('source','{$bold}{$source}{$unbold}');
		$this->assign_by_ref('sentDataGrid',$messageGrid);




        	return $this->fetch(Celini::getTemplatePath("/messages/" . $this->template_mod . "_sent.html"));
        }



	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return mixed
        */
        function actionSearch_view() {
		return $this->actionInbox_view();
        }

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return string
        */
        function actionNew_add() {

	
		if($GLOBALS['config']['messages']['email']['all_messages_are_tasks']){
			$display_task = false;
		}else{
			$display_task = true;
		}

		$this->assign('display_task',$display_task);

                if($GLOBALS['config']['messages']['email']['no_priority']){
                        $display_priority = false;
                }else{
                        $display_priority = true;
                }

                $this->assign('display_priority',$display_priority);




		$this_user = $this->_me->get_user();		
		$this_person_id = $this_user->get('person_id');


		$to_list = $this->_user_to_list($this_person_id);
		if(array_key_exists('patient_id',$_GET)){
			
			$patient_id = $_GET['patient_id'];
			$patient =& ORDataObject::factory('Patient',$patient_id);
			$patient_person_id = $patient->get('person_id');
			$pat_first = $patient->get('first_name');
			$pat_last = $patient->get('last_name');
			//echo "$patient_person_id $pat_last $pat_first";
			if($GLOBALS['config']['messages']['email']['send_to_patient']){
				$to_list = array($patient_person_id => "Patient: $pat_last, $pat_first") + $to_list;
			}
			$this->assign('regarding',"Patient: $pat_last, $pat_first");
			$this->assign('FORM_ACTION',Celini::link('new','Messages')."patient_id=$patient_id");
		}else{
			$patient_id = 0;
		}
							//this removes the senders id from the possible recipients list
		$this->assign_by_ref('to_list',$to_list);

		if(array_key_exists('process',$_POST)){
		if(array_key_exists('to',$_POST)){
			$to = $_POST['to'];
		}else{//if I do not choose someone, send to myself
			$this_user = $this->_me->get_user();
                	$to = $this_user->get('person_id');

		}
			
		$from = $this_person_id;
		if(array_key_exists('message',$_POST)){
			$content = $_POST['message']; 
		}
		if(array_key_exists('is_todo',$_POST)){
			$is_todo = $_POST['is_todo'];
		}
		if(array_key_exists('priority',$_POST)){
			$priority = $_POST['priority'];
		}else{
			$priority = -1;
		}


		$message =& ORDataObject::Factory('MMMessages');

	//	var_export($_POST);
		//if we have a subject, its a new thread... 
		//if we have a thread_id its a new message on an old thread..
		if(array_key_exists('subject',$_POST)){
			$subject = $_POST['subject'];
			$thread_id = $message->send_new_thread($to,$from,$subject,$content,$patient_id,$priority,$is_todo);
			$this->messages->addMessage('New Message Sent');
				
			if(array_key_exists('patient_id',$_GET)){ 
				$patient_id = $_GET['patient_id'];
				$patient =& ORDataObject::factory('Patient',$patient_id);
				$patient_person_id = $patient->get('person_id');
				if($patient_person_id != $to){// this is a message to a user ABOUT a patient
					//Now we include the patient in the thread!!	
					$message =& ORDataObject::Factory('MMMessages');
					if($GLOBALS['config']['messages']['email']['send_to_patient']){
						
						$new_content = "SYSTEM MESSAGE: A clinical user has sent a message to another clinical user regarding your healthcare. You have been automatically included in this thread.";//SHOULD BE IN CONFIG
						
						$message->send_old_thread($patient_person_id,$from,$thread_id,$new_content,$priority);				
					}
					$this->messages->addMessage('This thread has been added to the patient record!');
				}
			}

			header('Refresh: 3; url=' . Celini::link('Inbox','Messages'));
			return "";


		}

		if(array_key_exists('thread_id',$_POST)){
			$thread_id = $_POST['thread_id'];
			$message->send_old_thread($to,$from,$thread_id,$content,$priority);
			if(isset($_POST['send_and_mark_done'])){
				$message->mark_done();
			}

			$this->messages->addMessage('Message Sent');
			
			header('Refresh: 3; url=' . Celini::link('Inbox','Messages'));
			return "";

		}		
		}
			

        	return $this->fetch(Celini::getTemplatePath("/messages/" . $this->template_mod . "_send.html"));
        }


}

?>
