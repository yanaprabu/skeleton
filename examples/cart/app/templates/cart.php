<form action="index.php/cart/" method="get">
<h1>Shopping Cart</h1>
<?php
$carturl = new A_Cart_Url();
$carturl->setManager($cart);

$cartitems = $cart->getItems();
if ($cartitems) {
?>
<table  cellpadding="5">
<tr>
	<td>SKU</td>
	<td>Name</td>
	<td>Price</td>
	<td>Quantity</td>
	<td>Sub-Total</td>
	<td>delete</td>
</tr>
<?php
	foreach ($cartitems as $item)
	{
		echo "<tr>
	<td>" . $item->getProductId() . "</td>
	<td>" . $item->getData('name') . "</td>
	<td>" . $cart->moneyFormat($item->getUnitPrice()) . "</td>
	<td>" . $carturl->cartSetQuantityFormTextField($item->getId(), $item->getQuantity()) . "</td>
	<td>" . $cart->moneyFormat($item->getPrice()) . "</td>
	<td>" . $carturl->cartDeleteFromCheckbox($item->getId()) . "</td>
</tr>";
	}
?>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td><?php echo $cart->moneyFormat($cart->getGrandTotal()); ?></td>
	<td>&nbsp;</td>
</tr>
</table>
<input type="submit" name="update" value="update"/>
</form>
<?php
} else {
	echo "<p>There are no items in your Shopping Cart. </p>";
}
?>
<p><a href="index.php/product_category/"/>Return to Product Categories</a></p>