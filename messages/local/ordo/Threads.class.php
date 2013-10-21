<?php
/**
 * Object Relational Persistence Mapping Class for table: threads
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package messages
 */

/**#@+
 * Required Libs
old way
//require_once CELLINI_ROOT.'/ordo/ORDataObject.class.php';
*/
//$loader->requireOnce('/ordo/ORDataObject.class.php');

/**#@-*/

/**
 * Object Relational Persistence Mapping Class for table: threads
 *
 * @package	messages
 */
class Threads extends ORDataObject {

	/**#@+
	 * Fields of table: threads mapped to class members
	 */
	var $id		= '';
	var $thread_name		= '';
	var $patient_id		= ''; //ALTER TABLE `threads` ADD `patient_id` INT( 10 ) NOT NULL ;
	var $is_todo		= ''; 
	var $is_done		= ''; //ALTER TABLE `threads` ADD `is_todo` TINYINT NOT NULL DEFAULT '0',
					//ADD `is_done` TINYINT NOT NULL DEFAULT '0';
	/**#@-*/


	/**
	 * Setup some basic attributes
	 * Shouldn't be called directly by the user, user the factory method on ORDataObject
	*
 	* @param boolean
 	* 
 	* @return string
        */
	function Threads($db = null) {
		parent::ORDataObject($db);	
		$this->_table = 'threads';
		$this->_sequence_name = 'sequences';	
	}

	/**
	 * Called by factory with passed in parameters, you can specify the primary_key of Threads with this
	*
 	* @param int
 	* 
 	* @return string
        */
	function setup($id = 0) {
		if ($id > 0) {
			$this->set('id',$id);
			$this->populate();
		}
	}

