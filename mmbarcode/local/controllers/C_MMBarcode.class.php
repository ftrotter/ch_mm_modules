<?php
//$loader->requireOnce('datasources/FeeSchedule_DS.class.php');
//$loader->requireOnce('includes/clni/clniActiveGrid.class.php');


class C_MMBarcode extends Controller {

	var $upload_errors;
	var $upload_status;
	var $new_file_array;

	function actionUpload_view(){

		if(count($_FILES) == 0){ //nothing uploaded... display upload form

			$my_dir = WEB_ROOT;

			$head =& Celini::HTMLheadInstance();
                	$head->addExternalCss('suggest');
                	$head->addElement(
		"<script type='text/javascript' src='$my_dir"."js/multifile_compressed.js'></script>");



			return($this->view->render("fileselect.html")); 

		}else{

			$this->_upload_process();
                       $this->messages->addMessage('',"Files have been uploaded and are in line for automatic processing <br>");
			return($this->view->render("fileselect.html")); 
//			$this->_process_files();
//                       $this->messages->addMessage('',"Processed files <br>");
//			$GLOBALS['loader']->requireOnce('controllers/C_Document.class.php');
//			$DocumentController =& new C_Document();
//			$DocumentController->batch_action_add();
//                       $this->messages->addMessage('',"Added files to patient records <br>");
		}


	}


function _upload_process(){
        $no_files = 0;
        foreach($_FILES as $file)
        {
                switch($file['error'])
                {
                        case 0:
                                // file found
                                if($file['name'] != NULL && $this->_okFileType($file['type'],$file['name']) != false){
                                        $this->_processFile($file);
                                }
                                break;

                        case (1|2):
                                // upload too large
                                $this->upload_errors[] = "file upload is too large contact support to increase the size of the files that can be uploaded";
                                break;

                        case 4:
                                // no file uploaded
                                break;

                        case (6|7):
                                // no temp folder or failed write - server config errors
                                $this->upload_errors[] = "internal server error, tmp unwritable - contact support";
                                break;
                }
        }

        if(isset($this->upload_status)){
                foreach($this->upload_status as $status_string){
                        $this->messages->addMessage('',$status_string . "<br>");
                }

        }

        if(isset($this->upload_errors)){
		foreach($this->upload_errors as $error_string){
			$this->messages->addMessage('',$error_string . "<br>");
		}

        }
}


function _okFileType($file_type,$file_name){
		if(strcmp(strtolower($file_type),'application/pdf')==0){
		        return(true);
		}else{
			$this->upload_errors[] = "ignoring file $file_name with type of $file_type";

			return(false);
		}
}


function _processFile($file){
	$config = $GLOBALS['config'];
	$mm_uploads_dir = $config['mmbarcode_starting_dir'];
	$mm_working_dir = $config['mmbarcode_working_dir'];
	$zxing_dir = $config['zxing_dir'];

	$doc_dir = $GLOBALS['config']['document_manager']['repository'];
	if(!file_exists($mm_uploads_dir)){
	
		if(mkdir($mm_uploads_dir)){
			$this->upload_status[] = 'created upload directory';
			// since this worked its safe to just create the working directory
			mkdir($mm_working_dir);	
		}else{
			$this->upload_errors[] = "Error: could not create upload directory, call support and have them check file permissions on $doc_dir";
			return;
		}
	}

	$new_file = $mm_uploads_dir . $file['name'];

	if(file_exists($new_file)){
	
		$this->upload_errors[] = "Error: there is already a file called $new_file, you have to upload unique file names";
		return;
	}
		
	$tmp_file = $file['tmp_name'];

	if(move_uploaded_file($tmp_file, $new_file)) {
		$this->upload_status[] = "uploaded file to $new_file";	
	}else{
		$this->error_status[] = "Error: failed to copy file from $tmp_file to $new_file";
	}




}












