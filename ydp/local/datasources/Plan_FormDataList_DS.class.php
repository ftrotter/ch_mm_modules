<?php
/**
 * This is Plan_FormDataList_DS.class.php
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
 * @see '/includes/Datasource_sql.class.php'
 */
$loader->requireOnce('/includes/Datasource_sql.class.php');
/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class Plan_FormDataList_DS extends Datasource_sql 
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
	var $_internalName = 'Plan_FormDataList_DS';

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
	function Plan_FormDataList_DS($external_id) {
		$external_id = intval($external_id);
		
		$this->setup(Celini::dbInstance(),
			array(
				'cols'    => "last_edit, f.name, f.form_id as form_id, form_data_id, external_id",
				'from'    => "form_data AS d
				             INNER JOIN form AS f USING(form_id)
							LEFT JOIN encounter AS e ON(d.external_id = e.encounter_id)",
				'orderby' => 'name, last_edit ASC',
				'where'   => "external_id = {$external_id} AND d.form_id = 600010"
			),
			array('name' => 'Plan Name', 'form_id' => 'Form ID', 'last_edit'=>'Last Edit'));
	}
}