	/**
	 * Populate the class from the db
	* 
 	* @return void
        */
	function populate() {
		parent::populate('thread_id');
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
	function markThreadSeen($thread_id,$person_id){
	
		$debug_seen = false;

		$thread_id = intval($thread_id);
		$person_id = intval($person_id);

		$sql = "
SELECT 
 mmmessages.message_id,
 seen.seen,
 seen.seen_when
FROM `mmmessages`
LEFT JOIN threads ON mmmessages.thread_id = threads.thread_id
LEFT JOIN seen ON mmmessages.message_id = seen.message_id 
WHERE threads.thread_id = $thread_id 
AND (( seen.person_id = $person_id OR seen.person_id IS NULL)
AND seen.seen = 0)";

//echo "mark seen sql<br>$sql<br>";
		$res = $this->_execute($sql);


		while(!$res->EOF) {
		//	var_export($res->fields);
			$seen = $res->fields['seen'];
			$message_id = $res->fields['message_id'];
			if($seen != 1){// this has not been seen or the seen record does not exist.
			if($debug_seen){	echo "message $message_id not seen $seen <br> ";}

				if(strcmp($seen,'')==0){//$seen is NULL
				if($debug_seen){echo "no seen record '$seen' for message $message_id and person $person_id<br>";}
					$seen_sql = "INSERT INTO `seen` 
							( `person_id` , `message_id` , `seen` , `seen_when` )
						VALUES (
					'$person_id', '$message_id', '1', NOW( )
					);";
			
				}else{//$seen is 0, so the seen record exists, just not filled out...
				if($debug_seen){echo "seen record of $seen for message $message_id and person $person_id<br>";}

					$seen_sql = "UPDATE `seen` SET 
							`seen` = '1',
							`seen_when` = NOW( ) 
						WHERE `person_id` = $person_id 
							AND `message_id` = $message_id;";
				//	echo $seen_sql."<br>";
				}
			
				$this->_execute($seen_sql);

			}else{
				if($debug_seen){echo "message $message_id seen $seen<br>";}
			}			
			//$ret[$this_id] = $res->fields;
			//$ret[$this_id]['last_one'] = false;
			$res->MoveNext();
		}

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
	function markThreadUnSeen($thread_id,$person_id){
	
		$debug_seen = false;

		$thread_id = intval($thread_id);
		$person_id = intval($person_id);

		$sql = "
SELECT 
 mmmessages.message_id
FROM `mmmessages`
LEFT JOIN threads ON mmmessages.thread_id = threads.thread_id
WHERE threads.thread_id = $thread_id ";

//echo "mark unseen sql<br> $sql<br>";
		$res = $this->_execute($sql);


		while(!$res->EOF) {
		//	var_export($res->fields);
			$message_id = $res->fields['message_id'];
			if($debug_seen){	echo "message $message_id creating unseen from $person_id<br> ";}

			$seen_sql = "INSERT INTO `seen` 
					( `person_id` , `message_id` , `seen` , `seen_when` )
				VALUES (
					'$person_id', '$message_id', '0', NOW( ) );";
	
			$this->_execute($seen_sql);	
			$res->MoveNext();
		}

	}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	* @param int
 	* 
 	* @return boolean 
        */
	function isNewToThread($thread_id,$person_id){

	$sql = "
SELECT	
COUNT(*) as total_messages
FROM mmmessages
WHERE mmmessages.thread_id = $thread_id
		";
//echo "total messages sql <br>$sql<br>";
		$result = $this->_db->Execute($sql);
		$total_messages = $result->fields['total_messages'];

	$sql = "
SELECT	
COUNT(*) as messages_with_seen
FROM mmmessages
LEFT JOIN seen ON mmmessages.message_id = seen.message_id
WHERE mmmessages.thread_id = $thread_id
AND ( seen.person_id = $person_id OR seen.person_id IS NULL)
		";
//echo "messages_with_seen sql <br>$sql<br>";

		$result = $this->_db->Execute($sql);
		$messages_with_seen = $result->fields['messages_with_seen'];
		if($messages_with_seen == 0){
		//	echo "returning true with $messages_with_seen seen and $total_messages total<br>";
			return(true);
		}else{
		//	echo "returning false with $messages_with_seen seen and $total_messages total<br>";
			return(false);
		}
	}





	/**#@+
	 * Getters and Setters for Table: threads
	 */
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
	function getMessagesArray($thread_id,$person_id){
	//	echo $thread_id;
		$thread_id = intval($thread_id);
		$sql = "
						SELECT 
						COUNT(envelopes.envelope_id) as number_of_recipients,
						envelopes.envelope_id AS envelope_id,
						envelopes.to_person,
						envelopes.from_person,
						envelopes.message_id,
						envelopes.when_sent,
						mmmessages.message_id,
						mmmessages.thread_id,
						mmmessages.priority,
						mmmessages.created,
						threads.is_todo,
						threads.is_done,
						mmmessages.content,
						threads.thread_id,
						threads.thread_name as subject,
						threads.patient_id as patient_id,
						CONCAT(to_person.last_name, \" ,\", to_person.first_name) as to_field,
						CONCAT(from_person.last_name, \" ,\", from_person.first_name) as from_field,
						from_person.last_name AS from_last,
						seen.person_id,
						seen.seen, 
						seen.seen_when
			FROM `mmmessages`
			LEFT JOIN envelopes ON envelopes.message_id = mmmessages.message_id
			LEFT JOIN threads ON mmmessages.thread_id = threads.thread_id
			LEFT JOIN person AS to_person ON to_person.person_id = envelopes.to_person
			LEFT JOIN person AS from_person ON from_person.person_id = envelopes.from_person
			LEFT JOIN seen ON  mmmessages.message_id = seen.message_id 
			WHERE threads.thread_id = $thread_id AND 
				seen.person_id = $person_id
			GROUP by envelopes.message_id
			ORDER by envelopes.envelope_id ASC
		";

//	echo "thread display sql <br>$sql<br>";

		$res = $this->_execute($sql);
		$ret = array();

		while(!$res->EOF) {
			$this_id = $res->fields['envelope_id'];
			$ret[$this_id] = $res->fields;
			$ret[$this_id]['last_one'] = false;

			if($ret[$this_id]['number_of_recipients'] > 1){
				// get recipients list
				$message_id = $ret[$this_id]['message_id'];
				$to_sql = "
				SELECT   
				to_person.person_id,   
				CONCAT(to_person.last_name, \" ,\", to_person.first_name) as to_field 
				FROM `mmmessages` 
				LEFT JOIN envelopes ON envelopes.message_id = mmmessages.message_id 
				LEFT JOIN threads ON mmmessages.thread_id = threads.thread_id 
				LEFT JOIN person AS to_person ON to_person.person_id = envelopes.to_person 
				LEFT JOIN person AS from_person ON from_person.person_id = envelopes.from_person 
				LEFT JOIN seen ON mmmessages.message_id = seen.message_id 
				WHERE 
				threads.thread_id = $thread_id AND 
				seen.person_id = $person_id AND
				mmmessages.message_id = $message_id	
				ORDER by 
				envelopes.envelope_id ASC ";


				$to_res = $this->_execute($to_sql);
				$c = "";
				$seen_to = array();
				$my_to_field = ''; // to prevent a warning
 				while(!$to_res->EOF) {
					if(!array_key_exists($to_res->fields['person_id'],$seen_to)){
						$my_to_field .= " $c ". $to_res->fields['to_field'];
						$c = "&";
						$seen_to[$to_res->fields['person_id']]= true;
					}
					$to_res->MoveNext();
				}
				$ret[$this_id]['to_field'] = $my_to_field;

			}
			$res->MoveNext();
		}
		$ret[$this_id]['last_one'] = true;

		//echo "returning: <br>";
		//var_export($ret);
		//echo "SQL: <br>".$sql;
		
		return $ret;		

	}

	
	/**
	 * Getter for Primary Key: thread_id
 	* 
 	* @return int
        */
	function get_thread_id() {
		return $this->id;
	}

	/**
	 * Setter for Primary Key: thread_id
	*
 	* @param int 
 	* 
 	* @return void 
        */
	function set_thread_id($id)  {
		$this->id = $id;
	}

	/**#@-*/

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return array 
        */
	function missingToPeople(){

		$to_list = array_flip($this->getToList());
		$people_list = array_flip($this->getPersonList());
		$missing_to = array_diff($people_list,$to_list);
		return($missing_to);
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return array
        */
	function getToList() {

		$thread_id = $this->id;
		
		$sql = "SELECT 
				messages.message_id, 
				to_p.person_id AS to_id, 
				concat_ws( ' ', to_p.first_name, to_p.last_name ) AS to_name, 
				to_type.person_type AS to_type
			FROM threads
			LEFT JOIN mmmessages messages ON messages.thread_id = threads.thread_id
			LEFT JOIN envelopes ON messages.message_id = envelopes.message_id
			LEFT JOIN person to_p ON envelopes.to_person = to_p.person_id
			LEFT JOIN person_type to_type ON envelopes.to_person = to_type.person_id
			WHERE threads.thread_id = $thread_id
			ORDER BY messages.message_id DESC 			
";
 		$results = $this->_db->Execute($sql) or die ("Database Error: " . $this->_db->ErrorMsg());
                while ($results && !$results->EOF) {
		//	var_export($results->fields);
			if(strlen($results->fields['to_id']) > 0){
			if($results->fields['to_type']<2){
                      		$returnArray[$results->fields['to_id']] = "Patient: ".$results->fields['to_name']; 
			} else{
                      		$returnArray[$results->fields['to_id']] = "User: ".$results->fields['to_name']; 
			} }

                        $results->MoveNext();
                }
	//	var_export($returnArray);
		return $returnArray;
	}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return array
        */
	function getPersonList() {

		$thread_id = $this->id;
		
		$sql = "SELECT
			messages.message_id,  
			to_p.person_id as to_id, 
			concat_ws(' ',to_p.first_name,to_p.last_name) as to_name,
			to_type.person_type as to_type,
			from_p.person_id as from_id, 
			concat_ws(' ',from_p.first_name,from_p.last_name) as from_name,  
			from_type.person_type as from_type
			FROM threads  
			LEFT JOIN mmmessages messages ON messages.thread_id = threads.thread_id
			LEFT JOIN envelopes ON messages.message_id = envelopes.message_id
			LEFT JOIN person to_p ON envelopes.to_person = to_p.person_id
			LEFT JOIN person_type to_type on envelopes.to_person = to_type.person_id
			LEFT JOIN person from_p ON envelopes.from_person = from_p.person_id
			LEFT JOIN person_type from_type on envelopes.from_person = from_type.person_id
			WHERE threads.thread_id = $thread_id
			ORDER BY messages.message_id DESC
			";
 		$results = $this->_db->Execute($sql) or die ("Database Error: " . $this->_db->ErrorMsg());
                while ($results && !$results->EOF) {
		//	var_export($results->fields);

                        if(strlen($results->fields['to_id']) > 0){
                        if($results->fields['to_type']<2){
                                $returnArray[$results->fields['to_id']] = "Patient: ".$results->fields['to_name'];
                        } else{
                                $returnArray[$results->fields['to_id']] = "User: ".$results->fields['to_name'];
                        } }
                        if(strlen($results->fields['from_id']) > 0){
                        if($results->fields['from_type']<2){
                                $returnArray[$results->fields['from_id']] = "Patient: ".$results->fields['from_name'];
                        } else{
                                $returnArray[$results->fields['from_id']] = "User: ".$results->fields['from_name'];
                        } }


                        $results->MoveNext();
                }
	//	var_export($returnArray);
		return $returnArray;
	}


}
?>
