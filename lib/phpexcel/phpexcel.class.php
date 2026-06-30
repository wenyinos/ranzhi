<?php
/**
 * PHPExcel compatibility bridge for PhpSpreadsheet.
 * Maps old PHPExcel_* class names to PhpOffice\PhpSpreadsheet equivalents.
 */
require_once dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php';

/* Main classes */
class_alias('PhpOffice\\PhpSpreadsheet\\Spreadsheet', 'PHPExcel');
class_alias('PhpOffice\\PhpSpreadsheet\\IOFactory', 'PHPExcel_IOFactory');

/* Style classes */
class_alias('PhpOffice\\PhpSpreadsheet\\Style\\Style', 'PHPExcel_Style');
class_alias('PhpOffice\\PhpSpreadsheet\\Style\\Alignment', 'PHPExcel_Style_Alignment');
class_alias('PhpOffice\\PhpSpreadsheet\\Style\\Border', 'PHPExcel_Style_Border');
class_alias('PhpOffice\\PhpSpreadsheet\\Style\\Fill', 'PHPExcel_Style_Fill');

/* NumberFormat: extend to add missing FORMAT_DATE_YYYYMMDD2 constant */
class PHPExcel_Style_NumberFormat extends PhpOffice\PhpSpreadsheet\Style\NumberFormat
{
    const FORMAT_DATE_YYYYMMDD2 = 'yyyy-mm-dd';
}

/* Cell classes */
class_alias('PhpOffice\\PhpSpreadsheet\\Cell\\DataType', 'PHPExcel_Cell_DataType');
class_alias('PhpOffice\\PhpSpreadsheet\\Cell\\DataValidation', 'PHPExcel_Cell_DataValidation');

/* Reader classes */
class_alias('PhpOffice\\PhpSpreadsheet\\Reader\\Xlsx', 'PHPExcel_Reader_Excel2007');
class_alias('PhpOffice\\PhpSpreadsheet\\Reader\\Xls', 'PHPExcel_Reader_Excel5');
