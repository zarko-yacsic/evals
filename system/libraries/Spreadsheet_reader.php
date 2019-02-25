<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class CI_Spreadsheet_reader {
	public function __construct(){
		include APPPATH . 'third_party/spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
		include APPPATH . 'third_party/spreadsheet-reader-master/SpreadsheetReader.php';
	}
}
?>