	function actionView($id) {


		$patient_id = $id;

		if(isset($_GET['cat_id'])){
			$category_id = $_GET['cat_id'];
		}else{
			$category_id = 1;
		}

   		if(isset($_GET['print_all'])){
                        $print_all = $_GET['print_all'];
                }else{
                        $print_all = 0;
                }
		if($print_all == 1){
			$this->assign('print_all',true);
		}


		if(!function_exists('ImageCreate')){


			return ("Error: You must install the php-gd library for this to work");


		}

		// Assign an array full of catagory names + the links to this controller/action
		// For display in the top
		
                $db = new clniDb();
                $sql = "select * from category";
                $names = $db->getAssoc($sql);




        $new_array = array();

        foreach($names as $cat_id => $cat_array){

                if($cat_array['parent'] == 0 || $cat_array['parent'] == 1  ){
                //This is the parent catagory!!
                        $new_array[$cat_id] = $cat_array['name'];
                }else{
                        $new_array[$cat_id] = $this->_name_recurse($cat_id,$names);
                }

        }
//       var_export($new_array);


		$cat_array = array();
		$image_array = array();

		//calculate instance URL

		$wr = 'https://' . $_SERVER['SERVER_NAME'];


		foreach($new_array as $cat_id => $name){

			$cat_array[$name] = 
				Celini::link('view','MMBarcode','print')."id=$patient_id&cat_id=$cat_id";
			$image_array[$name] = 
				Celini::link('PNG','MMBarcode','print')."id=$patient_id&cat_id=$cat_id";

			
			$patient_cat_link = $wr . Celini::link('view','PatientDashboard','main')."id=$patient_id&cat_id=$cat_id";
//			echo $patient_cat_link . "<br>";
			$patient_cat_link_enc = urlencode($patient_cat_link);

			$image_array[$name] = WEB_ROOT . "/qr/qr_img.php?d=$patient_cat_link_enc";


			$merged_array[$cat_id] = array(
				'name' => $name,
				'cat_link' => $cat_array[$name],
				'image_link' => $image_array[$name],
			);

		}


		$this->assign('merged_category_array',$merged_array);

/*
		$cat_array = array (

		'Correspondence' => Celini::link('view','MMBarcode','print')."id=$id&cat_id=5000",
		'Imaging' => Celini::link('view','MMBarcode','print')."id=$id&cat_id=6000",
		'Outside_Consultations' => Celini::link('view','MMBarcode','print')."id=$id&cat_id=7000",
		'Paper_Lab_Results' => Celini::link('view','MMBarcode','print')."id=$id&cat_id=8000",
		
				);




		$image_array = array (
		
		'Correspondence' => Celini::link('PNG','MMBarcode','print')."id=$id&cat_id=5000",
		'Imaging' => Celini::link('PNG','MMBarcode','print')."id=$id&cat_id=6000",
                'Outside_Consultations' => Celini::link('PNG','MMBarcode','print')."id=$id&cat_id=7000",
                'Paper_Lab_Results' => Celini::link('PNG','MMBarcode','print')."id=$id&cat_id=8000",

		);
*/
		$this->assign('catagories',$cat_array);
		$this->assign('images',$image_array);
		$this->assign('PRINT_ALL_LINK',Celini::link('view','MMBarcode','print')."id=$patient_id&cat_id=$category_id&print_all=1");


		if(is_numeric($patient_id)){
		//	echo $id;

			$this->assign('PNG_IMG',Celini::link('PNG','MMBarcode','print')."id=$patient_id&cat_id=$category_id");

			$patient =& ORdataObject::factory('Patient',$patient_id);
			$this->assign_by_ref('patient',$patient);			

			$this->assign('PATIENT_LINK',Celini::link('view','PatientDashboard','main')."id=$patient_id");

			$this->assign('SEARCH_LINK',Celini::link('find','PatientFinder','main'));
			$this->assign('MAIN_LABEL',$new_array[$category_id]);
                 


			return($this->view->render("mmbarcode.html")); 
		}else{

			return "error invalid input";
		}

	}


