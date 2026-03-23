<?php 
if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= UserData::getById($_SESSION["user_id"]);
if($user==null){ Core::redir("./");}
?>
<?php if(isset($_GET["opt"]) && $_GET['opt']=="all"):
$items = ControlData::getAll();
?>
<div class="row">
	<div class="col-md-12">
		<div class="card mb-4">
			<div class="card-header">Control de Acceso</div>
			<div class="card-body">
				<a href="./?view=control&opt=new" class="btn btn-secondary">Nuevo Acceso</a>
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
									<th>Descripción</th>
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
										<td><?php echo $item->description; ?></td>
										<td><?php echo $item->created_at; ?></td>
										<td>
											<a href="./?view=control&opt=edit&id=<?php echo $item->id; ?>" class="btn btn-warning btn-sm"><i class="bi-pencil"></i></a>
											<a href="./?action=control&opt=del&id=<?php echo $item->id; ?>" class="btn btn-danger btn-sm"><i class="bi-trash"></i></a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else:?>
						<p class="alert alert-warning">No hay registros de acceso</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):
?>
<div class="card mb-4">
	<div class="card-header">Control de Acceso</div>
	<div class="card-body">
		<h2>Verificar Acceso</h2>
		<div class="row">
			<div class="col-md-6">
				<form method="post" action="./?action=control&opt=check">
					<div class="form-group">
						<label>ID del Cliente</label>
						<input type="text" name="client_id" class="form-control" placeholder="Ingrese ID del Cliente" required autofocus>
					</div>
					<div class="d-grid gap-2 mt-3">
						<button type="submit" class="btn btn-primary ">Verificar Acceso</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="result"):
$client = isset($_GET["client_id"]) ? ClientData::getById($_GET["client_id"]) : null;
$contract = isset($_GET["contract_id"]) ? ContractData::getById($_GET["contract_id"]) : null;
$success = isset($_GET["status"]) && $_GET["status"]=="ok";
?>
<div class="row">
	<div class="col-md-12">
		<div class="card mb-4 <?php echo $success ? 'border-success' : 'border-danger'; ?>">
			<div class="card-header <?php echo $success ? 'bg-success text-white' : 'bg-danger text-white'; ?>">
				Resultado de Acceso
			</div>
			<div class="card-body text-center">
				<?php if($success): ?>
					<h1 class="display-1 text-success"><i class="bi-check-circle"></i></h1>
					<h2 class="text-success">ACCESO PERMITIDO</h2>
					<p class="lead">Bienvenido(a) <b><?php echo $client->name." ".$client->lastname; ?></b></p>
					<div class="alert alert-success d-inline-block">
						Membresía: <b><?php echo MembershipData::getById($contract->membership_id)->name; ?></b><br>
						Vence: <b><?php echo $contract->finish_at; ?></b>
					</div>
				<?php else: ?>
					<h1 class="display-1 text-danger"><i class="bi-x-circle"></i></h1>
					<h2 class="text-danger">ACCESO DENEGADO</h2>
					<p class="lead">Cliente: <b><?php echo $client ? ($client->name." ".$client->lastname) : "No encontrado"; ?></b></p>
					<div class="alert alert-danger d-inline-block">
						<?php echo $_GET["msg"]; ?>
					</div>
				<?php endif; ?>
				<br><br>
				<a href="./?view=control&opt=new" class="btn btn-secondary">Volver a intentar</a>
			</div>
		</div>
	</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="edit"):
$item = ControlData::getById($_GET["id"]);
?>
<div class="card mb-4">
	<div class="card-header">Control de Acceso</div>
	<div class="card-body">
		<h2>Editar Acceso</h2>
		<div class="row">
			<div class="col-md-6">
				<form method="post" action="./?action=control&opt=update">
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
						<label>Descripción</label>
						<textarea name="description" class="form-control" placeholder="Descripción/Comentario" rows="3"><?php echo $item->description; ?></textarea>
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
