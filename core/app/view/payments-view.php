<?php 
if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= UserData::getById($_SESSION["user_id"]);
if($user==null){ Core::redir("./");}
?>
<?php if(isset($_GET["opt"]) && $_GET['opt']=="all"):
$items = PaymentData::getAll();
?>
<div class="row">
	<div class="col-md-12">
		<div class="card mb-4">
			<div class="card-header">Pagos</div>
			<div class="card-body">
				<a href="./?view=payments&opt=new" class="btn btn-secondary">Nuevo Pago</a>
				<br><br>
				<div class="table-responsive">
					<?php if(count($items)>0):?>
						<table class="table border mb-0">
							<thead class="table-light fw-semibold">
								<tr class="align-middle">
									<th>#</th>
									<th>Cliente</th>
									<th>Contrato</th>
									<th>Usuario</th>
									<th>Monto</th>
									<th>Fecha</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($items as $item):?>
									<tr>
										<td>#<?php echo $item->id; ?></td>
										<td><?php $c = ClientData::getById($item->client_id); if($c!=null){ echo $c->name." ".$c->lastname; } ?></td>
										<td>#<?php echo $item->contract_id; ?></td>
										<td><?php $u = UserData::getById($item->user_id); if($u!=null){ echo $u->name." ".$u->lastname; } ?></td>
										<td>$ <?php echo number_format($item->amount,2)."."; ?></td>
										<td><?php echo $item->created_at; ?></td>
										<td>
											<a href="./?view=payments&opt=edit&id=<?php echo $item->id; ?>" class="btn btn-warning btn-sm"><i class="bi-pencil"></i></a>
											<a href="./?action=payments&opt=del&id=<?php echo $item->id; ?>" class="btn btn-danger btn-sm"><i class="bi-trash"></i></a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else:?>
						<p class="alert alert-warning">No hay Pagos</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):
?>
<div class="card mb-4">
	<div class="card-header">Pagos</div>
	<div class="card-body">
		<h2>Nuevo Pago</h2>
		<div class="row">
			<div class="col-md-6">
				<form method="post" action="./?action=payments&opt=add">
					<input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"]; ?>">

					<div class="form-group">
						<label>Cliente</label>
						<?php $clients = ClientData::getAll(); ?>
						<select name="client_id" class="form-control" required>
							<option value="">-- SELECCIONE --</option>
							<?php foreach($clients as $client): ?>
								<option value="<?php echo $client->id; ?>"><?php echo $client->name." ".$client->lastname; ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label>Contrato</label>
						<?php $contracts = ContractData::getAll(); ?>
						<select name="contract_id" class="form-control" required>
							<option value="">-- SELECCIONE --</option>
							<?php foreach($contracts as $contract): 
								$c = ClientData::getById($contract->client_id);
								$m = MembershipData::getById($contract->membership_id);
							?>
								<option value="<?php echo $contract->id; ?>">Contrato #<?php echo $contract->id; ?> - <?php echo $c->name; ?> (<?php echo $m->name; ?>)</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label>Monto</label>
						<input type="text" required name="amount" class="form-control" placeholder="Monto">
					</div>

					<div class="d-grid gap-2 mt-3">
						<button type="submit" class="btn btn-primary ">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="edit"):
$item = PaymentData::getById($_GET["id"]);
?>
<div class="card mb-4">
	<div class="card-header">Pagos</div>
	<div class="card-body">
		<h2>Editar Pago</h2>
		<div class="row">
			<div class="col-md-6">
				<form method="post" action="./?action=payments&opt=update">
					<input type="hidden" name="_id" value="<?php echo $item->id; ?>">
					<input type="hidden" name="user_id" value="<?php echo $item->user_id; ?>">
					<div class="form-group">
						<label>Usuario</label>
						<span class="form-control" style="background-color: #e9ecef;">
							<?php $u = UserData::getById($item->user_id); if($u!=null){ echo $u->name." ".$u->lastname; } ?>
						</span>
					</div>

					<div class="form-group">
						<label>Cliente</label>
						<?php $clients = ClientData::getAll(); ?>
						<select name="client_id" class="form-control" required>
							<option value="">-- SELECCIONE --</option>
							<?php foreach($clients as $client): ?>
								<option value="<?php echo $client->id; ?>" <?php if($client->id==$item->client_id){ echo "selected"; } ?>><?php echo $client->name." ".$client->lastname; ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label>Contrato</label>
						<?php $contracts = ContractData::getAll(); ?>
						<select name="contract_id" class="form-control" required>
							<option value="">-- SELECCIONE --</option>
							<?php foreach($contracts as $contract): 
								$c = ClientData::getById($contract->client_id);
								$m = MembershipData::getById($contract->membership_id);
							?>
								<option value="<?php echo $contract->id; ?>" <?php if($contract->id==$item->contract_id){ echo "selected"; } ?>>Contrato #<?php echo $contract->id; ?> - <?php echo $c->name; ?> (<?php echo $m->name; ?>)</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label>Monto</label>
						<input type="text" required name="amount" value="<?php echo $item->amount; ?>" class="form-control" placeholder="Monto">
					</div>

					<div class="d-grid gap-2 mt-3">
						<button type="submit" class="btn btn-success ">Actualizar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
