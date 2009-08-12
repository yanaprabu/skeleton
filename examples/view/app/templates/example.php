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


<h3>About this example</h3>
<p>The goals of this example are:</p>
<ol>
	<li>To show the three View modes: <b>setContent()</b>, <b>setTemplate()</b> and <b>setRenderer()</b></li>
	<li>To show using the various methods of the View, such as <b>partial()</b>, <b>partialLoop()</b>, <b>set()</b>, <b>get()</b>.</li>
	<li>To show working together with the Response for layouts. So, using the default layout, replacing the default layout, replacing default sidebars, etc.</li>
	<li>To show setting <b>headers</b> and <b>redirects</b>.</li>
</ol>

<p>In the menu above are a few different actions showing a couple of things</p>
	
<?php
// partial loop with single parameter which is an array of assoc arrays
echo $this->partialLoop('colors', $this->colors);

// partial for the footer template
echo $this->partial('layout/footer');
?>