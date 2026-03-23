<?php
$start = isset($_GET["sd"]) ? $_GET["sd"] : date("Y-m-d");
$end = isset($_GET["ed"]) ? $_GET["ed"] : date("Y-m-d");
$items = PaymentData::getRange($start, $end);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">Reporte de Pagos</div>
            <div class="card-body">
                <form class="row g-3" method="get">
                    <input type="hidden" name="view" value="paymentsreport">
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
                        <a href="./?action=report&opt=payments&sd=<?php echo $start; ?>&ed=<?php echo $end; ?>" class="btn btn-success">Descargar CSV</a>
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
                                    <th>Contrato #</th>
                                    <th>Monto</th>
                                    <th>Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0;
                                foreach($items as $item):
                                $total += $item->amount;
                                ?>
                                    <tr>
                                        <td>#<?php echo $item->id; ?></td>
                                        <td><?php $client = ClientData::getById($item->client_id); if($client!=null){ echo $client->name." ".$client->lastname; } ?></td>
                                        <td>#<?php echo $item->contract_id; ?></td>
                                        <td>$<?php echo number_format($item->amount,2); ?></td>
                                        <td><?php echo $item->created_at; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="3" class="text-end"><b>TOTAL</b></td>
                                    <td><b>$<?php echo number_format($total,2); ?></b></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else:?>
                        <p class="alert alert-warning">No hay Pagos en el rango seleccionado</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
