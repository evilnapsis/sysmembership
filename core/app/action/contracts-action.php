<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){
	$status = 0; // bloqueado/pendiente
	if(isset($_POST["payment"]) && $_POST["payment"] > 0){
		$status = 1; // activo
	}

	$item = new ContractData();
	$item->addExtraFieldString("client_id", htmlentities($_POST["client_id"]));
	$item->addExtraFieldString("membership_id", htmlentities($_POST["membership_id"]));
	$item->addExtraFieldString("user_id", $_SESSION["user_id"]);
	$item->addExtraFieldString("start_at", htmlentities($_POST["start_at"]));
	$item->addExtraFieldString("finish_at", htmlentities($_POST["finish_at"]));
	$item->addExtraFieldString("status", $status);
	$c = $item->add();

	if(isset($_POST["payment"]) && $_POST["payment"] > 0){
		$p = new PaymentData();
		$p->addExtraFieldString("client_id", htmlentities($_POST["client_id"]));
		$p->addExtraFieldString("contract_id", $c[1]);
		$p->addExtraFieldString("user_id", $_SESSION["user_id"]);
		$p->addExtraFieldString("amount", htmlentities($_POST["payment"]));
		$p->add();
	}

	Core::alert("Contrato agregado!");
	Core::redir("./?view=contracts&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){
	$item = ContractData::getById($_POST["_id"]);
	$item->addExtraFieldString("client_id", htmlentities($_POST["client_id"]));
	$item->addExtraFieldString("membership_id", htmlentities($_POST["membership_id"]));
	$item->addExtraFieldString("user_id", htmlentities($_POST["user_id"]));
	$item->addExtraFieldString("start_at", htmlentities($_POST["start_at"]));
	$item->addExtraFieldString("finish_at", htmlentities($_POST["finish_at"]));
	$item->addExtraFieldString("status", htmlentities($_POST["status"]));
	$item->update();
	Core::alert("Contrato actualizado!");
	Core::redir("./?view=contracts&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$item = ContractData::getById($_GET["id"]);
	$item->del();
	Core::alert("Contrato eliminado!");
	Core::redir("./?view=contracts&opt=all");
}
?>
