<?php
// partial for the header template
echo $this->partial('layout/header');
?>

<ul id="menu">
<?php
// partial loop with two parameters where the 1st is the name and the 2nd is an array of values to replace name each loop
echo $this->partialLoop('menu', 'menuitem', $this->menuitems);
?>
</ul>


<p>The content</p>


	
<?php
// partial loop with single parameter which is an array of assoc arrays
echo $this->partialLoop('colors', $this->colors);

// partial for the footer template
echo $this->partial('layout/footer');
?>