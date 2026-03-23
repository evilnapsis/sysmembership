<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){
	$item = new ControlData();
	$item->addExtraFieldString("client_id", htmlentities($_POST["client_id"]));
	$item->addExtraFieldString("contract_id", htmlentities($_POST["contract_id"]));
	$item->addExtraFieldString("user_id", $_SESSION["user_id"]);
	$item->addExtraFieldString("description", htmlentities($_POST["description"]));
	$item->add();
	Core::alert("Acceso agregado!");
	Core::redir("./?view=control&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="check"){
	$client = ClientData::getById($_POST["client_id"]);
	$today = date("Y-m-d");

	if($client==null){
		Core::redir("./?view=control&opt=result&status=error&msg=El cliente no existe.");
		exit;
	}

	$contracts = ContractData::getAllBy("client_id", $client->id);
	$active_contract = null;

	foreach($contracts as $c){
		if($c->status == 1 && $today >= $c->start_at && $today <= $c->finish_at){
			$active_contract = $c;
			break;
		}
	}

	if($active_contract != null){
		// Registrar acceso exitoso
		$ctrl = new ControlData();
		$ctrl->addExtraFieldString("client_id", $client->id);
		$ctrl->addExtraFieldString("contract_id", $active_contract->id);
		$ctrl->addExtraFieldString("user_id", $_SESSION["user_id"]);
		$ctrl->addExtraFieldString("description", "Acceso Automático - OK");
		$ctrl->add();

		Core::redir("./?view=control&opt=result&status=ok&client_id=".$client->id."&contract_id=".$active_contract->id);
	} else {
		// Acceso denegado: buscar por qué
		$msg = "Sin contrato activo.";
		foreach($contracts as $c){
			if($c->status == 1 && $today > $c->finish_at){
				$msg = "Membresía vencida el ".$c->finish_at;
			}else if($c->status == 0){
				$msg = "Contrato pendiente de pago.";
			}
		}
		Core::redir("./?view=control&opt=result&status=error&client_id=".$client->id."&msg=".$msg);
	}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){
	$item = ControlData::getById($_POST["_id"]);
	$item->addExtraFieldString("client_id", htmlentities($_POST["client_id"]));
	$item->addExtraFieldString("contract_id", htmlentities($_POST["contract_id"]));
	$item->addExtraFieldString("user_id", htmlentities($_POST["user_id"]));
	$item->addExtraFieldString("description", htmlentities($_POST["description"]));
	$item->update();
	Core::alert("Acceso actualizado!");
	Core::redir("./?view=control&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$item = ControlData::getById($_GET["id"]);
	$item->del();
	Core::alert("Acceso eliminado!");
	Core::redir("./?view=control&opt=all");
}
?>
