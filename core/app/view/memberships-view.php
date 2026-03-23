<?php 
if(!isset($_SESSION["user_id"])){ Core::redir("./");}
$user= UserData::getById($_SESSION["user_id"]);
if($user==null){ Core::redir("./");}
?>
<?php if(isset($_GET["opt"]) && $_GET['opt']=="all"):
$items = MembershipData::getAll();
?>
<div class="row">
	<div class="col-md-12">
		<div class="card mb-4">
			<div class="card-header">Membresias</div>
			<div class="card-body">
				<a href="./?view=memberships&opt=new" class="btn btn-secondary">Nueva Membresía</a>
				<br><br>
				<div class="table-responsive">
					<?php if(count($items)>0):?>
						<table class="table border mb-0">
							<thead class="table-light fw-semibold">
								<tr class="align-middle">
									<th>#</th>
															<th>Nombre</th>
															<th>Descripción</th>
															<th>Duración</th>
															<th>Precio</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($items as $item):?>
									<tr>
										<td>#<?php echo $item->id; ?></td>
															<td><?php echo $item->name; ?></td>
															<td><?php echo $item->description; ?></td>
															<td><?php echo $item->duration; ?></td>
															<td><?php echo $item->price; ?></td>
										<td>
											<a href="./?view=memberships&opt=edit&id=<?php echo $item->id; ?>" class="btn btn-warning btn-sm"><i class="bi-pencil"></i></a>
											<a href="./?action=memberships&opt=del&id=<?php echo $item->id; ?>" class="btn btn-danger btn-sm"><i class="bi-trash"></i></a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else:?>
						<p class="alert alert-warning">No hay Membresias</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php elseif(isset($_GET["opt"]) && $_GET["opt"]=="new"):
?>
<div class="card mb-4">
	<div class="card-header">Membresias</div>
	<div class="card-body">
		<h2>Nueva Membresía</h2>
		<div class="row">
			<div class="col-md-6">
				<form method="post" action="./?action=memberships&opt=add">
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" required name="name" class="form-control" placeholder="Nombre">
	</div>
	<div class="form-group">
		<label>Descripción</label>
		<input type="text" required name="description" class="form-control" placeholder="Descripción">
	</div>
	<div class="form-group">
		<label>Duración</label>
		<input type="text" required name="duration" class="form-control" placeholder="Duración">
	</div>
	<div class="form-group">
		<label>Precio</label>
		<input type="text" required name="price" class="form-control" placeholder="Precio">
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
$item = MembershipData::getById($_GET["id"]);
?>
<div class="card mb-4">
	<div class="card-header">Membresias</div>
	<div class="card-body">
		<h2>Editar Membresía</h2>
		<div class="row">
			<div class="col-md-6">
				<form method="post" action="./?action=memberships&opt=update">
					<input type="hidden" name="_id" value="<?php echo $item->id; ?>">
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" required name="name" value="<?php echo $item->name; ?>" class="form-control" placeholder="Nombre">
	</div>
	<div class="form-group">
		<label>Descripción</label>
		<input type="text" required name="description" value="<?php echo $item->description; ?>" class="form-control" placeholder="Descripción">
	</div>
	<div class="form-group">
		<label>Duración</label>
		<input type="text" required name="duration" value="<?php echo $item->duration; ?>" class="form-control" placeholder="Duración">
	</div>
	<div class="form-group">
		<label>Precio</label>
		<input type="text" required name="price" value="<?php echo $item->price; ?>" class="form-control" placeholder="Precio">
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
