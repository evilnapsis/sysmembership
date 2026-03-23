<?php

$tables = [
	'client' => [
		'name' => 'Cliente',
		'fields' => ['name', 'lastname', 'email', 'address', 'phone'],
        'singular' => 'client',
        'plural' => 'clients'
	],
	'membership' => [
		'name' => 'Membresia',
		'fields' => ['name', 'description', 'duration', 'price'],
        'singular' => 'membership',
        'plural' => 'memberships'
	],
	'contract' => [
		'name' => 'Contrato',
		'fields' => ['client_id', 'membership_id', 'user_id', 'start_at', 'finish_at', 'status'],
        'singular' => 'contract',
        'plural' => 'contracts'
	],
    'payment' => [
		'name' => 'Pago',
		'fields' => ['client_id', 'contract_id', 'user_id', 'amount'],
        'singular' => 'payment',
        'plural' => 'payments'
	]
];

$base_dir = "c:/xampp/htdocs/sysmembership/core/app";

foreach($tables as $t => $data) {
    // Model Pattern
    $className = ["client"=>"ClientData", "membership"=>"MembershipData", "contract"=>"ContractData", "payment"=>"PaymentData"][$t];
    $modelName = $className;
    $fieldsInit = "";
    foreach($data['fields'] as $f) {
        $fieldsInit .= "\t\t\$this->$f = \"\";\n";
    }
    $modelCode = "<?php\nclass $modelName extends Extra {\n" .
"\tpublic static \$tablename = \"$t\";\n" .
"\tpublic \$extra_fields;\n" .
"\tpublic \$extra_fields_strings;\n\n" .
"\tpublic function __construct(){\n" .
"\t\t\$this->extra_fields = array();\n" .
"\t\t\$this->extra_fields_strings = array();\n" .
$fieldsInit . 
"\t\t\$this->created_at = \"NOW()\";\n" .
"\t}\n\n" .
"\tpublic function add(){\n" .
"\t\t\$sql = \"insert into \".self::\$tablename.\" (\".\$this->getExtraFieldNames().\",created_at) \";\n" .
"\t\t\$sql .= \"value (\".\$this->getExtraFieldValues().\",\$this->created_at)\";\n" .
"\t\treturn Executor::doit(\$sql);\n" .
"\t}\n\n" .
"\tpublic function del(){\n" .
"\t\t\$sql = \"delete from \".self::\$tablename.\" where id=\$this->id\";\n" .
"\t\tExecutor::doit(\$sql);\n" .
"\t}\n\n" .
"\tpublic static function delBy(\$k,\$v){\n" .
"\t\t\$sql = \"delete from \".self::\$tablename.\" where \$k=\\\"\$v\\\"\";\n" .
"\t\tExecutor::doit(\$sql);\n" .
"\t}\n\n" .
"\tpublic function update(){\n" .
"\t\t\$sql = \"update \".self::\$tablename.\" set \".\$this->getExtraFieldforUpdate().\" where id=\$this->id\";\n" .
"\t\tExecutor::doit(\$sql);\n" .
"\t}\n\n" .
"\tpublic function updateById(\$k,\$v){\n" .
"\t\t\$sql = \"update \".self::\$tablename.\" set \$k=\\\"\$v\\\" where id=\$this->id\";\n" .
"\t\tExecutor::doit(\$sql);\n" .
"\t}\n\n" .
"\tpublic static function getById(\$id){\n" .
"\t\t \$sql = \"select * from \".self::\$tablename.\" where id=\$id\";\n" .
"\t\t\$query = Executor::doit(\$sql);\n" .
"\t\treturn Model::one(\$query[0],new $modelName());\n" .
"\t}\n\n" .
"\tpublic static function getBy(\$k,\$v){\n" .
"\t\t\$sql = \"select * from \".self::\$tablename.\" where \$k=\\\"\$v\\\"\";\n" .
"\t\t\$query = Executor::doit(\$sql);\n" .
"\t\treturn Model::one(\$query[0],new $modelName());\n" .
"\t}\n\n" .
"\tpublic static function getAll(){\n" .
"\t\t \$sql = \"select * from \".self::\$tablename;\n" .
"\t\t\$query = Executor::doit(\$sql);\n" .
"\t\treturn Model::many(\$query[0],new $modelName());\n" .
"\t}\n\n" .
"\tpublic static function getAllBy(\$k,\$v){\n" .
"\t\t \$sql = \"select * from \".self::\$tablename.\" where \$k=\\\"\$v\\\"\";\n" .
"\t\t\$query = Executor::doit(\$sql);\n" .
"\t\treturn Model::many(\$query[0],new $modelName());\n" .
"\t}\n\n" .
"\tpublic static function getLike(\$q){\n" .
"\t\t\$sql = \"select * from \".self::\$tablename.\" where name like '%\$q%'\";\n" .
"\t\t\$query = Executor::doit(\$sql);\n" .
"\t\treturn Model::many(\$query[0],new $modelName());\n" .
"\t}\n" .
"}\n" .
"?>\n";

    file_put_contents("$base_dir/model/$modelName.php", $modelCode);

    // Action Pattern
    $addFields = "";
    foreach($data['fields'] as $f) {
        $addFields .= "\t\$item->addExtraFieldString(\"$f\", htmlentities(\$_POST[\"$f\"]));\n";
    }

    $plural = $data['plural'];
    $singular = $data['singular'];

    $actionCode = "<?php\n" .
"if(isset(\$_GET[\"opt\"]) && \$_GET[\"opt\"]==\"add\"){\n" .
"if(count(\$_POST)>0){\n" .
"\t\$item = new $modelName();\n" .
$addFields .
"\t\$item->add();\n" .
"\tCore::alert(\"$data[name] agregado!\");\n" .
"\tCore::redir(\"./?view=$plural&opt=all\");\n" .
"}\n" .
"}\n" .
"else if(isset(\$_GET[\"opt\"]) && \$_GET[\"opt\"]==\"update\"){\n" .
"if(count(\$_POST)>0){\n" .
"\t\$item = $modelName::getById(\$_POST[\"_id\"]);\n" .
$addFields .
"\t\$item->update();\n" .
"\tCore::alert(\"$data[name] actualizado!\");\n" .
"\tCore::redir(\"./?view=$plural&opt=all\");\n" .
"}\n" .
"}\n" .
"else if(isset(\$_GET[\"opt\"]) && \$_GET[\"opt\"]==\"del\"){\n" .
"\t\$item = $modelName::getById(\$_GET[\"id\"]);\n" .
"\t\$item->del();\n" .
"\tCore::alert(\"$data[name] eliminado!\");\n" .
"\tCore::redir(\"./?view=$plural&opt=all\");\n" .
"}\n" .
"?>\n";

    file_put_contents("$base_dir/action/$plural-action.php", $actionCode);
    
    // View Pattern
    $thFields = "";
    $tdFields = "";
    $formFieldsAdd = "";
    $formFieldsEdit = "";
    foreach($data['fields'] as $f) {
        $thFields .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<th>$f</th>\n";
        $tdFields .= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<td><?php echo \$item->$f; ?></td>\n";
        
        $formFieldsAdd .= "\t<div class=\"form-group\">\n" .
        "\t\t<label>$f</label>\n" .
        "\t\t<input type=\"text\" required name=\"$f\" class=\"form-control\" placeholder=\"$f\">\n" .
        "\t</div>\n";
        
        $formFieldsEdit .= "\t<div class=\"form-group\">\n" .
        "\t\t<label>$f</label>\n" .
        "\t\t<input type=\"text\" required name=\"$f\" value=\"<?php echo \$item->$f; ?>\" class=\"form-control\" placeholder=\"$f\">\n" .
        "\t</div>\n";
    }


    $viewCode = "<?php \n" .
"if(!isset(\$_SESSION[\"user_id\"])){ Core::redir(\"./\");}\n" .
"\$user= UserData::getById(\$_SESSION[\"user_id\"]);\n" .
"if(\$user==null){ Core::redir(\"./\");}\n" .
"?>\n" .
"<?php if(isset(\$_GET[\"opt\"]) && \$_GET['opt']==\"all\"):\n" .
"\$items = $modelName::getAll();\n" .
"?>\n" .
"<div class=\"row\">\n" .
"\t<div class=\"col-md-12\">\n" .
"\t\t<div class=\"card mb-4\">\n" .
"\t\t\t<div class=\"card-header\">$data[name]s</div>\n" .
"\t\t\t<div class=\"card-body\">\n" .
"\t\t\t\t<a href=\"./?view=$plural&opt=new\" class=\"btn btn-secondary\">Nuevo $data[name]</a>\n" .
"\t\t\t\t<br><br>\n" .
"\t\t\t\t<div class=\"table-responsive\">\n" .
"\t\t\t\t\t<?php if(count(\$items)>0):?>\n" .
"\t\t\t\t\t\t<table class=\"table border mb-0\">\n" .
"\t\t\t\t\t\t\t<thead class=\"table-light fw-semibold\">\n" .
"\t\t\t\t\t\t\t\t<tr class=\"align-middle\">\n" .
"\t\t\t\t\t\t\t\t\t<th>#</th>\n" .
$thFields .
"\t\t\t\t\t\t\t\t\t<th>Acciones</th>\n" .
"\t\t\t\t\t\t\t\t</tr>\n" .
"\t\t\t\t\t\t\t</thead>\n" .
"\t\t\t\t\t\t\t<tbody>\n" .
"\t\t\t\t\t\t\t\t<?php foreach(\$items as \$item):?>\n" .
"\t\t\t\t\t\t\t\t\t<tr>\n" .
"\t\t\t\t\t\t\t\t\t\t<td>#<?php echo \$item->id; ?></td>\n" .
$tdFields .
"\t\t\t\t\t\t\t\t\t\t<td>\n" .
"\t\t\t\t\t\t\t\t\t\t\t<a href=\"./?view=$plural&opt=edit&id=<?php echo \$item->id; ?>\" class=\"btn btn-warning btn-sm\"><i class=\"bi-pencil\"></i></a>\n" .
"\t\t\t\t\t\t\t\t\t\t\t<a href=\"./?action=$plural&opt=del&id=<?php echo \$item->id; ?>\" class=\"btn btn-danger btn-sm\"><i class=\"bi-trash\"></i></a>\n" .
"\t\t\t\t\t\t\t\t\t\t</td>\n" .
"\t\t\t\t\t\t\t\t\t</tr>\n" .
"\t\t\t\t\t\t\t\t<?php endforeach; ?>\n" .
"\t\t\t\t\t\t\t</tbody>\n" .
"\t\t\t\t\t\t</table>\n" .
"\t\t\t\t\t<?php else:?>\n" .
"\t\t\t\t\t\t<p class=\"alert alert-warning\">No hay $data[name]s</p>\n" .
"\t\t\t\t\t<?php endif; ?>\n" .
"\t\t\t\t</div>\n" .
"\t\t\t</div>\n" .
"\t\t</div>\n" .
"\t</div>\n" .
"</div>\n" .
"<?php elseif(isset(\$_GET[\"opt\"]) && \$_GET[\"opt\"]==\"new\"):\n" .
"?>\n" .
"<div class=\"card mb-4\">\n" .
"\t<div class=\"card-header\">$data[name]s</div>\n" .
"\t<div class=\"card-body\">\n" .
"\t\t<h2>Nuevo $data[name]</h2>\n" .
"\t\t<div class=\"row\">\n" .
"\t\t\t<div class=\"col-md-6\">\n" .
"\t\t\t\t<form method=\"post\" action=\"./?action=$plural&opt=add\">\n" .
$formFieldsAdd .
"\t\t\t\t\t<div class=\"d-grid gap-2\">\n" .
"\t\t\t\t\t\t<button type=\"submit\" class=\"btn btn-primary \">Guardar</button>\n" .
"\t\t\t\t\t</div>\n" .
"\t\t\t\t</form>\n" .
"\t\t\t</div>\n" .
"\t\t</div>\n" .
"\t</div>\n" .
"</div>\n" .
"<?php elseif(isset(\$_GET[\"opt\"]) && \$_GET[\"opt\"]==\"edit\"):\n" .
"\$item = $modelName::getById(\$_GET[\"id\"]);\n" .
"?>\n" .
"<div class=\"card mb-4\">\n" .
"\t<div class=\"card-header\">$data[name]s</div>\n" .
"\t<div class=\"card-body\">\n" .
"\t\t<h2>Editar $data[name]</h2>\n" .
"\t\t<div class=\"row\">\n" .
"\t\t\t<div class=\"col-md-6\">\n" .
"\t\t\t\t<form method=\"post\" action=\"./?action=$plural&opt=update\">\n" .
"\t\t\t\t\t<input type=\"hidden\" name=\"_id\" value=\"<?php echo \$item->id; ?>\">\n" .
$formFieldsEdit .
"\t\t\t\t\t<div class=\"d-grid gap-2\">\n" .
"\t\t\t\t\t\t<button type=\"submit\" class=\"btn btn-success \">Actualizar</button>\n" .
"\t\t\t\t\t</div>\n" .
"\t\t\t\t</form>\n" .
"\t\t\t</div>\n" .
"\t\t</div>\n" .
"\t</div>\n" .
"</div>\n" .
"<?php endif; ?>\n";

    file_put_contents("$base_dir/view/$plural-view.php", $viewCode);
}
echo "Done generating code.\n";
?>
