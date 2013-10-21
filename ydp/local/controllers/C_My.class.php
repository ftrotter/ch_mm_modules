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
 * This is aloader
 *
 * @see "/controllers/PHRController.class.php"
 */
$loader->requireOnce("/controllers/PHRController.class.php");
/**
 * This is aloader
*
 * @see "/includes/Menu.class.php"
 */
$loader->requireOnce("/includes/Menu.class.php");
/**
 * This is aloader
 *
 * @see '/includes/Grid.class.php'
 */
$loader->requireOnce('/includes/Grid.class.php');

/**	
* This is a class C_My 
*
* The main controller is used to perform  wrapping dance around the controller that 
* does the actual work  
*
* @package ydp
*/
class C_My extends PHRController {
        /**	
         * This is a variable $template_mod
         * @var string
         */
	var $template_mod;
        /**	
         * This is a variable $menu 
         * @var string
         */
	var $menu = false;
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
        * @param string
        *
        * @global array $GLOBALS['C_MAIN']
        * @global array $GLOBALS['config']
        * @global array $GLOBALS['extra_css']
        * 
 	* @return void
        */
	function C_My ($template_mod = "general") {
		parent::Controller();
		$this->template_mod = $template_mod;
		$this->assign("FORM_ACTION", "index.php?" . $_SERVER['QUERY_STRING']);
		$this->assign("TOP_ACTION", $_SERVER['SCRIPT_NAME']."/main/");
		$this->assign("NAV_ACTION", $_SERVER['SCRIPT_NAME']."/");
		if (!isset($GLOBALS['style'])) {
			$GLOBALS['style'] = "";
		}
		$this->assign("STYLE", $GLOBALS['style']);
		if (isset($_GET['set_print_view'])) {
			$this->_print_view = true;
		}
		
		$GLOBALS['C_MAIN'] = array();

		if (isset($GLOBALS['config']['use_menu']) && $GLOBALS['config']['use_menu']) {
			$this->menu =& Menu::getInstance();
			$this->assign('menu_group',$this->menu->getSection());
		}
		
		if (isset($GLOBALS['config']['extra_css']) && is_array($GLOBALS['config']['extra_css'])) {
			$this->assign('extra_css', $GLOBALS['config']['extra_css']);
		}

		$this->assign('translate',$GLOBALS['config']['translate']);
		
		// If it's set, assign the base_dir
		if (isset($GLOBALS['config']['base_dir'])) {
			$this->assign('base_dir', $GLOBALS['config']['base_dir']);
		}
	}
        
