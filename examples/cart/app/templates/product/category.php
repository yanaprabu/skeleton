<h2>Product Categories</h2>
<?php
$rows = $product->findCategories();
foreach ($rows as $name)
{
	echo "<p><a href=\"index.php/product_listing/?category={$name}\"/>{$name}</a></p>";
}
?>
<p><a href="index.php/cart/"/>View Shopping Cart</a></p>