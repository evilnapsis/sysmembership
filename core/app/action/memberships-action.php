<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){
	$item = new MembershipData();
	$item->addExtraFieldString("name", htmlentities($_POST["name"]));
	$item->addExtraFieldString("description", htmlentities($_POST["description"]));
	$item->addExtraFieldString("duration", htmlentities($_POST["duration"]));
	$item->addExtraFieldString("price", htmlentities($_POST["price"]));
	$item->add();
	Core::alert("Membresia agregado!");
	Core::redir("./?view=memberships&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){
	$item = MembershipData::getById($_POST["_id"]);
	$item->addExtraFieldString("name", htmlentities($_POST["name"]));
	$item->addExtraFieldString("description", htmlentities($_POST["description"]));
	$item->addExtraFieldString("duration", htmlentities($_POST["duration"]));
	$item->addExtraFieldString("price", htmlentities($_POST["price"]));
	$item->update();
	Core::alert("Membresia actualizado!");
	Core::redir("./?view=memberships&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$item = MembershipData::getById($_GET["id"]);
	$item->del();
	Core::alert("Membresia eliminado!");
	Core::redir("./?view=memberships&opt=all");
}
?>
