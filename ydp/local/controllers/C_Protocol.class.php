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
 * @see "controllers/Controller.class.php"
 */
$loader->requireOnce("controllers/Controller.class.php");
/**
 * @see "datasources/User_FormDataList_DS.class.php"
 */
$loader->requireOnce("datasources/User_FormDataList_DS.class.php");

/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class C_Protocol extends Controller {

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* 
 	* @return void
        */
	function C_Protocol ($template_mod = "general") {
		parent::Controller();
		$this->template_mod = $template_mod;

	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed fetch()
        */
	function actionDefault_view() {
		
// Display links to the forms for each of the protocols

	$user_id = $this->_me->get_id();
//	echo $user_id;

	$this->assign('diabetes_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001659&form_data_id=0&external_id=$user_id\">Diabetes</a>");
	$this->assign('depression_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001689&form_data_id=0&external_id=$user_id\">Depression</a>");
	$this->assign('excercise_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001699&form_data_id=0&external_id=$user_id\">Prevention</a>");
	$this->assign('hypertension_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001694&form_data_id=0&external_id=$user_id\">Hypertension</a>");
	$this->assign('chf_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001679&form_data_id=0&external_id=$user_id\">Congestive Heart Failure</a>");
	$this->assign('cad_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001684&form_data_id=0&external_id=$user_id\">Coronary Artery Disease</a>");
	$this->assign('lipids_form',
		'<a href="'.Celini::link('fillout','Form')."id=1008457&form_data_id=0&external_id=$user_id\">Lipids</a>");
	$this->assign('adult_asthma_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001664&form_data_id=0&external_id=$user_id\">Asthma</a>");
	$this->assign('otitis_media_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001714&form_data_id=0&external_id=$user_id\">Otitis Media</a>");
	$this->assign('pharyngitis_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001719&form_data_id=0&external_id=$user_id\">Pharyngitis</a>");
	$this->assign('child_asthma_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001674&form_data_id=0&external_id=$user_id\">Child Asthma</a>");
	$this->assign('add_form',
		'<a href="'.Celini::link('fillout','Form')."id=1001669&form_data_id=0&external_id=$user_id\">Attention Deficit Disorder</a>");

	$userds =& new User_FormDataList_DS($user_id);
	$formDataGrid =& new cGrid($userds);
			$formDataGrid->name = "formDataGrid";
			$formDataGrid->registerTemplate('name','<a href="'.Celini::link('fillout','Form').'id={$form_id}&form_data_id={$form_data_id}">{$name}</a>');
			$formDataGrid->pageSize = 10;
			$formDataGrid->setExternalId($user_id);
	
	$this->assign_by_ref('formDataGrid',$formDataGrid);

	
	return $this->fetch(Celini::getTemplatePath("/protocol/" . $this->template_mod . "_default.html"));
	
	}


}

?>
