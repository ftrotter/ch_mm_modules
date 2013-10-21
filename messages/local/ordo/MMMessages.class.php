<?php
/**
 * Object Relational Persistence Mapping Class for table: messages
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package messages
 */

/**#@+
 * Required Libs
 
//require_once CELLINI_ROOT.'/ordo/ORDataObject.class.php';
*/

/**#@-*/
//$loader->requireOnce('/ordo/ORDataObject.class.php');
/**
 * Object Relational Persistence Mapping Class for table: messages
 *
 * @package	messages
 */
class MMMessages extends ORDataObject {

	/**#@+
	 * Fields of table: messages mapped to class members
	 */
	var $id		= '';
	var $thread_id		= '';
	var $created		= '';
//	var $is_todo		= '';//ALTER TABLE `mmmessages` DROP `is_todo` ,
//	var $is_done		= '';//DROP `is_done` ;
	var $priority		= ''; // 1 is highest 5 is lowest
	var $content		= '';
	/**#@-*/


	/**
	 * Setup some basic attributes
	 * Shouldn't be called directly by the user, user the factory method on ORDataObject
	*
 	* @param boolean 
 	* 
 	* @return void 
        */
	function MMMessages($db = null) {
		parent::ORDataObject($db);	
		$this->_table = 'mmmessages';
		$this->_sequence_name = 'sequences';	
	}

