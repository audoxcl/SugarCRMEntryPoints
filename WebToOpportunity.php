<?php

/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

if(!defined('sugarEntry')) define('sugarEntry', true);

global $db;
global $current_user;
global $app_list_strings;
global $sugar_config;

$current_user->id = 1;

$account_name = $_REQUEST['account_name'];
$account_web = $_REQUEST['account_web'];

$contact_first_name = $_REQUEST['contact_first_name'];
$contact_last_name = $_REQUEST['contact_last_name'];
$contact_email = $_REQUEST['contact_email'];
$contact_mobile = $_REQUEST['contact_mobile'];

$opportunity_name = $_REQUEST['opportunity_name'];
$opportunity_amount = $_REQUEST['opportunity_amount'];

// Search account by url domain and Update it or Create it
$account = new Account();
if(!is_null($account->retrieve_by_string_fields(array('web' => $contact_email)))){
	if(empty($account->name)) 	$account->name = $account_name;
	if(empty($account->url)) 	$account->url = $account_web;
	$account->save();
}
else{
	$account->name = $account_name;
	$account->url = $account_web;
	$account->save();
}

// Search contact by email and Update it or Create it
$contact = new Contact();
if(!is_null($contact->retrieve_by_string_fields(array('email1' => $contact_email)))){
	if(empty($contact->first_name)) $contact->first_name = $contact_first_name;
	if(empty($contact->last_name)) $contact->last_name = $contact_last_name;
	if(empty($contact->mobile)) $contact->mobile = $contact_mobile;
	$contact->save();
}
else{
	$contact->first_name = $contact_first_name;
	$contact->last_name = $contact_last_name;
	$contact->email1 = $contact_email;
	$contact->mobile = $contact_mobile;
	$contact->save();
	$contact->load_relationship('accounts');
	$contact->accounts->add($account->id);
}

// Create the Opportunity
$opportunity = new Opportunity();
$opportunity->name = $opportunity_name;
$opportunity->amount = $opportunity_amount;
$opportunity->date_closed = "";
$opportunity->sales_stage = "Closed Won";
$opportunity->account_id = $account->id;
$opportunity->save();

$opportunity->load_relationship('contacts');
$opportunity->contacts->add($contact->id);

?>