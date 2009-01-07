<?php if ($first): ?><a href="basic_example.php?page=<?php echo $first; ?>"><< First</a>&nbsp;<?php endif; ?>
<?php if ($previous): ?><a href="basic_example.php?page=<?php echo $previous; ?>"><</a>&nbsp;<?php endif; ?>
<!--<?php if ($first && $before->current() > $first + 1): ?>...<?php endif; ?>-->
<?php foreach ($before as $item): ?>
<?php if ($item): ?><a href="basic_example.php?page=<?php echo $item; ?>"><?php echo $item; ?></a>&nbsp;<?php endif; ?>
<?php endforeach; ?>
<?php echo $page; ?>&nbsp;<?php foreach ($after as $item): ?>
<?php if ($item): ?><a href="basic_example.php?page=<?php echo $item; ?>"><?php echo $item; ?></a>&nbsp;<?php endif; ?>
<?php endforeach; ?>
<!--<?php if ($last && $after->current() < $last - 1): ?>...<?php endif; ?>-->
<?php if ($next): ?><a href="basic_example.php?page=<?php echo $next; ?>">></a>&nbsp;<?php endif; ?>
<?php if ($last): ?><a href="basic_example.php?page=<?php echo $last; ?>">Last >></a>&nbsp;<?php endif; ?>