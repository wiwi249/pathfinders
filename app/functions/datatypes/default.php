<?php

//TEXT TYPE
function FieldTextValue($field, $data) {
	return $data;
}

function FieldTextInput($name, $value = "") {
	global $view;
	$g['value'] = $value;
	return $view->getTemplate('dfield-text-input', $g);
}

//DROPDOWN TYPE
function FieldDropdownValue($field, $data) {
	global $db;
	$query = $db->MakeQuery("SELECT name FROM fields_values WHERE value='".$data."' AND field='".$field."';");
	$val = $db->FetchRes($query);
	
	return $val['name'];
}

function FieldDropdownInput($name, $value) {
	global $view, $db;
	
	$query = $db->MakeQuery("SELECT v.name, v.value FROM `fields_values` v JOIN `fields_def` d ON v.id = d.id WHERE d.`name` = '".$name."';");
	while($row = $db->NumRows($query)) {
		if($row['value'] == $value)
			$row['selected'] = " selected";
			
		else 
			$row['selected'] = "";
		
		$g['options'] .= getTemplate("dfield-dropdown-option",$row);
	}
	
	$g['name'] = $name;
	
	return $view->getTemplate("dfield-dropdown-input", $g);
}

//DATE TYPE
function FieldDateValue($field, $data) {
	return $data;
}

function FieldDateInput($name, $value) {
	global $view;
	
	$vals['name'] = $name;
	$vals['value'] = $value;
	
	$view->getTemplate('dfield-date-input', $vals);
	
}

//LONGTEXT TYPE
function FieldLongTextValue($data) {
	return $data;
}

function FieldLongTextInput($name, $value) {
	return "<textarea name='$name'>".$value."</textarea>";
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