	/**
	 * Called by factory with passed in parameters, you can specify the primary_key of Messages with this
	*
 	* @param int
 	* 
 	* @return void 
        */
	function setup($id = 0) {
		if ($id > 0) {
			$this->set('id',$id);
			$this->populate();
		}
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string 
 	* @param string 
 	* @param string 
 	* @param int 
 	* @param int
 	* @param int
 	* 
 	* @return int 
        */
	function send_new_thread($to,$from,$subject,$message_text,$patient_id = 0,$priority = 0,$todo = 0){

		$sent = date("Y-m-d H:i:s");


		$new_thread =& ORDataObject::Factory('Threads');	
		$new_thread->set('thread_name',$subject);
		$new_thread->set('patient_id',$patient_id);
		$new_thread->set('is_done',0);
		$new_thread->set('is_todo',$todo);
		$new_thread->persist();
		$thread_id = $new_thread->get('id');
		$this->set('thread_id',$thread_id);
		$this->set('priority',$priority);
		$this->set('content',$message_text);
		$this->set('created',$sent);

		$this->persist();
		
		$message_id = $this->get('id');
	//	echo "Marking read from: message $message_id person $from marked read<br>";
		$this->_markSeen($message_id,$from);
	//	echo "Marking unread to: message $message_id person $to marked unread<br>";
		$priority = $this->get('priority');

		if(!is_array($to)){	
			$this->_markUnSeen($message_id,$to);
			$new_env =& ORDataObject::Factory('Envelopes');
			$new_env->set('to_person',$to);
			$new_env->set('from_person',$from);
			$new_env->set('message_id',$message_id);
			$new_env->set('when_sent',$sent);
			$new_env->persist();
			if(isset($GLOBALS['config']['messages']['email']['priority_threshold'])){
				$priority_threshold = $GLOBALS['config']['messages']['email']['priority_threshold'];
			}else{
				$priority_threshold = 1;
			}


			if($priority >= $priority_threshold){
				$this->email_notice($new_env);
			}
	
		}else{

			foreach($to as $to_person){
				$this->_markUnSeen($message_id,$to_person);
				$new_env =& ORDataObject::Factory('Envelopes');
				$new_env->set('to_person',$to_person);
				$new_env->set('from_person',$from);
				$new_env->set('message_id',$message_id);
				$new_env->set('when_sent',$sent);
				$new_env->persist();
	                        if(isset($GLOBALS['config']['messages']['email']['priority_threshold'])){
       	        	                 $priority_threshold = $GLOBALS['config']['messages']['email']['priority_threshold'];
       		                }else{
                        	        $priority_threshold = 1;
                        	}

	
        	                if($priority >= $priority_threshold){
                	                $this->email_notice($new_env);
                        	}


			}

		}

		


		return($thread_id);
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return void 
        */
	function mark_done(){

		$thread =& ORDataObject::Factory('Threads',$this->get('thread_id'));
		$thread->set('is_done',1);
		$thread->persist();
		
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return void 
        */
	function mark_notdone(){

		$thread =& ORDataObject::Factory('Threads',$this->get('thread_id'));
		$thread->set('is_done',0);
		$thread->persist();

		
	}



	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string 
 	* @param string 
 	* @param string 
 	* @param string 
 	* @param int
 	* @param int
 	* 
 	* @return void 
        */
	function send_old_thread($to,$from,$thread_id,$message_text,$priority,$todo = 0,$is_done = 0){


		$sent = date("Y-m-d H:i:s");


		$this->set('thread_id',$thread_id);
		if($is_done > 0){
			$this->set('is_done',$is_done);
		}
		$this->set('content',$message_text);
		$this->set('priority',$priority);
		$this->set('created',$sent);
		$this->persist();
		$message_id = $this->get('id');
		//echo "Marking read from: message $message_id person $from marked read<br>";
		$this->_markSeen($message_id,$from);

		$thread =& ORDataObject::Factory('Threads',$thread_id);
		if($is_done > 0){
			$thread->set('is_done',$is_done);
			$thread->persist();
		}
		$thread_people = $thread->getPersonList();
		foreach($thread_people as $person_id => $something_useless_here){
			if($person_id != $from && $person_id != 0){// notifies thread listeners
				//echo "Thread people loop: message $message_id person $person_id marked unread<br>";
				$this->_markUnSeen($message_id,$person_id);
			}
		}	
		
		if(!is_array($to)){// then make it an array!!
			$to = array($to);
		}
		
		// you have to reply "to" the original
		$missing_tos = $thread->missingToPeople();
		$to = $to + $missing_tos;	


		foreach($to as $to_person){
			if($thread->isNewToThread($thread_id,$to_person)){
		//	echo "Marking whole Thread unread for to person $to_person<br>";
				$thread->markThreadUnSeen($thread_id,$to_person);
			}else{
		//	echo "Marking single unread to: message $message_id person $to_person marked unread<br>";
				$this->_markUnSeen($message_id,$to);
			}
			$new_env =& ORDataObject::Factory('Envelopes');	
			$new_env->set('to_person',$to_person);
			$new_env->set('from_person',$from);
			$new_env->set('message_id',$message_id);
			$new_env->set('when_sent',date("Y-m-d H:i:s"));
			$new_env->persist();
			$priority = $this->get('priority');
                        if(isset($GLOBALS['config']['messages']['email']['priority_threshold'])){
                                $priority_threshold = $GLOBALS['config']['messages']['email']['priority_threshold'];
                        }else{
                                $priority_threshold = 1;
                        }


                        if($priority >= $priority_threshold){
                                $this->email_notice($new_env);
                        }
		}//end of foreach
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
	function email_notice($envelope){

//	echo "MMMessages: email_notice called <br>";
	//ini_set("include_path", CELLINI_ROOT."/includes/phpmailer");
	//require_once("class.phpmailer.php");
	global $loader;
	$loader->requireOnce('/includes/phpmailer/class.phpmailer.php');

	$config = $GLOBALS['config'];

	if(isset($config['messages']['email'])){
		$from = $config['messages']['email']['from'];
		$from_name = $config['messages']['email']['from_name'];
		$subject = $config['messages']['email']['subject'];
		$main_url = $config['messages']['email']['main_url'];
		$send_to_patient = $config['messages']['email']['send_to_patient'];
		$patient_url = $config['messages']['email']['patient_url'];

	}else{
		
		$from     = "YDP@ydponline.com";
		$from_name = "Your Doctor Program";
		$subject  =  "YDP Secure Message";
        	$server = $_SERVER['SERVER_NAME'];

        	$path_parts = split('/',$_SERVER['REQUEST_URI']);
        	$instance_name = $path_parts[1];

       	 	$fred_url = "http://localhost/fred_test_dir/";
        	$main_url = "https://$server/$instance_name/";
		$fred_link = $fred_url.$url_end;	
		$patient_url = "index.php/my/MyAccess";
		$send_to_patient = true;
	}
	
	$to_person =& ORDataObject::Factory('Person',$envelope->get('to_person'));
	$person_type = $to_person->get_type();	


	$mail = new PHPMailer();
	$mail->From     = $from;
	$mail->FromName = $from_name;
	$mail->WordWrap = 50;                              // set word wrap
	$mail->IsHTML(true);                               // send as HTML
	$mail->Subject  =  $subject ." for ". $to_person->get('first_name');

	$mail->AddAddress($to_person->get('email'),$to_person->get('first_name')." ".$to_person->get('last_name')); 

	$cc = $GLOBALS['config']['messages']['email']['testing_email'];	
	if($cc){ //it is either an email address... or false...
		$mail->AddAddress($cc ,"CCed For " .$to_person->get('first_name')." ".$to_person->get('last_name')); 
	}
//	$tokenobject =& ORDataObject::Factory('Token',null);
//	$token = $tokenobject->new_random($patient_id);
//	$tokenobject->persist();


	if($person_type < 2){
		if($send_to_patient){
			$link = $patient_url;
		}else{
			//then we should not be sending anything!!
			return;
		}
	}else{
		$link = $main_url;
	}	
	//echo "sending link $link for person type $person_type<br>";

//	$this->assign('patient_id',$patient_id);
//	$this->assign('token',$token);
//	$this->assign('link',$link);
//	$this->assign('fred_link',$fred_link);

//echo "sending message to " . $to_person->get_email() . "\n";


	$mail->Body     = "You have a new message  <a href='$link'>here</a>"; 
	$mail->AltBody  = "You have a new message here -> $link"; 

	if(!$mail->Send())
	{
   		echo "Message was not sent <p>";
   		echo "Mailer Error: " . $mail->ErrorInfo;
   		echo "<br> Perhaps there is a problem with your mail setup. </p>";
   		return;
	}
		//set sent status here
		return;
	

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	* @param int
 	* 
 	* @return void 
        */
	function _markUnSeen($message_id,$person_id){
		$message_id = intval($message_id);
		$person_id = intval($person_id);
		
		$seen_sql = "INSERT INTO `seen` 
					( `person_id` , `message_id` , `seen` , `seen_when` )
				VALUES (
					'$person_id', '$message_id', '0', NOW( )
				);";

		$this->_execute($seen_sql);

	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	* @param int
 	* 
 	* @return void 
        */
	function _markSeen($message_id,$person_id){
		$message_id = intval($message_id);
		$person_id = intval($person_id);
		
		$seen_sql = "INSERT INTO `seen` 
					( `person_id` , `message_id` , `seen` , `seen_when` )
				VALUES (
					'$person_id', '$message_id', '1', NOW( )
				);";

		$this->_execute($seen_sql);

	}


	/**
	 * Populate the class from the db
 	* 
 	* @return void 
        */
	function populate() {
		parent::populate('message_id');
	}

	/**#@+
	 * Getters and Setters for Table: messages
	 */

	
	/**
	 * Getter for Primary Key: message_id
 	* 
 	* @return int 
        */
	function get_message_id() {
		return $this->id;
	}

	/**
	 * Setter for Primary Key: message_id
 	* 
 	* @return int 
        */
	function set_message_id($id)  {
		$this->id = $id;
	}

	/**#@-*/
}
?>
