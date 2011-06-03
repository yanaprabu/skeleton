
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
<input type="hidden" name="action" value="Form4">
<table>

    <tr>
      <td>Name</td>
      <td><input size="10" name="name" value="<?php if(isset($values['name'])) { echo $values['name']; } ?>" type="text"/></td>
      <td><span class="error"><?php if(isset($errmsg['name'])) { echo implode(', ', $errmsg['name']); } ?></span></td>
    </tr>
    <tr>
      <td>Email</td>
      <td><input type="text" name="email" value="<?php if(isset($values['email'])) { echo $values['email']; } ?>"></td>
      <td><span class="error"><?php if(isset($errmsg['email'])) { echo implode(', ', $errmsg['email']); } ?></span></td>
    </tr>
    <tr>
      <td>Password</td>
      <td><input type="text" name="password" value="<?php if(isset($values['password'])) { echo $values['password']; } ?>"></td>
      <td><span class="error"><?php if(isset($errmsg['password'])) { echo implode(', ', $errmsg['password']); } ?></span></td>
    </tr>
    <tr>
      <td>Password again</td>
      <td><input type="text" name="password2" value="<?php if(isset($values['password2'])) { echo $values['password2']; } ?>"></td>
      <td><span class="error"><?php if(isset($errmsg['password2'])) { echo implode(', ', $errmsg['password2']); } ?></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" value="submit"></td>
    </tr>    
  </table>
  </form>

</body>
</html>