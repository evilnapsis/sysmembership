<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){
	$item = new ClientData();
	$item->addExtraFieldString("name", htmlentities($_POST["name"]));
	$item->addExtraFieldString("lastname", htmlentities($_POST["lastname"]));
	$item->addExtraFieldString("email", htmlentities($_POST["email"]));
	$item->addExtraFieldString("address", htmlentities($_POST["address"]));
	$item->addExtraFieldString("phone", htmlentities($_POST["phone"]));
	$item->add();
	Core::alert("Cliente agregado!");
	Core::redir("./?view=clients&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){
	$item = ClientData::getById($_POST["_id"]);
	$item->addExtraFieldString("name", htmlentities($_POST["name"]));
	$item->addExtraFieldString("lastname", htmlentities($_POST["lastname"]));
	$item->addExtraFieldString("email", htmlentities($_POST["email"]));
	$item->addExtraFieldString("address", htmlentities($_POST["address"]));
	$item->addExtraFieldString("phone", htmlentities($_POST["phone"]));
	$item->update();
	Core::alert("Cliente actualizado!");
	Core::redir("./?view=clients&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$item = ClientData::getById($_GET["id"]);
	$item->del();
	Core::alert("Cliente eliminado!");
	Core::redir("./?view=clients&opt=all");
}
?>
