<?php
$script = preg_replace('/[^a-zA-Z0-9\_\.]/', '', $_GET['script']);
session_start();
echo '<pre>' . print_r($_SESSION, 1) . '</pre>';
?>
<html>
<body>

<p><a href="<?php echo $script; ?>">Return without resume.</a></p>

<p><a href="<?php echo $script; ?>?page=resume">Return with resume.</a></p>

<p><a href="<?php echo $script; ?>?page=resume&last_row=recalc">Return with resume and recalc.</a></p>

</body>
</html>