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
 * @see "datasources/YDPProtocol_DS.class.php"
 */
$loader->requireOnce("datasources/YDPProtocol_DS.class.php");

/**	
* Short Description
*
* Long Description
* Long Description  
*
* @package ydp
*/
class C_YDPProtocol extends Controller {

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* 
 	* @return void
        */
	function C_YDPProtocol ($template_mod = "general") {
		parent::Controller();
		$this->template_mod = $template_mod;

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
	function actionSavefile_edit($protocol_id){

       	 	if($protocol_id > 0){
      	        
			$protocol_id_array = array ($protocol_id);
		}else{
			//FRED throw error
			
		  	$my_protocol =& ORDataObject::Factory('YDPProtocol');
			$protocol_id_array = $my_protocol->idList();
		}

		foreach($protocol_id_array as $this_protocol_id){
		$my_protocol =& ORDataObject::Factory('YDPProtocol',$this_protocol_id);

		$file_id = $my_protocol->get('file_name');
		$plan_file_id = $my_protocol->get('plan_file_name');

		$file_name = APP_ROOT."/user/forms/$file_id.tpl.html";
//		$plan_file_name = APP_ROOT."/user/forms/$plan_file_id.tpl.html";

                $this->assign('name',$my_protocol->get('name'));

                $this->assign('file_name',$my_protocol->get('file_name'));
  //              $this->assign('plan_file_name',$my_protocol->get('plan_file_name'));
                if($my_protocol->get('active')==0){
                        $this->assign('active',false);
                }else{
                        $this->assign('active',true);
                }
                $this->assign('history_targets',str_replace('"',"",$my_protocol->get('history_targets')));
                $this->assign('risk_factors',str_replace('"',"",$my_protocol->get('risk_factors')));
                $this->assign('outcome_goals',str_replace('"',"",$my_protocol->get('outcome_goals')));
                $this->assign('treatment_goals',str_replace('"',"",$my_protocol->get('treatment_goals')));
                $this->assign('medications',str_replace('"',"",$my_protocol->get('medications')));
                $this->assign('lab_studies',str_replace('"',"",$my_protocol->get('lab_studies')));
                $this->assign('imaging_studies',str_replace('"',"",$my_protocol->get('imaging_studies')));
                $this->assign('referral',str_replace('"',"",$my_protocol->get('referral')));
                $this->assign('interventions',str_replace('"',"",$my_protocol->get('interventions')));


                $meds_string = $my_protocol->get('medications');
		$meds_array = split(",",$meds_string);
		foreach($meds_array as $readable_string){
			$variable_name_string=strtolower(preg_replace("/ / ","_",
				preg_replace("/[^a-z \d]/i", "", $readable_string)));
			$new_meds_array[$variable_name_string] = $readable_string;
		}
		$meds_array = $new_meds_array;

		$this->assign_by_ref('meds_array',$meds_array);

                $risks_string = $my_protocol->get('risk_factors');
		$risks_array = split(",",$risks_string);
		$new_risks_array = array();
		foreach($risks_array as $readable_string){
			$variable_name_string=strtolower(preg_replace("/ / ","_",
				preg_replace("/[^a-z \d]/i", "", $readable_string)));
			$new_risks_array[$variable_name_string] = $readable_string;
		}
		$risk_array = $new_risks_array;
		$this->assign_by_ref('risks_array',$risk_array);
			
		$form = $this->fetch(Celini::getTemplatePath("/ydpprotocol/protocol_form_template.html"));
//		$plan_form = $this->fetch(Celini::getTemplatePath("/ydpprotocol/plan_form_template.html"));
	
		if(file_exists($file_name)){	

			if($fp = @fopen($file_name,"w ")) 
     			{
				fwrite($fp, $form); 
		          	fclose($fp);
				$back = Celini::link('default','YDPProtocol'); 
				$this->messages->addMessage("Protocol File $file_name successfully written");
				$this->messages->addMessage("Return to the <a href='$back'>list</a>");
			
			}else{
				$this->messages->addMessage("ERROR File $file_name cannot be opened");
			}
		}else{
				$this->messages->addMessage("ERROR File $file_name does not already exists. Create the form first and then only use this system to modify it.");
			
		}


		//this is the old plan engine.
/*
		if(file_exists($plan_file_name)){	

			if($fp = @fopen($plan_file_name,"w ")) 
     			{
				fwrite($fp, $plan_form); 
		          	fclose($fp);
				$back = Celini::link('default','YDPProtocol'); 
				$this->messages->addMessage("Plan File $plan_file_name successfully written");
				$this->messages->addMessage("Return to the <a href='$back'>list</a>");
			
			}else{
				$this->messages->addMessage("ERROR File $plan_file_name cannot be opened");
			}
		}else{
				$this->messages->addMessage("ERROR File $plan_file_name does not already exists. Create the form first and then only use this system to modify it.");
			
		}
*/


		}//foreach
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	* 
 	* @return mixed
        */
	function actionDefault_view() {
		
        $this->assign('SAVE_ALL_LINK',Celini::link('savefile','YDPProtocol'));
	$protocolds =& new YDPProtocol_DS();	
	       $protocolDataGrid =& new cGrid($protocolds);
                        $protocolDataGrid->name = "formDataGrid";
                        $protocolDataGrid->registerTemplate('id',
                                '<a href="'.Celini::link('edit','YDPProtocol').
                                'protocol_id={$id}">{$name}</a>'
                                );

                        $protocolDataGrid->registerTemplate('file_name',
                                '<a href="'.Celini::link('savefile','YDPProtocol').
                                'protocol_id={$id}">{$file_name}</a>'
                                );

                        $protocolDataGrid->pageSize = 50;
                //      $formDataGrid->setExternalId($user_id);

        $this->assign_by_ref('protocolDataGrid',$protocolDataGrid);


	return $this->fetch(Celini::getTemplatePath("/ydpprotocol/" . $this->template_mod . "_default.html"));
	
	}




	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* 
 	* @return mixed
        */
        function actionEdit($protocol_id) {

	if($protocol_id > 0){
		$my_protocol =& ORDataObject::Factory('YDPProtocol',$protocol_id);
	//var_export($_POST);

	if(array_key_exists('process',$_POST)){

		$my_variables = $_POST['text'];
		$my_protocol->populate_array($my_variables);
		$my_protocol->persist();
		$my_protocol->get('id');
		$this->messages->addMessage(
			"Protocol Added/ Modified");
		 $this->assign("FORM_ACTION", Celini::link(true). "?protocol_id=$protocol_id");
	}

		$this->assign('name',$my_protocol->get('name'));

		$this->assign('file_name',$my_protocol->get('file_name'));
		if($my_protocol->get('active')==0){
			$this->assign('active',false);
		}else{
			$this->assign('active',true);
		}
		$this->assign('history_targets',$my_protocol->get('history_targets'));
		$this->assign('risk_factors',$my_protocol->get('risk_factors'));
		$this->assign('outcome_goals',$my_protocol->get('outcome_goals'));
		$this->assign('treatment_goals',$my_protocol->get('treatment_goals'));
		$this->assign('medications',$my_protocol->get('medications'));
		$this->assign('lab_studies',$my_protocol->get('lab_studies'));
		$this->assign('imaging_studies',$my_protocol->get('imaging_studies'));
		$this->assign('referral',$my_protocol->get('referral'));
		$this->assign('new',false);
		$this->assign("FORM_ACTION", Celini::link(true). "?protocol_id=$protocol_id");
		
	}else{
		$my_protocol =& ORDataObject::Factory('YDPProtocol');
		$this->assign('new',true);
		 $this->assign("FORM_ACTION", Celini::link(true));
	}



        return $this->fetch(Celini::getTemplatePath("/ydpprotocol/" . $this->template_mod . "_edit.html"));

        }



}

?>
