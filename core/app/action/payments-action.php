<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){
	$contract = ContractData::getById($_POST["contract_id"]);
	if($contract != null && $contract->status == 1){
		Core::alert("El contrato ya está pagado/activo!");
		Core::redir("./?view=payments&opt=all");
		exit;
	}

	$item = new PaymentData();
	$item->addExtraFieldString("client_id", htmlentities($_POST["client_id"]));
	$item->addExtraFieldString("contract_id", htmlentities($_POST["contract_id"]));
	$item->addExtraFieldString("user_id", $_SESSION["user_id"]);
	$item->addExtraFieldString("amount", htmlentities($_POST["amount"]));
	$item->add();

	if($contract != null){
		$contract->addExtraFieldString("status", 1);
		$contract->update();
	}

	Core::alert("Pago agregado!");
	Core::redir("./?view=payments&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){
	$item = PaymentData::getById($_POST["_id"]);
	$item->addExtraFieldString("client_id", htmlentities($_POST["client_id"]));
	$item->addExtraFieldString("contract_id", htmlentities($_POST["contract_id"]));
	$item->addExtraFieldString("user_id", htmlentities($_POST["user_id"]));
	$item->addExtraFieldString("amount", htmlentities($_POST["amount"]));
	$item->update();
	Core::alert("Pago actualizado!");
	Core::redir("./?view=payments&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$item = PaymentData::getById($_GET["id"]);
	$item->del();
	Core::alert("Pago eliminado!");
	Core::redir("./?view=payments&opt=all");
}
?>
