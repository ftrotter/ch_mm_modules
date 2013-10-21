<?php
/**
 * This is Patient_PlanList_DS.class.php
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
$loader->requireOnce( '/includes/Datasource_sql.class.php');
/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class Patient_PlanList_DS extends Datasource_sql 
{
	/**
	 * Short Description
	 *
	 * Long Description
	 * Long Description  
	 *
         * @var boolean
         */
	var $_planStatus = null;
	/**
	 * Short Description
	 *
	 * Long Description
	 * Long Description  
	 *
         * @var boolean
         */
	var $_planType = null;
	
	
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
	var $_internalName = 'Patient_PlanList_DS';
	
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
	function Patient_PlanList_DS($patient_id) {
		

		settype($patient_id,'int');

		$this->setup(Celini::dbInstance(), 
			array(
				'cols' 	=> 'plans.id as plan_id, 
					plans.patient_id, 
					plans.plan_name, 
					plans.plan_type as plan_type,
					plans.plan_status as plan_status,
					COUNT( * ) AS plan_versions',
				'from' 	=> "plans JOIN form_data ON form_data.external_id = plans.id",
				'where' => "patient_id = $patient_id",
				'groupby' => "plans.id"
				
			),
			array('plan_type' => 'Plan Type','plan_status' => 'Plan Status','plan_versions' => 'Plan Versions'));

		$this->registerFilter('plan_status',array(&$this,'planStatus'));
		$this->registerFilter('plan_type',array(&$this,'planType'));
	}
	
	
	/**
	 * Load encounter_reason enum if necessary
	 *
	 * @access private
 	 * 
 	 * @return mixed 
         */

	function _lookupStatusList() {
		if (!is_null($this->_planStatus)) {
			return;
		}
		
		$enum = ORDataObject::factory('Enumeration');
		$this->_planStatus = $enum->get_enum_list('plan_status');
	}
	
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	* 
 	* @return mixed 
        */	
	function planStatus($id) {
		$this->_lookupStatusList();
		if (isset($this->_planStatus[$id])) {
			return $this->_planStatus[$id];
		}
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return void
        */
	function _lookupTypeList() {
		if (!is_null($this->_planType)) {
			return;
		}
		
		$enum = ORDataObject::factory('Enumeration');
		$this->_planType = $enum->get_enum_list('protocols');
	}
	
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param int 
 	* 
 	* @return mixed 
        */	
	function planType($id) {
		$this->_lookupTypeList();
		if (isset($this->_planType[$id])) {
			return $this->_planType[$id];
		}
	}



}

