<?php
//require_once CELLINI_ROOT . '/includes/Datasource_sql.class.php';
/**
 * This is Message_MessageDataList_DS.class.php
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
class Message_MessageDataList_DS extends Datasource_sql 
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
	var $_internalName = 'Message_MessageDataList_DS';

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
	function Message_MessageDataList_DS($external_id) {
		$external_id = intval($external_id);
		
		$this->setup(Celini::dbInstance(),
			array(
				'cols'    => "last_edit, f.name, f.form_id as form_id, form_data_id, external_id",
				'from'    => "form_data AS d
				             INNER JOIN form AS f USING(form_id)
							LEFT JOIN encounter AS e ON(d.external_id = e.encounter_id)",
				'orderby' => 'name, last_edit ASC',
				'where'   => " d.form_id = 600010"
			),
			array('name' => 'Plan Name', 'form_id' => 'Form ID', 'last_edit'=>'Last Edit'));
	}
}
/*
SELECT 
envelopes.envelope_id,
envelopes.to_person,
envelopes.from_person,
envelopes.message_id,
envelopes.when_read,
envelopes.when_sent,
mmmessages.message_id,
mmmessages.thread_id,
mmmessages.created,
mmmessages.is_todo,
mmmessages.is_done,
mmmessages.content,
threads.thread_id,
threads.thread_name as subject
FROM `envelopes`
LEFT JOIN mmmessages ON envelopes.message_id = mmmessages.message_id
LEFT JOIN threads ON mmmessages.thread_id = threads.thread_id
WHERE to_person = 614268
*/
