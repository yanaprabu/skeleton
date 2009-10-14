<p>This is the template file standardlayout.</p>

<div style="float:left;width:74%;">
<?php echo $maincontent; ?>
</div>

<div style="float:right;width:25%;">
<?php echo isset($subcontent) ? $subcontent : '&nbsp;'; ?>
</div>