<style>
.column1 {
}
.column2 {
}
</style>
<h2>Step 1 - Connect to Database</h2>
<form action="index.php/builder/index/" method="post">
<div>
	<div class="column1">
	Database type:
	</div>
	<div class="column2">
	<select name="phptype">
<?php
var_export(get_loaded_extensions());
$exts = get_loaded_extensions();
$dbexts = array(
  'mysql' => 'mysql',
  'mysqli' => 'mysqli',
  'pdo_mysql' => 'PDO MySQL',
  'pdo_sqlite' => 'PDO SQLite',
  'SQLite' => 'SQLite',
  'sqlite3' => 'sqlite3',
);
foreach ($dbexts as $driver => $name) {
	if (in_array($driver, $exts)) {
		echo "<option name=\"$driver\">$name</option>\n";
	}
}
?>
	</select>
	</div>
</div>
<div>
	<div class="column1">
	Host:
	</div>
	<div class="column2">
	<input type="text" name="host" value=""/>
	</div>
</div>
<div>
	<div class="column1">
	Database:
	</div>
	<div class="column2">
	<input type="text" name="database" value=""/>
	</div>
</div>
<div>
	<div class="column1">
	Username:
	</div>
	<div class="column2">
	<input type="text" name="username" value=""/>
	</div>
</div>
<div>
	<div class="column1">
	Password:
	</div>
	<div class="column2">
	<input type="text" name="password" value=""/>
	</div>
</div>
<div>
	<div class="column1">
	&nbsp;
	</div>
	<div class="column2">
	<input type="submit" name="go" value="next"/>
	</div>
</div>
</form>
