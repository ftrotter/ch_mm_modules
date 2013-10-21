<?php





class MedManParser
{
   var $fp;
   var $delimiter;
   var $inner_delimiter;
   var $length;
   //--------------------------------------------------------------------
   function MedManParser($file_name, $parse_header=false, 
			$delimiter=",", $length=8000,$inner_delimiter = "\"")
   {
	//echo "opening $file_name in parser";
       $this->fp = fopen($file_name, "r");
       $this->delimiter = $delimiter;
       $this->inner_delimiter = $inner_delimiter;
       $this->length = $length;
       //$this->lines = $lines;

	//get the first line of the MedMan file...
       $throw_away = fgetcsv($this->fp, $this->length, $this->delimiter);

   }
   //--------------------------------------------------------------------
   function close_file()
   {
       if ($this->fp)
       {
           fclose($this->fp);
       }
   }
   //--------------------------------------------------------------------
   function get($max_lines=0)
   {
       //if $max_lines is set to 0, then get all the data

       $data = array();

       if ($max_lines > 0)
           $line_count = 0;
       else
           $line_count = -1; // so loop limit is ignored

	$lines_left = false;


       while ($line_count < $max_lines && ($row = fgetcsv($this->fp, 
							$this->length, 
							$this->delimiter,
							$this->inner_delimiter)) !== FALSE)
       {

		$lines_left = true;
	//	var_export($row);
               $data[] = $row;

           if ($max_lines > 0)
               $line_count++;
       }
	if($lines_left){
	       return $data;
	}else{
		return false;
	}

   }
   //--------------------------------------------------------------------

}

/*Here is a OOP based importer similar to the one posted earlier. However, this is slightly more flexible in that you can import huge files without running out of memory, you just have to use a limit on the get() method

FRED: The original version of this is php5... I have back ported it to php4 

Sample usage for small files:-
-------------------------------------
$importer = new CsvImporter("small.txt",true);
$data = $importer->get();
print_r($data);

Sample usage for large files:-
-------------------------------------
$importer = new CsvImporter("large.txt",true);
while($data = $importer->get(2000))
{
print_r($data);
}
$importer->close_file();
And heres the class:-
-------------------------------------
*/



?>
