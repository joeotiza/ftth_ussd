<?php
ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
// if($action == 'logout2'){
// 	$logout = $crud->logout2();
// 	if($logout)
// 		echo $logout;
// }

if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'save_faq'){
	$save = $crud->save_faq();
	if($save)
		echo $save;
}
if($action == 'save_area'){
	$save = $crud->save_area();
	if($save)
		echo $save;
}
if($action == 'save_location'){
	$save = $crud->save_location();
	if($save)
		echo $save;
}
if($action == 'save_package'){
	$save = $crud->save_package();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'delete_area'){
	$save = $crud->delete_area();
	if($save)
		echo $save;
}
if($action == 'delete_location'){
	$save = $crud->delete_location();
	if($save)
		echo $save;
}
if($action == 'delete_faq'){
	$save = $crud->delete_faq();
	if($save)
		echo $save;
}
if($action == 'delete_package'){
	$save = $crud->delete_package();
	if($save)
		echo $save;
}
if($action == 'delete_customer'){
	$save = $crud->delete_customer();
	if($save)
		echo $save;
}
if($action == 'delete_case'){
	$save = $crud->delete_case();
	if($save)
		echo $save;
}
if($action == 'delete_change'){
	$save = $crud->delete_change();
	if($save)
		echo $save;
}
if($action == 'delete_chat'){
	$save = $crud->delete_chat();
	if($save)
		echo $save;
}
if($action == 'save_project'){
	$save = $crud->save_project();
	if($save)
		echo $save;
}
if($action == 'delete_project'){
	$save = $crud->delete_project();
	if($save)
		echo $save;
}
if($action == 'save_task'){
	$save = $crud->save_task();
	if($save)
		echo $save;
}
if($action == 'delete_task'){
	$save = $crud->delete_task();
	if($save)
		echo $save;
}
if($action == 'save_progress'){
	$save = $crud->save_progress();
	if($save)
		echo $save;
}
if($action == 'delete_progress'){
	$save = $crud->delete_progress();
	if($save)
		echo $save;
}
if($action == 'get_report'){
	$get = $crud->get_report();
	if($get)
		echo $get;
}
ob_end_flush();
?>