        /**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
        *
        * @global array $GLOBALS['entry_file']
        * @global array $GLOBALS['config']
        * @global array $GLOBALS['C_MAIN']
        * @global array $GLOBALS['util']
        * @global array $GLOBALS['default_template']
        *
 	* @return mixed fetch()
        */
	function display($display = "") {
		$this->assign("display",$display);


		$this->assign('HOME_ACTION',Celini::link('default','MyRecord','my'));	
		$this->assign('INBOX_ACTION',Celini::link('inbox','MyMessages','my'));		
		$this->assign('SEND_ACTION',Celini::link('new','MyMessages','my'));
		$this->assign('LOGOUT_ACTION',Celini::link('logout','MyRecord','my'));

		if(array_key_exists('entry_file',$GLOBALS['config'])){$entry_file = $GLOBALS['config']['entry_file'].'/main/';}else{
                $entry_file = 'index.php'.'/my/';}  

		$url_string = substr($_SERVER['PHP_SELF'],strpos(strtolower($_SERVER['PHP_SELF']),$entry_file)+strlen($entry_file));
        	$url_string = strtolower($url_string);
        	$controller_array = split('/',$url_string);
		$controller_string = implode('_',$controller_array);  
		$this->assign('controller_string',$controller_string);
		$this->assign('controller',$controller_array[0]);
	
		foreach($GLOBALS['C_MAIN'] as $key => $val) {
			$this->assign($key,$val);
		}

		if (isset($GLOBALS['util']) && $GLOBALS['util'] == true) {
			return $display;
		}
		if ($this->menu && is_object($this->menu)) {
			$this->assign('menu',$this->menu->toArray());
			$this->assign('menu_group',$this->menu->getSection());
			$this->assign('menu_current',$this->menu->getCurrent());

		}
		
		if ($this->_print_view) {
			return $this->fetch(Celini::getTemplatePath("/main/" . $this->template_mod . "_print.html"));
		}
		else {
			$page = "list";
			if (isset($GLOBALS['config']['default_template'])) {
				$page = $GLOBALS['config']['default_template'];
			}
			return $this->fetch(Celini::getTemplatePath("/my/" . $this->template_mod . "_$page.html"));	
		}
	}
	
	
	/**
	 * This handles all requests to export grids.
	 *
	 * This will create a file of the type $to for the DS of the name $grid 
	 * and return it as a downloadable file
	 * Note: All execution stops after this has been executed
 	 * 
 	 * @param	string
	 * @param	string
	 * @param	string
	 * 
	 * @return void
 	*/
	function export_grid_action($to, $dsName, $external_id) {
		$mimeType = $this->_checkMimeType($to);
	
		include_once CELLINI_ROOT . '/includes/DatasourceFileLoader.class.php';
		$loader =& new DatasourceFileLoader();
		$loader->load($dsName);
		$ds =& new $dsName($external_id);
		
		include_once CELLINI_ROOT . '/includes/Grid.class.php';
		$grid =& new cGrid($ds);

		$this->_sendGridToBrowser($grid, $dsName . '-' . date("dmYHis"), $to);
	}
	
	
	/**
	 * This handles requests to export part of a report
	 *
	 * @todo This is going to be a big ol' ugly method until we get the
	 * datasource and grid portion of report generation out of 
	 * {@link Controller::report_action_view()} and into it's own object.
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * 
	 * @return void
 	*/
	function export_report_action($to, $external_id, $name) {
		$mimeType = $this->_checkMimeType($to);
		
		require_once CELLINI_ROOT ."/includes/ReportFilter.class.php";

		$r =& ORDataObject::factory('Report',$external_id);

		$queries = $r->get('exploded_query');
		if (count($queries) == 0) {
			$queries['default'] = $r->get('query');
		}
		foreach($queries as $key => $query) {
			// don't worry about the other queries in the report
			if (!preg_match('/^' . $name . '(,(.+))?$/', $key)) {
				continue;
			}
			if (strstr($key,',')) {
				$flags = explode(',',$key);
				$key = array_shift($flags);
				$report['flags'] = $flags;
			}
			$report['filter'] =& new ReportFilter($query);

			$report['ds'] =& $report['filter']->getDatasource();
			foreach($report['filter']->dsFilters as $k => $val) {
				if (strstr($val[0],'&')) {
					$tmp = explode('&',$val[0]);

					if ($tmp[1] == 'ds') {
						$val[0] = array($report['ds'],$tmp[0]);
					}
					else {
						$val[0] = array(${$tmp[1]},$tmp[0]);
					}
				}
				$report['ds']->registerFilter($k,array_shift($val),$val);
			}

			if (isset($flags) && in_array('class',$flags)) {
				$extra = $report['filter']->extraData;
				$c = "Report_".$extra['class'];
				if (!class_exists($c)) {
					if (file_exists( APP_ROOT."/local/includes/$c.class.php")) {
						require_once APP_ROOT."/local/includes/$c.class.php";
					}
				}

				if (!class_exists($c)) {
					trigger_error("Unable to load class $c for dataset $key",E_USER_ERROR); 
				}

				$o = new $c();
				foreach($extra as $k => $v) {
					$o->$k = $v;
				}

				$report['ds'] =& $o->getDs();
			}

			$report['grid'] =& new cGrid($report['ds']);
			$report['grid']->pageSize = 30;
			$report['grid']->name = $key;
			if ($key != 'default') {
				$report['title'] = $key;
			}
			
			$report['ds']->_type = $to;
		}

		$toReplace = array(':', '/', ' ');
		$filename = str_replace($toReplace, '_', $r->get('label'));
		if ($name != 'default') {
			$filename .= '-' . str_replace($toReplace, '_', $name);
		}
		$this->_sendGridToBrowser($report['grid'], $filename, $to);
	}
	
	
	/**
	 * short comment
         * 
         * Sends a Grid to the browser as a file
	 *
	 * @param object
	 * @param string
	 * @param string
         *
         * @return void
	 */
	function _sendGridToBrowser(&$grid, $filename, $filetype) {
		$mimeType = $this->_checkMimeType($filetype);
		
		$rendererName = 'Grid_Renderer_' . $filetype;
		$grid->set_renderer(new $rendererName());
		$grid->setOutputType($filetype);
		
		$this->_sendFileDownloadHeaders($mimeType, $filename. '.' . $filetype);
		echo $grid->render(false);
		exit;
	}
	
	/**
	 * Determine whether or not a given type is allowed and returns the 
	 * mime-type string.
	 *
	 * This should eventually be its own object and would throw an exception in
	 * PHP 5 on an error so a higher level could attempt to recover and present
	 * something meaningful.
	 *
	 * @param string
	 * 
	 * @return list $mimeTypes[$to]
	 */
	function _checkMimeType($to) {
		static $mimeTypes = array('csv' => 'text/csv');
		if (!isset($mimeTypes[$to])) {
			die('Unrecognized export type: ' . htmlspecialchars($to));
		}
		
		return $mimeTypes[$to];
	}

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return mixed imagepng
        */
	function displayexporttocsvicon_action() {
		header('Content-Type: image/png');
		$img = imagecreatefrompng(CELLINI_ROOT . '/images/export-to-csv.png');
		imagepng($img);
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @return mixed run_child_controller()
        */
	function empty_action() {
		return $this->fetch(Celini::getTemplatePath("/main/" . $this->template_mod . "_list.html"));
	}
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
        * @param string 
        * @param string 
        *
        * @return mixed run_child_controller()
        */
	function magic_action($controller,$action,$managerArg = "") {
		$fga = func_get_args();
		array_splice($fga,0,3);
		return $this->run_child_controller($controller,$action,$managerArg,$fga);
	}
	
	/**	
        * short comment
        *
	* long comment for the class method below 
	*
        *	@param string 
        *	@param string 
        *	@param string 
        *	@param string 
        *
        *	@return	mixed display()  
        */
	function run_child_controller($controller,$action,$managerArg,$extra_args) {
		$c = new Controller();
		$args = array_merge(array($controller => "",$action => "",$managerArg => $managerArg),$extra_args);
		$display = $c->act($args);
		if (!$c->_continue_processing) {
			return $display;
		}
		return $this->display($display);
	}


}
?>
