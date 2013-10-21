<?php
/**
 * This is C_Plan.class.php
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
 * @see "includes/Grid.class.php"
 */
$loader->requireOnce("includes/Grid.class.php");
/**
 * @see "includes/Datasource_sql.class.php"
 */
$loader->requireOnce("includes/Datasource_sql.class.php");
/**
 * @see "ordo/Plans.class.php"
 */
$loader->requireOnce("ordo/Plans.class.php");
/**
 * @see "datasources/Plan_FormDataList_DS.class.php"
 */
$loader->requireOnce("datasources/Plan_FormDataList_DS.class.php");

/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class C_Plan extends Controller {
	/**	
         * This is a variable $template_mod
         * @var string
         */
	var $filters = "";


	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* 
 	* @return void
        */
	function C_Account ($template_mod = "general") {
		parent::Controller();
		$this->template_mod = $template_mod;

		if (!isset($_SESSION['clearhealth']['filters'][get_class($this)])) {
			$_SESSION['clearhealth']['filters'][get_class($this)] = array();
		}
		$this->filters = $_SESSION['clearhealth']['filters'][get_class($this)];
		$this->assign("filters",$this->filters);
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
	function actionDefault_view($patient_id) {



		/*
		$hds =& new Datasource_AccountHistory($this->filters);
		$hds->setup($patient_id);
		$renderer =& new Grid_Renderer_AccountHistory();
		$history_grid =& new cGrid($hds,$renderer);
		$this->assign_by_ref("history_grid",$history_grid);

		$building =& ORDataObject::Factory('Building');
		$this->assign_by_ref('building',$building);

		$ip =& ORDataObject::Factory('InsuranceProgram');
		$this->assign_by_ref('insuranceProgram',$ip);
		
		return $this->fetch(Celini::getTemplatePath("/account/" . $this->template_mod . "_history.html"));
*/
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
	function actionAdd($patient_id) {

		if(array_key_exists('process',$_POST)){//Then this form has been submitted...
				//So we create the 	
			$plan =& ORDataObject::Factory('Plans');
			$plan->populate_array($_POST);
			if($plan->persist()){
				$planid = $plan->get('id');
				$edit_link = Celini::link('edit','Plan')."plan_id=$planid";
				header("Location: $edit_link");		
			}else{//This is a duplicate plan for this patient 
				$this->messages->addMessage('ERROR: there is already a plan of this type for this patient');

			}
		}else{
			$this->assign('FORM_ACTION',Celini::link('add','Plan')."patient_id=$patient_id");
			$this->assign('patient_id',$patient_id);
			return $this->fetch(Celini::getTemplatePath("/plan/" . $this->template_mod . "_add.html"));
		}

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
	function actionEdit($plan_id) {
		$process = false;
		if($plan_id == 0 && array_key_exists('plan_id',$_POST)){
			$plan_id = $_POST['plan_id'];
			$process = true;
		}
		$plan =& ORDataObject::Factory('Plans',$plan_id);
		if($process){
			$plan->populate_array($_POST);
			$plan->persist();
		}
		$this->assign('patient_id',$plan->get('patient_id'));
		$this->assign('provider_id',$plan->get('provider_id'));
		$id = $plan->get('id');
		$this->assign('id',$id);
		$this->assign('plan_type',$plan->get('plan_type'));
		$this->assign('plan_status',$plan->get('plan_status'));
	
		$protocol =& ORDataObject::Factory('YDPProtocol');
		$plan_content_form_id = $protocol->planform_from_type($plan->get('plan_type'));

	//	$plan_content_form_id = 600010;// could change per setup
				// Form/fillout?id=600010
		$this->assign('PLAN_LINK',Celini::link('fillout','Form')."id=$plan_content_form_id&form_data_id=0&external_id=$id");

		$plan_ds =& new Plan_FormDataList_DS($id);
		$formDataGrid =& new cGrid($plan_ds);
		$formDataGrid->name = "formDataGrid";
		$formDataGrid->registerTemplate('name','<a href="'.Celini::link('fillout','Form').
		"id=$plan_content_form_id&".'form_data_id={$form_data_id}'."&external_id=$id".'">{$name}</a>');
		$formDataGrid->pageSize = 10;
		$formDataGrid->setExternalId($plan->get('id'));
		$this->assign_by_ref('formDataGrid',$formDataGrid);


		return $this->fetch(Celini::getTemplatePath("/plan/" . $this->template_mod . "_edit.html"));


	}


}

?>
