<?php 
if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= UserData::getById($_SESSION["user_id"]);
if($user==null){ Core::redir("./");}
?>
<?php if(isset($_GET["opt"]) && $_GET['opt']=="all"):
$items = ContractData::getAll();
?>
<div class="row">
	<div class="col-md-12">
		<div class="card mb-4">
			<div class="card-header">Contratos</div>
			<div class="card-body">
				<a href="./?view=contracts&opt=new" class="btn btn-secondary">Nuevo Contrato</a>
				<br><br>
				<div class="table-responsive">
					<?php if(count($items)>0):?>
						<table class="table border mb-0">
							<thead class="table-light fw-semibold">
								<tr class="align-middle">
									<th>#</th>
															<th>Cliente</th>
															<th>Membresía</th>
															<th>Usuario</th>
															<th>Fecha de inicio</th>
															<th>Fecha de fin</th>
															<th>Estado</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($items as $item):?>
									<tr>
										<td>#<?php echo $item->id; ?></td>
															<td><?php $client = ClientData::getById($item->client_id); if($client!=null){ echo $client->name." ".$client->lastname; } ?></td>
															<td><?php $membership = MembershipData::getById($item->membership_id); if($membership!=null){ echo $membership->name; } ?></td>
															<td><?php echo $item->user_id; ?></td>
															<td><?php echo $item->start_at; ?></td>
															<td><?php echo $item->finish_at; ?></td>
															<td>
																<?php 
																	if($item->status == 1){ 
																		echo '<span class="badge bg-success">Activo</span>'; 
																	} else if($item->status == 0){ 
																		echo '<span class="badge bg-warning text-dark">Pendiente</span>'; 
																	} else {
																		echo '<span class="badge bg-secondary">'.$item->status.'</span>';
																	}
																?>
															</td>
										<td>
											<a href="./?view=contracts&opt=edit&id=<?php echo $item->id; ?>" class="btn btn-warning btn-sm"><i class="bi-pencil"></i></a>
											<a href="./?action=contracts&opt=del&id=<?php echo $item->id; ?>" class="btn btn-danger btn-sm"><i class="bi-trash"></i></a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else:?>
						<p class="alert alert-warning">No hay Contratos</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):
?>
<div class="card mb-4">
	<div class="card-header">Contratos</div>
	<div class="card-body">
		<h2>Nuevo Contrato</h2>
		<div class="row">
			<div class="col-md-12">
				<form method="post" action="./?action=contracts&opt=add">
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
		<label>Tipo de Membresía</label>
		<?php $memberships = MembershipData::getAll(); ?>
		<select name="membership_id" id="membership_id" class="form-control" required>
			<option value="">-- SELECCIONE --</option>
			<?php foreach($memberships as $membership): ?>
				<option value="<?php echo $membership->id; ?>" data-price="<?php echo $membership->price; ?>"><?php echo $membership->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="form-group">
		<label>Precio</label>
		<input type="text" required name="price" id="price" class="form-control" placeholder="Precio">
	</div>
	<div class="form-group">
		<label>Fecha de inicio</label>
		<input type="text" required name="start_at" value="<?php echo date("Y-m-d"); ?>" class="form-control" placeholder="Fecha de inicio">
	</div>


	<div class="form-group">
		<label>Fecha de fin</label>
		<input type="text" required name="finish_at" value="<?php echo date("Y-m-d", strtotime("+30 days")); ?>" class="form-control" placeholder="Fecha de fin">
	</div>
	<div class="form-group">
		<label>Realizar Pago</label>
		<input type="text" required name="payment" value="0" class="form-control" placeholder="PAgo">
	</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
	$("#membership_id").change(function(){
		var price = $(this).find(':selected').data('price');
		$("#price").val(price);
	});
});
</script>
<br>
					<div class="d-grid gap-2">
						<button type="submit" class="btn btn-primary ">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="edit"):
$item = ContractData::getById($_GET["id"]);
?>
<div class="card mb-4">
	<div class="card-header">Contratos</div>
	<div class="card-body">
		<h2>Editar Contrato</h2>
		<div class="row">
			<div class="col-md-12">
				<form method="post" action="./?action=contracts&opt=update">
					<input type="hidden" name="_id" value="<?php echo $item->id; ?>">
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
		<label>Membresía</label>
		<?php $memberships = MembershipData::getAll(); ?>
		<select name="membership_id" class="form-control" required>
			<option value="">-- SELECCIONE --</option>
			<?php foreach($memberships as $membership): ?>
				<option value="<?php echo $membership->id; ?>" <?php if($membership->id==$item->membership_id){ echo "selected"; } ?>><?php echo $membership->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="form-group">
		<label>Usuario</label>
		<input type="hidden" name="user_id" value="<?php echo $item->user_id; ?>">
		<span class="form-control" style="background-color: #e9ecef;">
			<?php $u = UserData::getById($item->user_id); if($u!=null){ echo $u->name." ".$u->lastname; } ?>
		</span>
	</div>
	<div class="form-group">
		<label>Fecha de inicio</label>
		<input type="text" required name="start_at" value="<?php echo $item->start_at; ?>" class="form-control" placeholder="Fecha de inicio">
	</div>
	<div class="form-group">
		<label>Fecha de fin</label>
		<input type="text" required name="finish_at" value="<?php echo $item->finish_at; ?>" class="form-control" placeholder="Fecha de fin">
	</div>
	<div class="form-group">
		<label>Estado</label>
		<select name="status" class="form-control" required>
			<option value="1" <?php if($item->status==1){ echo "selected"; } ?>>Activo</option>
			<option value="0" <?php if($item->status==0){ echo "selected"; } ?>>Inactivo</option>
		</select>
	</div>
					<div class="d-grid gap-2">
						<button type="submit" class="btn btn-success ">Actualizar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
