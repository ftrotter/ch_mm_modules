<?php

//require_once CELLINI_ROOT . '/includes/Datasource_sql.class.php';

/**
 * This is Message_InboxDataList_DS.class.php
 *
 * This does .......
 *
 * @author Fred Trotter<ftrotter@gmail.com>
 * @version version no
 * @copyright UTHSC-H
 * @link http://yourdoctorprogram.com/
 * @package messages
 */
/**
 *
 * Required Libs
 *
 * @see '/includes/Datasource_sql.class.php'
 */
$loader->requireOnce('/includes/Datasource_sql.class.php');
/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package messages
*/
class Message_InboxDataList_DS extends Datasource_sql 
{
	/**
	 * Stores the case-sensative class name for this ds and should be considered
	 * read-only.
	 *
	 * This is being used so that the internal name matches the filesystem
	 * name.  Once BC for PHP 4 is no longer required, this can be dropped in
	 * favor of using get_class($ds) where ever this property is referenced.
	 *
	 * @var string
	 */
	var $_internalName = 'Message_InboxDataList_DS';

	/**
	 * The default output type for this datasource.
	 *
	 * @var string
	 */
	var $_type = 'html';

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* 
 	* @return void 
        */
	function Message_InboxDataList_DS($external_id) {
		$external_id = intval($external_id);
//		echo $external_id;
/*
SELECT 
COUNT( * ) AS message_number,
envelopes.envelope_id AS envelope_id, 
envelopes.to_person, envelopes.from_person, 
envelopes.message_id, envelopes.when_sent, 
mmmessages.message_id, mmmessages.thread_id, 
mmmessages.created, mmmessages.is_todo, 
mmmessages.is_done, mmmessages.content, 
threads.thread_id, threads.thread_name AS subject, 
to_person.first_name AS to_first, 
to_person.last_name AS to_last, 
from_person.first_name AS from_first, 
from_person.last_name AS from_last, 
seen.seen, 
seen.seen_when
FROM envelopes
LEFT JOIN mmmessages ON envelopes.message_id = mmmessages.message_id
LEFT JOIN threads ON mmmessages.thread_id = threads.thread_id
LEFT JOIN person AS to_person ON to_person.person_id = envelopes.to_person
LEFT JOIN person AS from_person ON from_person.person_id = envelopes.from_person
LEFT JOIN seen ON ( mmmessages.message_id = seen.message_id
AND seen.person_id = envelopes.to_person )
WHERE envelopes.to_person =614268
GROUP BY mmmessages.thread_id
ORDER BY envelopes.envelope_id DESC 
*/
		//FRED should be from config!!
		$only_display_undone_tasks = true;
		if($only_display_undone_tasks){
			$task_logic = 'AND (threads.is_todo = 0 OR threads.is_done = 0)';
		}else{
			$task_logic = '';
		}

   $labels=array(

       
//				'priority'=>'Priority',
				'source'=>'From',
				'id' => 'Subject', 
				'patient_id'=>'Regarding Patient',
				'is_done'=>'Task Status',
				'when_sent'=>'Sent',


	 );
        if($GLOBALS['config']['messages']['email']['no_priority']){
                         //do nothing
                        }else{
                         $labels['priority'] = 'Priority';
        }


		$this->setup(Celini::dbInstance(),
			array(
				'cols'    => 	"
						COUNT(*) AS merge,
						MIN(seen.seen) as seen, 
						seen.seen_when,
						mmmessages.priority as priority,
						threads.thread_id AS message_number,
						threads.thread_id AS read_number,
						threads.thread_id AS bold,
						threads.thread_id AS unbold,
						threads.thread_id AS source,
						threads.patient_id AS patient_id,
						envelopes.envelope_id as id,
						envelopes.to_person,
						envelopes.from_person,
						envelopes.message_id,
						MAX(envelopes.when_sent) as when_sent,
						mmmessages.message_id as message_id,
						mmmessages.thread_id,
						mmmessages.created,
						threads.is_todo as is_todo,
						mmmessages.content,
						threads.thread_id,
						threads.thread_name as subject,
						threads.is_done as is_done,
						patient_person.first_name AS patient_first,
						patient_person.last_name AS patient_last,
						to_person.first_name AS to_first,
						to_person.last_name AS to_last,
						from_person.first_name AS from_first,
						from_person.last_name AS from_last
						",
				'from'    => "`envelopes`
				LEFT JOIN mmmessages ON envelopes.message_id = mmmessages.message_id
				LEFT JOIN threads ON mmmessages.thread_id = threads.thread_id
				LEFT JOIN person AS to_person ON to_person.person_id = envelopes.to_person
				LEFT JOIN person AS from_person ON from_person.person_id = envelopes.from_person
				LEFT JOIN person AS patient_person ON patient_person.person_id = threads.patient_id
				JOIN seen ON ( mmmessages.message_id = seen.message_id 
							AND seen.person_id = envelopes.to_person )

						",
				'orderby' => 'seen ASC, priority DESC,  when_sent DESC',
				'groupby' => 'mmmessages.thread_id',
				'where'   => "to_person = $external_id $task_logic  "
			),
			$labels
			);

		$this->registerFilter('is_done',array(&$this,'taskDisplay'));
		$this->registerFilter('message_number',array(&$this,'messageCount'));
		$this->registerFilter('read_number',array(&$this,'readCount'));
		$this->registerFilter('bold',array(&$this,'unreadBold'));
		$this->registerFilter('unbold',array(&$this,'unreadUnBold'));
		$this->registerFilter('source',array(&$this,'fromList'));
		$this->registerFilter('subject',array(&$this,'shortSubject'));
		$this->registerFilter('priority',array(&$this,'colorPriority'));
		$this->registerFilter('when_sent',array(&$this,'prettySent'));

		$this->_strict_labeling = true;

//		echo $this->pretty_print_sql($this->preview());


	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string
 	* @param boolean
 	* 
 	* @return void 
        */
function pretty_print_sql($sql_string,$web = true){
	if($web){
		$cr = "<br>";
		$t = " &nbsp &nbsp; &nbsp; ";
	}else{
		$cr = "\n";
		$t = "\t";
	}
	list ($select , $from ) = split('FROM',$sql_string);
	$select = str_replace('SELECT', "SELECT $cr\t\t",$select);
	$select = str_replace(',',",$cr $t $t",$select);
	echo "$select $cr";
	list ($from, $where) = split('WHERE',$from);
	$from_array =  split('LEFT JOIN',$from);
	$from = implode("$cr $t $t LEFT REPLACEMELATER ",$from_array);
	$from_array =  split('INNER JOIN',$from);
	$from = implode("$cr $t $t INNER REPLACEMELATER ",$from_array);
	$from_array =  split('OUTER JOIN',$from);
	$from = implode("$cr $t $t OUTER REPLACEMELATER ",$from_array);
	$from_array =  split('RIGHT JOIN',$from);
	$from = implode("$cr $t $t RIGHT REPLACEMELATER ",$from_array);

	$from_array =  split('JOIN',$from);
	$from = implode("$cr $t $t JOIN ",$from_array);
	$from = str_replace('LEFT REPLACEMELATER','LEFT JOIN', $from);
	$from = str_replace('INNER REPLACEMELATER','INNER JOIN', $from);
	$from = str_replace('OUTER REPLACEMELATER','OUTER JOIN', $from);
	$from = str_replace('RIGHT REPLACEMELATER','RIGHT JOIN', $from);
	$from = str_replace('ON',"$cr $t $t $t $t ON", $from);
	echo "FROM $from $cr";
	//echo "FROM has > $from \n\n"; 
	if(substr('GROUP BY',$where) !== 0){
		list ($where, $group_by) = split('GROUP BY',$where);
		echo "WHERE $cr $t $t $where $cr";
		if(substr('ORDER BY',$group_by) !== 0){
			list ($group_by, $order_by) = split('ORDER BY',$group_by);
			echo "GROUP BY $cr $t $t $group_by $cr";
			echo "ORDER BY $cr $t $t $order_by $cr";
		}
	}else{
	if(substr('ORDER BY',$where) !== 0){
		list ($where, $order_by) = split('ORDER BY',$from);
		echo "WHERE $cr $t $t $where $cr";
		echo "ORDER $cr $t $t BY $order_by $cr";
	}
}
}


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string
 	* @param boolean
 	* 
 	* @return string
        */
	function prettySent($string){

		$time_stamp = strtotime($string);
		$today_midnight = mktime(0, 0, 0, date("m") , date("d") , date("Y"));



		if($time_stamp > $today_midnight){
			//date is today just display the time
			$date_string = date( 'g:i a',$time_stamp);
		}else{// before today
			$jan_first = mktime(0,0,0,1,1,date("Y"));	
			if($time_stamp > $jan_first){
				$date_string = date('M j',$time_stamp);
			}else{ // last years dates
				$date_string = date('F j, Y',$time_stamp);
		}
		}

		return $date_string;
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param boolean
 	* @param string
 	* 
 	* @return string
        */
	function taskDisplay($is_done, $row){

		$message_id = $row['message_id'];
		$done_link = Celini::link('MarkDone','Messages')."message_id=$message_id";


		if($is_done > 0){
			$phrase = "done <a href=$done_link&done=0>mark not done</a>";
			$color = 'aqua';
		}else{
			$phrase = "not done <a href=$done_link&done=1>mark done</a>";
			$color = 'orange';
		}

		if($row['is_todo'] == 0){
			$phrase = 'not a task';
			$color = 'transparent';
		}

	return "<div style='background-color: $color; font-weight: bold; margin-left: -5px; text-align:  center;'>$phrase</div>";

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
	function colorPriority($priority){
		
		$color = "transparent";

		if ($priority == 5){
			$color = "red";
		}
		if ($priority == 4){
			$color = "yellow";
		}

                $priority_map = array(
                        0 => 'normal',
                        1 => 'low',
                        2 => 'medium',
                        3 => 'high',
                        4 => 'important',
                        5 => 'critical',
                );

                $priority = $priority_map[$priority];



			return "<div style='background-color: $color; font-weight: bold; margin-left: -5px; text-align:  center;'>$priority</div>";
		

	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string
 	* 
 	* @return string
        */
	function shortSubject($subject){

		if(strlen($subject) > 40){
			$short_sub = substr($subject,0,40)."...";
			return($short_sub);
		}else{
			return($subject);
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
 	* @return string
        */
	function fromList($thread_id,$row){
$this_person = $row['to_person'];


	$sql = "
SELECT 
envelopes.to_person as to_person_id,
to_person.first_name AS to_first, 
to_person.last_name AS to_last, 
envelopes.from_person as from_person_id,
from_person.first_name AS from_first, from_person.last_name AS from_last
FROM threads
JOIN mmmessages ON threads.thread_id = mmmessages.thread_id
JOIN envelopes ON envelopes.message_id = mmmessages.message_id
LEFT JOIN person AS to_person ON to_person.person_id = envelopes.to_person
LEFT JOIN person AS from_person ON from_person.person_id = envelopes.from_person
WHERE threads.thread_id = $thread_id
ORDER BY envelopes.envelope_id DESC
";
		$result = $this->_db->Execute($sql);

		while($result && !$result->EOF) {
			// using this method multiple occurences of a name are simply overwritten in the array
			$to_person = $result->fields['to_person_id'];
			$from_person = $result->fields['from_person_id'];
		/*	if($to_person != $this_person){
				$thread_people[$to_person] = 
					substr($result->fields['to_first'],0,1).". ".$result->fields['to_last'];
			}*/	
			if($from_person != $this_person){
				$thread_people[$from_person] = 
					substr($result->fields['from_first'],0,1).". ".$result->fields['from_last'];	
			}
			$result->MoveNext();
		}
		if(count($thread_people)>0){
			$person_list = implode(", ",$thread_people);
		}else{
			$person_list = "Self";
		}
		return($person_list);

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
	function messageCount($thread_id){
	$sql = "
SELECT	
COUNT(*) as total_count
FROM mmmessages
WHERE mmmessages.thread_id = $thread_id
		";

		$result = $this->_db->Execute($sql);
		$total_count = $result->fields['total_count'];

		return($total_count);
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param int
 	* 
 	* @return int
        */
	function readCount($thread_id,$row){

$this_person = $row['to_person'];
	$sql = "
SELECT COUNT( * ) AS read_count
FROM mmmessages
JOIN envelopes ON envelopes.message_id = mmmessages.message_id
JOIN seen ON ( mmmessages.message_id = seen.message_id )
WHERE mmmessages.thread_id = $thread_id
AND seen.person_id = $this_person
AND seen.seen = 1
		";


		$result = $this->_db->Execute($sql);
		$read_count = $result->fields['read_count'];
		return($read_count);
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int
 	* @param int
 	* 
 	* @return string
        */
	function unreadBold($thread_id,$row){

$this_person = $row['to_person'];
	$sql = "
SELECT COUNT( * ) AS read_count
FROM mmmessages
JOIN envelopes ON envelopes.message_id = mmmessages.message_id
JOIN seen ON ( mmmessages.message_id = seen.message_id )
WHERE mmmessages.thread_id = $thread_id
AND seen.person_id = $this_person
AND seen.seen = 0
		";

//echo $sql. "<br><br>";
		$result = $this->_db->Execute($sql);
		$read_count = $result->fields['read_count'];
		if($read_count>0){
			return('<b>');
		}else{
			return('');
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
 	* @return string
        */
	function unreadUnBold($thread_id,$row){

$this_person = $row['to_person'];
	$sql = "
SELECT COUNT( * ) AS read_count
FROM mmmessages
JOIN envelopes ON envelopes.message_id = mmmessages.message_id
JOIN seen ON ( mmmessages.message_id = seen.message_id )
WHERE mmmessages.thread_id = $thread_id
AND seen.person_id = $this_person
AND seen.seen = 0
		";

		$result = $this->_db->Execute($sql);
		$read_count = $result->fields['read_count'];
		if($read_count>0){
			return('</b>');
		}else{
			return('');
		}
	}



}

