<html>
<head>
<title></title>
<style>
.error {
	color: red;
	}
</style>
</head>
<body>

<form action="" method="post">
<input type="hidden" name="action" value="Form1">
<table>
    <tr>
      <td>Field 1 (Numbers only in range 1-10)</td>
      <td><?php $p1 = $this->getField('field1'); echo $p1->render(); ?></td>
      <td><span class="error"><?php echo $p1->getErrorMsg(', '); ?></span></td>
    </tr>
    <tr>
      <td>Field 2 (Must match Field 1)</td>
      <td><?php $p2 = $this->getField('field2'); echo $p2->render(); ?></td>
      <td><span class="error"><?php echo $p2->getErrorMsg(', '); ?></span></td>
    </tr>
    <tr>
      <td>Field 3 (Letters only min length 5)</td>
      <td><input type="text" name="field3" value="<?php $p3 = $this->getField('field3'); echo $p3->value; ?>"></td>
      <td><span class="error"><?php echo $p3->getErrorMsg(', '); ?></span></td>
    </tr>
    <tr>
      <td>Field 4 (Convert letters to uppercase)</td>
      <td><input type="text" name="field4" value="<?php $p4 = $this->getField('field4'); echo $p4->value; ?>"></td>
      <td><span class="error"><?php echo $p4->getErrorMsg(', '); ?></span></td>
    </tr>    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" value="submit"></td>
    </tr>    
  </table>
  </form>
	<a href="../">Return to Examples</a>
</body>
</html>