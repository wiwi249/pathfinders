<?php


//TEXT TYPE
function FieldTextValue($data) {
	return $data;
}

function FieldTextInput($name, $value = "") {
	global $view;
	$g['value'] = $value;
	return $view->getTemplate('dfield-text-input', $g);
}

//DROPDOWN TYPE
function FieldDropdownValue($data) {
	global $db;
	$query = $db->MakeQuery("SELECT value FROM field_values WHERE name='".$data."';");
	$val = $db->FetchRes($query);
	
	return $val['value'];
}

function FieldDropdownInput($name, $value) {
	global $view, $db;
	
	$query = $db->MakeQuery("SELECT v.value FROM `fields_values` v JOIN `fields_def` d ON v.id = d.id WHERE d.`name` = '".$name."';");
	while($row = $db->NumRows($query)) {
		if($row['value'] == $value)
			$row['selected'] = "selected";
			
		else 
			$row['selected'] = "";
		
		$g['options'] .= getTemplate("dfield-dropdown-option",$row);
	}
	
	$g['name'] = $name;
	
	return $view->getTemplate("dfield-dropdown-input", $g);
}

//DATE TYPE
function FieldDateValue($data) {
	return $data;
}

function FieldDateInput($data) {
	
	
	
}

//LONGTEXT TYPE
function FieldLongTextValue($data) {
	return $data;
}

function FieldLongTextInput($data) {
	return "<textarea>".$data."</textarea>";
}

//CHECKBOX TYPE
function FieldCheckBoxValue($data) {
	if($data === "1") {
		return "Tak";
	}
	
	else if ($data === "0")
		return "Nie";
	
	return "";
}

function FieldCheckBoxInput($name, $data) {
	return "<input type='checkbox' name='".$name."' ".($data === "1" ? "checked" : "")." />";
}

//CHECKBOXGROUP TYPE - ?
function FieldCheckBoxGroupValue($data) {
	
}

function FieldCheckBoxGroupInput($name,$data) {
	
}


