<?php
if(!isset($_SESSION["user_id"])){ Core::redir("./");}

if(isset($_GET["opt"]) && $_GET["opt"]=="contracts"){
    $start = $_GET["sd"];
    $end = $_GET["ed"];
    $items = ContractData::getRange($start, $end);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=contracts_report_'.$start.'_'.$end.'.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('#', 'Cliente', 'Membresia', 'Inicio', 'Fin', 'Estado', 'Registro'));

    foreach($items as $item){
        $client = ClientData::getById($item->client_id);
        $client_name = $client!=null ? $client->name." ".$client->lastname : "";
        $membership = MembershipData::getById($item->membership_id);
        $membership_name = $membership!=null ? $membership->name : "";
        $status = $item->status == 1 ? "Activo" : "Inactivo/Pendiente";
        fputcsv($output, array($item->id, $client_name, $membership_name, $item->start_at, $item->finish_at, $status, $item->created_at));
    }
    fclose($output);
    exit;

} else if(isset($_GET["opt"]) && $_GET["opt"]=="payments"){
    $start = $_GET["sd"];
    $end = $_GET["ed"];
    $items = PaymentData::getRange($start, $end);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=payments_report_'.$start.'_'.$end.'.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('#', 'Cliente', 'Contrato #', 'Monto', 'Registro'));

    foreach($items as $item){
        $client = ClientData::getById($item->client_id);
        $client_name = $client!=null ? $client->name." ".$client->lastname : "";
        fputcsv($output, array($item->id, $client_name, $item->contract_id, $item->amount, $item->created_at));
    }
    fclose($output);
    exit;

} else if(isset($_GET["opt"]) && $_GET["opt"]=="control"){
    $start = $_GET["sd"];
    $end = $_GET["ed"];
    $items = ControlData::getRange($start, $end);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=control_report_'.$start.'_'.$end.'.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('#', 'Cliente', 'Contrato #', 'Descripcion', 'Registro'));

    foreach($items as $item){
        $client = ClientData::getById($item->client_id);
        $client_name = $client!=null ? $client->name." ".$client->lastname : "";
        fputcsv($output, array($item->id, $client_name, $item->contract_id, $item->description, $item->created_at));
    }
    fclose($output);
    exit;
}
?>
