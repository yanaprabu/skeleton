<form action="index.php/cart/" method="get">
<h2>Product Listing</h2>
<h3>Category <?php echo $category; ?></h3>
<table cellpadding="5">
<tr>
<td>SKU</td>
<td>Name</td>
<td>Price</td>
<td>Quantity</td>
</tr>
<?php
$carturl = new A_Cart_Url();

$rows = $product->findProductsInCategory($category);
foreach ($rows as $row)
{
	echo "<tr>
<td>{$row['sku']}</td>
<td>{$row['name']}</td>
<td>$" . number_format($row['price'], 2) . "</td>
<td>" . $carturl->pageAddItemFormText($row['sku'], 0) . "</td>
</tr>";
}
?>
</table>
<input type="submit" name="buy" value="buy"/>
</form>
<p><a href="index.php/product_category/"/>Return to Product Categories</a> | <a href="index.php/cart/"/>View Shopping Cart</a></p>