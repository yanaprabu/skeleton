<div class="pagination" id="standard">
<?php if ($first): ?><a href="pagination2.php?page=<?php echo $first; ?>">First</a>&nbsp;<?php endif; ?>
<?php if ($previous): ?><a href="pagination2.php?page=<?php echo $previous; ?>">Previous</a>&nbsp;<?php endif; ?>
<?php foreach ($before as $item): ?>
<?php if ($item): ?><a href="pagination2.php?page=<?php echo $item; ?>"><?php echo $item; ?></a>&nbsp;|&nbsp;<?php endif; ?>
<?php endforeach; ?>
<strong><?php echo $current; ?></strong>&nbsp;|&nbsp;
<?php foreach ($after as $item): ?>
<?php if ($item): ?><a href="pagination2.php?page=<?php echo $item; ?>"><?php echo $item; ?></a>&nbsp;|&nbsp;<?php endif; ?>
<?php endforeach; ?>
<?php if ($next): ?><a href="pagination2.php?page=<?php echo $next; ?>">Next</a>&nbsp;<?php endif; ?>
<?php if ($last): ?><a href="pagination2.php?page=<?php echo $last; ?>">Last</a><?php endif; ?>
</div>