<?php
$loader->requireOnce("/ordo/ORDataObject.class.php");

/**
 * Controller to hold stuff that a patient can see on thier record
 *
 * Long Description
 * Long Description
 *   
 * @package	ydp
 */
class C_MyDownloads extends Controller {

	/**
	 * Update the password of the currently logged in user
         *
      	 * long comment for the class method below  
	 * 
 	 * @return void
         */
	function actionDownload_view() {
		//$user =& $this->_me->get_user();
	
		$patient_id = $_GET['patient_id'];
		$file = $_GET['file'];

		$file_real = APP_ROOT ."/user/documents/$patient_id/".$file;



		
//The following lines were adopted from the following author... 
  /*****************************************************************
   **                                                             **
   **  Author:  Simon Stenhouse                                   **
   **  Date:    26.11.2005                                        **
   **  Version: 1.2                                               **
   **  Website: http://www.simonstenhouse.net/                    **
   **  License: http://www.gnu.org/licenses/gpl.txt               **
   **                                                             **
   **  ---------------------------------------------------------  **
   **                                                             **/

	if (substr($file, 0, 1) == '.' || strpos($file, '..') > 0 || substr($file, 0, 1) == '/' || strpos($file, '/') > 0){
	//Fred: I removed the record of the Hack Attack... A Cellini based system
	//should go here...

		header("HTTP/1.0 403 Forbidden");
		echo "You cannot access other file systems. IP logged.";
		exit();
	}

//check this implementation after sorting out the referrer system...
//	preg_match("/^(http:\/\/)?([^\/]+)/i", $_SERVER['HTTP_REFERER'], $matches);
//	$referer	= (substr($matches[2], 0, 4) == 'www.') ? substr($matches[2], 4, strlen($matches[2])) : $matches[2];
	$host   	= (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.') ? substr($_SERVER['HTTP_HOST'], 4, strlen($_SERVER['HTTP_HOST'])) : $_SERVER['HTTP_HOST'];

	$referer = $host;// remove me to check referrer checks..

                // LEECH ATTEMPT CHECK
                // If the domain name of the referer does not match the
                // the domain name of your site, then someone has tried
                // to leech one of your files!
        if ($referer != $host)
	{
		//Add direct link leeching here too..
		//
		header("HTTP/1.0 403 Forbidden");
		echo "You cannot access your files via direct linking.";
		exit();
        }	

                if (file_exists($file_real))
                {
                        // Get extension of requested file
                        $extension = strtolower(substr(strrchr($file, "."), 1));

                        // Determine correct MIME type
                        switch($extension)
                        {
                                case "asf":     $type = "video/x-ms-asf";                break;
                                case "avi":     $type = "video/x-msvideo";               break;
                                case "bin":     $type = "application/octet-stream";      break;
                                case "bmp":     $type = "image/bmp";                     break;
                                case "cgi":     $type = "magnus-internal/cgi";           break;
                                case "css":     $type = "text/css";                      break;
                                case "dcr":     $type = "application/x-director";        break;
                                case "dxr":     $type = "application/x-director";        break;
                                case "dll":     $type = "application/octet-stream";      break;
                                case "doc":     $type = "application/msword";            break;
                                case "exe":     $type = "application/octet-stream";      break;
                                case "gif":     $type = "image/gif";                     break;
                                case "gtar":    $type = "application/x-gtar";            break;
                                case "gz":      $type = "application/gzip";              break;
                                case "htm":     $type = "text/html";                     break;
                                case "html":    $type = "text/html";                     break;
                                case "iso":     $type = "application/octet-stream";      break;
                                case "jar":     $type = "application/java-archive";      break;
                                case "java":    $type = "text/x-java-source";            break;
                                case "jnlp":    $type = "application/x-java-jnlp-file";  break;
                                case "js":      $type = "application/x-javascript";      break;
                                case "jpg":     $type = "image/jpg";                     break;
                                case "jpe":     $type = "image/jpg";                     break;
                                case "jpeg":    $type = "image/jpg";                     break;
                                case "lzh":     $type = "application/octet-stream";      break;
                                case "mdb":     $type = "application/mdb";               break;
                                case "mid":     $type = "audio/x-midi";                  break;
                                case "midi":    $type = "audio/x-midi";                  break;
                                case "mov":     $type = "video/quicktime";               break;
                                case "mp2":     $type = "audio/x-mpeg";                  break;
                                case "mp3":     $type = "audio/mpeg";                    break;
                                case "mpg":     $type = "video/mpeg";                    break;
                                case "mpe":     $type = "video/mpeg";                    break;
                                case "mpeg":    $type = "video/mpeg";                    break;
                                case "pdf":     $type = "application/pdf";               break;
                                case "php":     $type = "application/x-httpd-php";       break;
                                case "php3":    $type = "application/x-httpd-php3";      break;
                                case "php4":    $type = "application/x-httpd-php";       break;
                                case "png":     $type = "image/png";                     break;
                                case "ppt":     $type = "application/mspowerpoint";      break;
                                case "qt":      $type = "video/quicktime";               break;
                                case "qti":     $type = "image/x-quicktime";             break;
                                case "rar":     $type = "encoding/x-compress";           break;
                                case "ra":      $type = "audio/x-pn-realaudio";          break;
                                case "rm":      $type = "audio/x-pn-realaudio";          break;
                                case "ram":     $type = "audio/x-pn-realaudio";          break;
                                case "rtf":     $type = "application/rtf";               break;
                                case "swa":     $type = "application/x-director";        break;
                                case "swf":     $type = "application/x-shockwave-flash"; break;
                                case "tar":     $type = "application/x-tar";             break;
                                case "tgz":     $type = "application/gzip";              break;
                                case "tif":     $type = "image/tiff";                    break;
                                case "tiff":    $type = "image/tiff";                    break;
                                case "torrent": $type = "application/x-bittorrent";      break;
                                case "txt":     $type = "text/plain";                    break;
                                case "wav":     $type = "audio/wav";                     break;
                                case "wma":     $type = "audio/x-ms-wma";                break;
                                case "wmv":     $type = "video/x-ms-wmv";                break;
                                case "xls":     $type = "application/xls";               break;
                                case "xml":     $type = "application/xml";               break;
                                case "7z":      $type = "application/x-compress";        break;
                                case "zip":     $type = "application/x-zip-compressed";  break;

                                default:        $type = "application/force-download";    break;
                        }

                        // (we really should) Log the download

                        // Send file for download
                        header("Pragma: public");
                        header("Expires: 0");
                        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                        header("Cache-Control: public", false);
                        header("Content-Description: File Transfer");
                        header("Content-Type: " . $type);
                        header("Accept-Ranges: bytes");
                        header("Content-Disposition: attachment; filename=" . $file . ";");
                        header("Content-Transfer-Encoding: binary");
                        header("Content-Length: " . filesize($file_real));
                        @readfile($file_real);
                }
                else
                {
                        // Requested file does not exist so cause a 404 error (File not found)
			header("HTTP/1.1 404 Not Found");
			echo "File Not Found.";
			exit();
                }



}


	/**
	 * MyDownload does not require login.. .instead it requires a valid token...
	 *
	 * so this always returns false. Allowing anonymous access.
	 * 
	 * @param	string	$controller
	 * @param	string	$action
	 * @return	boolean	true - the user needs to be logged into view this action, false the user doesn't need to be logged in
	 */

	/**	
        * short comment
        *
	* long comment for the class method below 
	*
 	* @param string 
 	* @param string
 	* 
 	* @return boolean
        */

	function requireLogin($controller, $action) {

		//So either you should have a valid login OR you should have a valid token.

		$tokenstring = $_GET['token'];
		$sha1_token = sha1($tokenstring);
		$patient_id = $_GET['patient_id'];
	
		$this->assign('token',$tokenstring);
		$this->assign('sha1_token',$sha1_token);
		$this->assign('patient_id',$patient_id);

		$tokenobject =& ORDataObject::Factory('Token',null);
		$token_valid = $tokenobject->is_valid($patient_id,$sha1_token);

		if($token_valid){//then we have an OK token so we do not need a login.
			return false;
		}else{//we have not token so we need a login
			return true;
		}
	}


}
?>
