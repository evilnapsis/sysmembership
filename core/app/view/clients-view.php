<?php 
if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= UserData::getById($_SESSION["user_id"]);
if($user==null){ Core::redir("./");}
?>
<?php if(isset($_GET["opt"]) && $_GET['opt']=="all"):
$items = ClientData::getAll();
?>
<div class="row">
	<div class="col-md-12">
		<div class="card mb-4">
			<div class="card-header">Clientes</div>
			<div class="card-body">
				<a href="./?view=clients&opt=new" class="btn btn-secondary">Nuevo Cliente</a>
				<br><br>
				<div class="table-responsive">
					<?php if(count($items)>0):?>
						<table class="table border mb-0">
							<thead class="table-light fw-semibold">
								<tr class="align-middle">
									<th>#</th>
															<th>Nombre</th>
															<th>Apellidos</th>
															<th>Correo</th>
															<th>Dirección</th>
															<th>Teléfono</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($items as $item):?>
									<tr>
										<td>#<?php echo $item->id; ?></td>
															<td><?php echo $item->name; ?></td>
															<td><?php echo $item->lastname; ?></td>
															<td><?php echo $item->email; ?></td>
															<td><?php echo $item->address; ?></td>
															<td><?php echo $item->phone; ?></td>
										<td>
											<a href="./?view=clients&opt=edit&id=<?php echo $item->id; ?>" class="btn btn-warning btn-sm"><i class="bi-pencil"></i></a>
											<a href="./?action=clients&opt=del&id=<?php echo $item->id; ?>" class="btn btn-danger btn-sm"><i class="bi-trash"></i></a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else:?>
						<p class="alert alert-warning">No hay Clientes</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):
?>
<div class="card mb-4">
	<div class="card-header">Clientes</div>
	<div class="card-body">
		<h2>Nuevo Cliente</h2>
		<div class="row">
			<div class="col-md-6">
				<form method="post" action="./?action=clients&opt=add">
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" required name="name" class="form-control" placeholder="Nombre">
	</div>
	<div class="form-group">
		<label>Apellidos</label>
		<input type="text" required name="lastname" class="form-control" placeholder="Apellidos">
	</div>
	<div class="form-group">
		<label>Correo</label>
		<input type="text" required name="email" class="form-control" placeholder="Correo">
	</div>
	<div class="form-group">
		<label>Dirección</label>
		<input type="text" required name="address" class="form-control" placeholder="Dirección">
	</div>
	<div class="form-group">
		<label>Teléfono</label>
		<input type="text" required name="phone" class="form-control" placeholder="Teléfono">
	</div>
					<div class="d-grid gap-2">
						<button type="submit" class="btn btn-primary ">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="edit"):
$item = ClientData::getById($_GET["id"]);
?>
<div class="card mb-4">
	<div class="card-header">Clientes</div>
	<div class="card-body">
		<h2>Editar Cliente</h2>
		<div class="row">
			<div class="col-md-6">
				<form method="post" action="./?action=clients&opt=update">
					<input type="hidden" name="_id" value="<?php echo $item->id; ?>">
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" required name="name" value="<?php echo $item->name; ?>" class="form-control" placeholder="Nombre">
	</div>
	<div class="form-group">
		<label>Apellidos</label>
		<input type="text" required name="lastname" value="<?php echo $item->lastname; ?>" class="form-control" placeholder="Apellidos">
	</div>
	<div class="form-group">
		<label>Correo</label>
		<input type="text" required name="email" value="<?php echo $item->email; ?>" class="form-control" placeholder="Correo">
	</div>
	<div class="form-group">
		<label>Dirección</label>
		<input type="text" required name="address" value="<?php echo $item->address; ?>" class="form-control" placeholder="Dirección">
	</div>
	<div class="form-group">
		<label>Teléfono</label>
		<input type="text" required name="phone" value="<?php echo $item->phone; ?>" class="form-control" placeholder="Teléfono">
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
