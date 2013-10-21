<?php
/**
 * This is skypestatus.php
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
 * 
 */
    $u = $_GET['u'];
    $path="http://mystatus.skype.com/smallicon/".$u;
    $im     = imagecreatefrompng($path);//.
    header('Content-type: image/png');
    imagepng($im,NULL,0,NULL);
    imagedestroy($im);
?>