	function actionPNG_view($id) {
	
		if(isset($_GET['cat_id'])){
			$full_id = $id . "-" .$_GET['cat_id'];
		}else{
			$full_id = $id . "-" ."1";
		}

		//	echo $id;
			$this->barcode($full_id);

	}




function _name_recurse($id,$name_array){

        $name = $name_array[$id]['name'];
        if($name_array[$id]['parent'] == 1){
                return($name);
        }else{


        $name_phrase =  $this->_name_recurse($name_array[$id]['parent'],$name_array).
                        " -> $name";

                return($name_phrase);

        }

}



function _process_files(){
	$config = $GLOBALS['config'];
	$starting_dir = $config['mmbarcode_starting_dir'];
	$working_dir = $config['mmbarcode_working_dir'];
	$zxing_dir = $config['zxing_dir'];


	if($source_handle = opendir($starting_dir)){

		while (false !== ($file = readdir($source_handle))) {
       		 	if ($file != "." && $file != "..") {
			
				$old_file = $starting_dir . $file;
				$new_file = $working_dir . $file;
			//	echo "attempting a copy from $old_file to $new_file";
				if(file_exists($new_file)){
					echo "ERROR: a filename like this already exists!!";
				}
				copy($old_file,$new_file);
				$this->_process_one_file($new_file);

			}
	    	}	
	}


        foreach($this->new_file_array as $code => $files){
                $merge_command = 'pdftk ';
                foreach($files as $file){
                        $merge_command .= $file . " ";
                }
                $merge_command .= "cat output /var/www/html/preview/user/documents/$code.pdf";
                //echo "using merge command \n $merge_command";
                exec($merge_command);
        }

}



function _process_one_file($file_name){

	$rand_num = rand(1000,9999);

	$file_results = array();	
	
	$config = $GLOBALS['config'];

	$starting_dir = $config['mmbarcode_starting_dir'];
	$working_dir = $config['mmbarcode_working_dir'];
	$zxing_dir = $config['zxing_dir'];
	chdir($working_dir);
	$split_command = "pdftk $file_name burst  output burst_$rand_num". '_%03d.pdf';
	echo "running \n $split_command \n";
	$result = exec($split_command);
	echo "result was $result <br>";
	passthru('ls -laF',$result);
	echo "result was $result <br>";
	if($working_handle = opendir($working_dir)){
        	while (false !== ($file = readdir($working_handle))) {
                	if ($file != "." && $file != "..") {

				$pos = strpos($file,(string) $rand_num);
			//	echo "testing $file against $rand_num with a result of $pos\n";
               			if($pos > 0){
					$long_name = $working_dir . $file;
					$img_name = $working_dir . $file . '.jpg';
					$convert_command = "convert $long_name $img_name";
			//		echo "running \n $convert_command \n";
					exec($convert_command);				
					chdir($zxing_dir);
			
					$zxing_command = 
			"java -cp /root/zxing/javase/javase.jar:/root/zxing/core/core.jar com.google.zxing.client.j2se.CommandLineRunner $img_name";

					$result = exec($zxing_command);	
					//echo("Zxing result for $file: $result \n");	
				
					$no_image_found_string = "No barcode found";	
					$no_image_pos = strpos($result,$no_image_found_string);

					$could_not_load_string = "Could not load image";	
					$could_not_load_pos = strpos($result,$could_not_load_string);
				
					if($could_not_load_pos > 0){
						// this is not an image
					}

					$new_pdf_filename = '0';

					if($no_image_pos > 0){
					
						//this pdf is content and has no barcode. 
						//echo "no image found for $img_name\n";

					}else{
			//example URL you might find here
			// https://something.example.com/mirrormed/index.php/main/PatientDashboard/view?id=22314703&cat_id=1
						list($url, $args) = explode('?',$result);
						list($id_string,$cat_string) = explode('&',$args);
						list($throw_away,$id) = explode('=',$id_string);
						list($throw_away,$cat) = explode('=',$cat_string);
			
						//echo "\n result $result boils down to id $id and cat $cat\n";
						$new_pdf_filename = $id . "_" . $cat;
						
					}
	
					$file_results[$long_name] = $new_pdf_filename;
	
				}// if pos of rand_num
			}// no . or ..
        	}//while on dir loop


	//	var_export($file_results);


		$count = 1;
		$count_string = str_pad($count,3,'0',STR_PAD_LEFT);
		$file_string = $working_dir . "burst_$rand_num"."_$count_string.pdf";
		while(isset($file_results[$file_string])){
			
			$new_file_code = $file_results[$file_string];
			if($new_file_code !=0){
				$this->new_file_array[$new_file_code][] = $file_string;
				$last_file_code = $new_file_code;
			}else{
				$this->new_file_array[$last_file_code][] = $file_string;
			}



			$count++;
			$count_string = str_pad($count,3,'0',STR_PAD_LEFT);
			$file_string = $working_dir . "burst_$rand_num"."_$count_string.pdf";
		}

	//	echo "new array with gueses on the zeros\n";
	//	var_export($new_file_array);

	//moved to global save...
	/*
	foreach($new_file_array as $code => $files){
		$merge_command = 'pdftk '; 
		foreach($files as $file){
			$merge_command .= $file . " ";
		}
		$merge_command .= "cat output /var/www/html/preview/user/documents/$code.pdf";
		echo "using merge command \n $merge_command";
		exec($merge_command);
	}
	*/


        }// if open the dir




}//function end





/*===========================================================================*/
/*      PHP Barcode Image Generator v1.0 [9/28/2000]
        Copyright (C)2000 by Charles J. Scheffold - cs@sid6581.net


		---
		UPDATE 5/10/2005 by C.Scheffold
		Changed FontHeight to -2 if no text is to be displayed (this eliminates
		the whitespace at the bottom of the image)
		---
		UPDATE 03/12/2005 by C.Scheffold
		Added '-' character to translation table
        ---
        UPDATE 09/21/2002 by Laurent NAVARRO - ln@altidev.com - http://www.altidev.com
        Updated to be compatible with register_globals = off and on
        ---
        UPDATE 4/6/2001 - Important Note! This script was written with the assumption
        that "register_globals = On" is defined in your PHP.INI file! It will not 
        work as-is      and as described unless this is set. My PHP came with this 
        enabled by default, but apparently many people have turned it off. Either 
        turn it on or modify the startup code to pull the CGI variables in the old 
        fashioned way (from the HTTP* arrays). If you just want to use the functions 
        and pass the variables yourself, well then go on with your bad self.
        ---
        
        This code is hereby released into the public domain.
        Use it, abuse it, just don't get caught using it for something stupid.


        The only barcode type currently supported is Code 3 of 9. Don't ask about 
        adding support for others! This is a script I wrote for my own use. I do 
        plan to add more types as time permits but currently I only require 
        Code 3 of 9 for my purposes. Just about every scanner on the market today
        can read it.


        PARAMETERS:
        -----------
        $barcode        = [required] The barcode you want to generate


        $type           = (default=0) It's 0 for Code 3 of 9 (the only one supported)
        
        $width          = (default=160) Width of image in pixels. The image MUST be wide
                                  enough to handle the length of the given value. The default
                                  value will probably be able to display about 6 digits. If you
                                  get an error message, make it wider!


        $height         = (default=80) Height of image in pixels
        
        $format         = (default=jpeg) Can be "jpeg", "png", or "gif"
        
        $quality        = (default=100) For JPEG only: ranges from 0-100


        $text           = (default=1) 0 to disable text below barcode, >=1 to enable


        NOTE: You must have GD-1.8 or higher compiled into PHP
        in order to use PNG and JPEG. GIF images only work with
        GD-1.5 and lower. (http://www.boutell.com)


        ANOTHER NOTE: If you actually intend to print the barcodes 
        and scan them with a scanner, I highly recommend choosing 
        JPEG with a quality of 100. Most browsers can't seem to print 
        a PNG without mangling it beyond recognition. 


        USAGE EXAMPLES FOR ANY PLAIN OLD HTML DOCUMENT:
        -----------------------------------------------


        <IMG SRC="barcode.php?barcode=HELLO&quality=75">


        <IMG SRC="barcode.php?barcode=123456&width=320&height=200">
                
        
*/
/*=============================================================================*/


//-----------------------------------------------------------------------------
// Startup code
//-----------------------------------------------------------------------------

function barcode($barcode){


	if(isset($_GET["text"])) $text=$_GET["text"];
	if(isset($_GET["format"])) $format=$_GET["format"];
	if(isset($_GET["quality"])) $quality=$_GET["quality"];
	if(isset($_GET["width"])) $width=$_GET["width"];
	if(isset($_GET["height"])) $height=$_GET["height"];
	if(isset($_GET["type"])) $type=$_GET["type"];
	if(isset($_GET["barcode"])) $barcode=$_GET["barcode"];




	if (!isset ($text)) $text = 1;
	if (!isset ($type)) $type = 1;
	if (empty ($quality)) $quality = 100;
	if (empty ($width)) $width = 500;
	if (empty ($height)) $height = 300;
	if (!empty ($format)) $format = strtoupper ($format);
        else $format="PNG";


	switch ($type)
	{
        	default:
       	         	$type = 1;
       		case 1:
                	$this->Barcode39 ($barcode, $width, $height, $quality, $format, $text);
                break;          
	}
}//function barcode

//-----------------------------------------------------------------------------
// Generate a Code 3 of 9 barcode
//-----------------------------------------------------------------------------
function Barcode39 ($barcode, $width, $height, $quality, $format, $text)
{
        switch ($format)
        {
                default:
                        $format = "JPEG";
                case "JPEG": 
                        header ("Content-type: image/jpeg");
                        break;
                case "PNG":
                        header ("Content-type: image/png");
                        break;
                case "GIF":
                        header ("Content-type: image/gif");
                        break;
        }


        $im = ImageCreate ($width, $height)
    or die ("Cannot Initialize new GD image stream");
        $White = ImageColorAllocate ($im, 255, 255, 255);
        $Black = ImageColorAllocate ($im, 0, 0, 0);
        //ImageColorTransparent ($im, $White);
        ImageInterLace ($im, 1);



        $NarrowRatio = 20;
        $WideRatio = 55;
        $QuietRatio = 35;


        $nChars = (strlen($barcode)+2) * ((6 * $NarrowRatio) + (3 * $WideRatio) + ($QuietRatio));
        $Pixels = $width / $nChars;
        $NarrowBar = (int)(20 * $Pixels);
        $WideBar = (int)(55 * $Pixels);
        $QuietBar = (int)(35 * $Pixels);


        $ActualWidth = (($NarrowBar * 6) + ($WideBar*3) + $QuietBar) * (strlen ($barcode)+2);
        
        if (($NarrowBar == 0) || ($NarrowBar == $WideBar) || ($NarrowBar == $QuietBar) || ($WideBar == 0) || ($WideBar == $QuietBar) || ($QuietBar == 0))
        {
                ImageString ($im, 1, 0, 0, "Image is too small!", $Black);
                $this->OutputImage ($im, $format, $quality);
                exit;
        }
        
        $CurrentBarX = (int)(($width - $ActualWidth) / 2);
        $Color = $White;
        $BarcodeFull = "*".strtoupper ($barcode)."*";
        settype ($BarcodeFull, "string");
        
        $FontNum = 3;
        $FontHeight = ImageFontHeight ($FontNum);
        $FontWidth = ImageFontWidth ($FontNum);
        if ($text != 0)
        {
                $CenterLoc = (int)(($width-1) / 2) - (int)(($FontWidth * strlen($BarcodeFull)) / 2);
                ImageString ($im, $FontNum, $CenterLoc, $height-$FontHeight, "$BarcodeFull", $Black);
        }
		else
		{
			$FontHeight=-2;
		}


        for ($i=0; $i<strlen($BarcodeFull); $i++)
        {
                $StripeCode = $this->Code39 ($BarcodeFull[$i]);


                for ($n=0; $n < 9; $n++)
                {
                        if ($Color == $White) $Color = $Black;
                        else $Color = $White;


                        switch ($StripeCode[$n])
                        {
                                case '0':
                                        ImageFilledRectangle ($im, $CurrentBarX, 0, $CurrentBarX+$NarrowBar, $height-1-$FontHeight-2, $Color);
                                        $CurrentBarX += $NarrowBar;
                                        break;


	     				case '1':
                                        ImageFilledRectangle ($im, $CurrentBarX, 0, $CurrentBarX+$WideBar, $height-1-$FontHeight-2, $Color);
                                        $CurrentBarX += $WideBar;
                                        break;
                        }
                }


                $Color = $White;
                ImageFilledRectangle ($im, $CurrentBarX, 0, $CurrentBarX+$QuietBar, $height-1-$FontHeight-2, $Color);
                $CurrentBarX += $QuietBar;
        }

        $this->OutputImage ($im, $format, $quality);
}


//-----------------------------------------------------------------------------
// Output an image to the browser
//-----------------------------------------------------------------------------
function OutputImage ($im, $format, $quality)
{
        switch ($format)
        {
                case "JPEG": 
                        ImageJPEG ($im, "", $quality);
                        break;
                case "PNG":
                        ImagePNG ($im);
                        break;
                case "GIF":
                        ImageGIF ($im);
                        break;
        }
}


//-----------------------------------------------------------------------------
// Returns the Code 3 of 9 value for a given ASCII character
//-----------------------------------------------------------------------------
function Code39 ($Asc)
{
        switch ($Asc)
        {
                case ' ':
                        return "011000100";     
                case '$':
                        return "010101000";             
                case '%':
                        return "000101010"; 
                case '*':
                        return "010010100"; // * Start/Stop
                case '+':
                        return "010001010"; 
                case '|':
                        return "010000101"; 
                case '.':
                        return "110000100"; 
                case '/':
                        return "010100010"; 
				case '-':
						return "010000101";
                case '0':
                        return "000110100"; 
                case '1':
                        return "100100001"; 
                case '2':
                        return "001100001"; 
                case '3':
                        return "101100000"; 
                case '4':
                        return "000110001"; 
                case '5':
                        return "100110000"; 
                case '6':
                        return "001110000"; 
                case '7':
                        return "000100101"; 
                case '8':
                        return "100100100"; 
                case '9':
                        return "001100100"; 
                case 'A':
                        return "100001001"; 
                case 'B':
                        return "001001001"; 
                case 'C':
                        return "101001000";
                case 'D':
                        return "000011001";
                case 'E':
                        return "100011000";
                case 'F':
                        return "001011000";
                case 'G':
                        return "000001101";
                case 'H':
                        return "100001100";
                case 'I':
                        return "001001100";
                case 'J':
                        return "000011100";
                case 'K':
                        return "100000011";
                case 'L':
                        return "001000011";
                case 'M':
                        return "101000010";
                case 'N':
                        return "000010011";
                case 'O':
                        return "100010010";
                case 'P':
                        return "001010010";
                case 'Q':
                        return "000000111";
                case 'R':
                        return "100000110";
                case 'S':
                        return "001000110";
                case 'T':
                        return "000010110";
                case 'U':
                        return "110000001";
                case 'V':
                        return "011000001";
                case 'W':
                        return "111000000";
                case 'X':
                        return "010010001";
                case 'Y':
                        return "110010000";
                case 'Z':
                        return "011010000";
                default:
                        return "011000100"; 
        }
}




}
?>
