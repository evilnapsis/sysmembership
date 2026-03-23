<?php
$start = isset($_GET["sd"]) ? $_GET["sd"] : date("Y-m-d");
$end = isset($_GET["ed"]) ? $_GET["ed"] : date("Y-m-d");
$items = ContractData::getRange($start, $end);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">Reporte de Contratos</div>
            <div class="card-body">
                <form class="row g-3" method="get">
                    <input type="hidden" name="view" value="contractsreport">
                    <div class="col-md-3">
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" name="sd" value="<?php echo $start; ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" name="ed" value="<?php echo $end; ?>" class="form-control">
                    </div>
                    <div class="col-md-4 align-self-end">
                        <button type="submit" class="btn btn-primary">Consultar</button>
                        <?php if(count($items)>0):?>
                        <a href="./?action=report&opt=contracts&sd=<?php echo $start; ?>&ed=<?php echo $end; ?>" class="btn btn-success">Descargar CSV</a>
                        <?php endif; ?>
                    </div>
                </form>
                <br>
                <div class="table-responsive">
                    <?php if(count($items)>0):?>
                        <table class="table border mb-0">
                            <thead class="table-light fw-semibold">
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Membresía</th>
                                    <th>Fecha de inicio</th>
                                    <th>Fecha de fin</th>
                                    <th>Estado</th>
                                    <th>Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($items as $item):?>
                                    <tr>
                                        <td>#<?php echo $item->id; ?></td>
                                        <td><?php $client = ClientData::getById($item->client_id); if($client!=null){ echo $client->name." ".$client->lastname; } ?></td>
                                        <td><?php $membership = MembershipData::getById($item->membership_id); if($membership!=null){ echo $membership->name; } ?></td>
                                        <td><?php echo $item->start_at; ?></td>
                                        <td><?php echo $item->finish_at; ?></td>
                                        <td>
                                            <?php 
                                                if($item->status == 1){ echo '<span class="badge bg-success">Activo</span>'; } 
                                                else if($item->status == 0){ echo '<span class="badge bg-warning text-dark">Pendiente</span>'; } 
                                                else { echo '<span class="badge bg-secondary">'.$item->status.'</span>'; }
                                            ?>
                                        </td>
                                        <td><?php echo $item->created_at; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else:?>
                        <p class="alert alert-warning">No hay Contratos en el rango seleccionado</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
