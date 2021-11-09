<?php
class portfolio_category
{
	public $tableColums = null;
	function __construct()
	{
		  
	}
		
	public function getTableElements()
	{		
				
		$tableElements["id"] = array("key" =>1, "type" =>"int(11)",  "null" =>"NO");		
		$tableElements["category"] = array("key" =>0, "type" =>"varchar(255)",  "null" =>"NO");
		
		return $tableElements;		
	}
}
?>