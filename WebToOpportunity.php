<?php

/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

// http://<your-crm-url>/index.php?entryPoint=WebToOpportunity&account_name=Audox Ingeniería Ltda.&account_website=www.audox.cl&contact_first_name=Javier&contact_last_name=Núñez&contact_email=janunez@audox.cl&contact_mobile=+56 9 9675 0572&opportunity_name=CRM Consulting Services&opportunity_amount=2000

if(!defined('sugarEntry')) define('sugarEntry', true);

global $db;
global $current_user;

$timeDate = new TimeDate();

$current_user->id = 1;

$account_name = $_REQUEST['account_name'];
$account_website = $_REQUEST['account_website'];

$contact_first_name = $_REQUEST['contact_first_name'];
$contact_last_name = $_REQUEST['contact_last_name'];
$contact_email = $_REQUEST['contact_email'];
$contact_mobile = $_REQUEST['contact_mobile'];

$opportunity_name = $_REQUEST['opportunity_name'];
$opportunity_amount = $_REQUEST['opportunity_amount'];

// Search account by url domain and Update it or Create it
$account = new Account();
if(!is_null($account->retrieve_by_string_fields(array('name' => $account_name)))){
	if(empty($account->name)) $account->name = $account_name;
	if(empty($account->website)) $account->website = $account_website;
	if(empty($account->assigned_user_id)) $account->assigned_user_id = 1;
	$account->save();
}
else{
	$account->name = $account_name;
	$account->website = $account_website;
	$account->assigned_user_id = 1;
	$account->save();
}

// Search contact by email and Update it or Create it
$query = "SELECT contacts.id FROM contacts WHERE contacts.deleted=0 AND contacts.id IN (
	SELECT eabr.bean_id
	FROM email_addr_bean_rel eabr JOIN email_addresses ea
	ON (ea.id = eabr.email_address_id)
	WHERE eabr.bean_module = 'Contacts' AND ea.email_address = '".$contact_email."' AND eabr.primary_address = 1 AND eabr.deleted=0)";
$result = $db->query($query);
$row = $db->fetchByAssoc($result);
$contact = new Contact();
if(!is_null($contact->retrieve($row['id']))){
	if(empty($contact->first_name)) $contact->first_name = $contact_first_name;
	if(empty($contact->last_name)) $contact->last_name = $contact_last_name;
	if(empty($contact->mobile)) $contact->mobile = $contact_mobile;
	if(empty($contact->assigned_user_id)) $contact->assigned_user_id = 1;
	$contact->save();
}
else{
	$contact->first_name = $contact_first_name;
	$contact->last_name = $contact_last_name;
	$contact->email1 = $contact_email;
	$contact->mobile = $contact_mobile;
	$contact->assigned_user_id = 1;
	$contact->save();
	$contact->load_relationship('accounts');
	$contact->accounts->add($account->id);
}

// Create the Opportunity
$opportunity = new Opportunity();
$opportunity->name = $opportunity_name;
$opportunity->amount = $opportunity_amount;
$opportunity->date_closed = $timeDate->getNow(true)->asDbDate();
$opportunity->sales_stage = "Closed Won";
$opportunity->account_id = $account->id;
$opportunity->assigned_user_id = 1;
$opportunity->save();
$opportunity->load_relationship('contacts');
$opportunity->contacts->add($contact->id);

?>