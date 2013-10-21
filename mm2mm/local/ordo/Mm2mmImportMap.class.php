<?php
/**
 * Object Relational Persistence Mapping Class for table: mm2mm_import_map
 *
 * @package	com.uversainc.celini
 * @author	Uversa Inc.
 */
class Mm2mmImportMap extends ORDataObject {

	/**#@+
	 * Fields of table: mm2mm_import_map mapped to class members
	 */
	var $mm2mm_import_map_id	= '';
	var $medman_id		= '';
	var $medman_file		= '';
	var $medman_field_group		= '';
	var $mirrormed_id		= '';
	var $mirrormed_table		= '';
	var $data_crc		= '';
	var $update_date		= '';
	var $initial_import_date		= '';
	/**#@-*/


	/**
	 * DB Table
	 */
	var $_table = 'mm2mm_import_map';

	/**
	 * Primary Key
	 */
	var $_key = 'mm2mm_import_map_id';
	
	/**
	 * Internal Name
	 */
	var $_internalName = 'Mm2mmImportMap';

	/**
	 * Handle instantiation
	 */
	function Mm2mmImportMap() {
		parent::ORDataObject();
	}



	
}
?>
