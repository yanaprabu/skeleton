<?php echo $pagination; ?>
<table border="1" cellpadding="5">
<tr>
<th>ID</th>
<th>Text</th>
<th>Sort</th>
</tr>
<?php foreach ($pages as $page): ?>
<tr>
<td><?php echo $page['id']; ?></td>
<td><?php echo $page['text']; ?></td>
<td><?php echo $page['sort']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php echo $pagination; ?>

<form method="get" action="pagination2.php">
<input type="hidden" name="page" value="<?php echo $_GET['page'] ? $_GET['page'] : 1; ?>" />
Order by: <select name="sort_key">
<option value="id" <?php if ($_GET['sort_key'] == 'id'): ?>selected="selected"<?php endif; ?>>ID</option>
<option value="text" <?php if ($_GET['sort_key'] == 'text'): ?>selected="selected"<?php endif; ?>>text</option>
<option value="sort" <?php if ($_GET['sort_key'] == 'sort'): ?>selected="selected"<?php endif; ?>>Sort</option>
</select>
<select name="sort_order">
<option value="asc" <?php if ($_GET['sort_order'] == 'asc'): ?>selected="selected"<?php endif; ?>>Ascending</option>
<option value="desc" <?php if ($_GET['sort_order'] == 'desc'): ?>selected="selected"<?php endif; ?>>Descending</option>
</select>
Items per page: <select name="items_per_page">
<option value="5" <?php if ($_GET['items_per_page'] == '5'): ?>selected="selected"<?php endif; ?>>5</option>
<option value="10" <?php if ($_GET['items_per_page'] == '10'): ?>selected="selected"<?php endif; ?>>10</option>
</select>
<input type="submit" name="submit" value="Show" />
</form>