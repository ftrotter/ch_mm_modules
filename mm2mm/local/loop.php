<?php

$myFile = "importlog.txt";
$fh = fopen($myFile, 'w') or die("can't open file");

if ($handle = opendir('pat/')) {
    echo "Directory handle: $handle\n";
    echo "Files:\n";

    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {
	if(strstr($file,'patfile_') !== false){

        	system("php load.php pat/$file");
        	system("php load.php pat/$file");
        	system("php load.php pat/$file");
        	system("php load.php pat/$file");
			
		fwrite($fh, "imported $file at ".date("H:i:s"));
	}
    }
}

fclose($fh);

?>
