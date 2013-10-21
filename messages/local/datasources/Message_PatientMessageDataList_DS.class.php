<?php

//require_once CELLINI_ROOT . '/includes/Datasource_sql.class.php';
/**
 * This is Message_PatientMessageDataList_DS.class.php
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
 * This is the data source for listing messages for a patient on the 
 * dashboard for that patient
 * @package messages
 */
class Message_PatientMessageDataList_DS extends Datasource_sql 
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
	var $_internalName = 'Message_PatientMessageDataList_DS';

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
	function Message_PatientMessageDataList_DS($external_id) {
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
WHERE envelopes.to_person = 1000361
GROUP BY mmmessages.thread_id
ORDER BY envelopes.envelope_id DESC 
*/


		$labels = array(
			//	'priority' => 'Priority',
				'subject' => 'Thread Subject',
				'created' => 'Date',
				'source' => 'From',
				'dest' => 'To',
				'content' => 'Message',

                        //      'from_last'=>'From Last',
                        //      'message_id'=>'Message',
                        //      'thread_id'=>'Thread',

                        );

                if($GLOBALS['config']['messages']['email']['no_priority']){
                        //do nothing
                }else{
                        $labels['priority'] = 'Priority';
                }



		$this->setup(Celini::dbInstance(),
			array(
				'cols'    =>	"
	     mmmessages.priority as priority,
            threads.thread_id AS message_number,
            threads.thread_id AS read_number,
            threads.thread_id AS bold,
            threads.thread_id AS unbold,
            threads.thread_id AS source,
            threads.thread_name as subject,
            mmmessages.message_id as source,
            mmmessages.thread_id as dest,
            mmmessages.thread_id,
            mmmessages.created,
            threads.is_todo as is_todo,
            mmmessages.content,
            threads.thread_id,
            threads.is_done as is_done,
            threads.patient_id
						",
				'from'    =>	"
	    `envelopes`
            LEFT JOIN mmmessages
                        ON envelopes.message_id = mmmessages.message_id
            JOIN threads
                        ON mmmessages.thread_id = threads.thread_id
",
				
				'orderby' => 'mmmessages.created DESC',
				'groupby' => 'mmmessages.message_id',
				'where'   => "patient_id = $external_id"),

				$labels
				);

//		echo $this->preview();


//		$this->registerFilter('is_done',array(&$this,'taskDisplay'));
		$this->registerFilter('created',array(&$this,'prettySent'));
		$this->registerFilter('source',array(&$this,'listFrom'));
		$this->registerFilter('dest',array(&$this,'listTo'));
		$this->registerFilter('content',array(&$this,'prettyMessage'));
		$this->registerFilter('priority',array(&$this,'colorPriority'));
		$this->registerFilter('subject',array(&$this,'colorItem'));

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
        function listFrom($message_id,$row){


        $sql = "
SELECT 
envelopes.to_person as to_person_id,
to_person.first_name AS to_first, 
to_person.last_name AS to_last, 
envelopes.from_person as from_person_id,
from_person.first_name AS from_first, 
from_person.last_name AS from_last
FROM mmmessages
JOIN envelopes ON envelopes.message_id = mmmessages.message_id
LEFT JOIN person AS to_person ON to_person.person_id = envelopes.to_person
LEFT JOIN person AS from_person ON from_person.person_id = envelopes.from_person
WHERE mmmessages.message_id = $message_id
ORDER BY envelopes.envelope_id DESC
";
                $result = $this->_db->Execute($sql);

                while($result && !$result->EOF) {
                        // using this method multiple occurences of a name are simply overwritten in the array
                        $to_person_id = $result->fields['to_person_id'];
                        $from_person_id = $result->fields['from_person_id'];
                /*      if($to_person != $this_person){
                                $thread_people[$to_person] = 
                                        substr($result->fields['to_first'],0,1).". ".$result->fields['to_last'];
                        }*/
                        $from_person_array[$from_person_id] =
                                        substr($result->fields['from_first'],0,1).". ".$result->fields['from_last'];
                        $to_person_array[$to_person_id] =
                                        substr($result->fields['to_first'],0,1).". ".$result->fields['to_last'];
                        $result->MoveNext();
                }
		foreach($from_person_array as $id => $from_name){
			$return_string .= $from_name . " ";
		}

//		$return_string .= " => <br>";
/*
		foreach($to_person_array as $id => $to_name){
			$return_string .= "&nbsp;";
			$return_string .= "&nbsp;";
			$return_string .= "&nbsp;";
			$return_string .= "&nbsp;";
			$return_string .= $to_name . "<br>";
		} 
*/
                return($return_string);

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
        function listTo($thread_id,$row){


        $sql = "
SELECT 
envelopes.to_person as to_person_id,
to_person.first_name AS to_first, 
to_person.last_name AS to_last, 
envelopes.from_person as from_person_id,
from_person.first_name AS from_first, 
from_person.last_name AS from_last
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
                        $to_person_id = $result->fields['to_person_id'];
                        $from_person_id = $result->fields['from_person_id'];
                /*      if($to_person != $this_person){
                                $thread_people[$to_person] = 
                                        substr($result->fields['to_first'],0,1).". ".$result->fields['to_last'];
                        }*/
                        $person_array[$from_person_id] =
                                        substr($result->fields['from_first'],0,1).". ".$result->fields['from_last'];
                        $person_array[$to_person_id] =
                                        substr($result->fields['to_first'],0,1).". ".$result->fields['to_last'];
                        $result->MoveNext();
                }
/*
		foreach($from_person_array as $id => $from_name){
			$return_string .= $from_name . " ";
		}
*/
//		$return_string .= " => <br>";
		foreach($person_array as $id => $to_name){
			$return_string .= $to_name . "<br>";
		} 
                return($return_string);

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
	function prettyMessage($message){
		$content = wordwrap($message,80,"<br>");
	            if (strstr($content,"\n")) {
                        $pos = strpos($content,"\n");
                        $line1 = strip_tags(trim(substr($content,0,$pos)));
                        $rest = trim(substr($content,($pos+1)));
                        return "
<div>
<span style='border-bottom: 1px dotted blue;'
onmouseover=\"this.parentNode.getElementsByTagName('div').item(0).style.display = 'block'; this.style.borderBottom = '';\">$line1</span>
<div style='display:none;'>$rest</div>
</div>";
                }
                return $content;
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
 	* @param int 
 	* @param int 
 	* 
 	* @return string 
        */
	function colorItem($item,$row){
		
		$priority = $row['priority'];

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


			return "<div style='background-color: $color; font-weight: bold; margin-left: -5px;'>$item</div>";
		

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


}
