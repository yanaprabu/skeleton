<?php if ($first): ?><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=<?php echo $first; ?>"><< First</a>&nbsp;<?php endif; ?>
<?php if ($previous): ?><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=<?php echo $previous; ?>"><</a>&nbsp;<?php endif; ?>
<!--<?php if ($first && $before->current() > $first + 1): ?>...<?php endif; ?>-->
<?php foreach ($before as $item): ?>
<?php if ($item): ?><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=<?php echo $item; ?>"><?php echo $item; ?></a>&nbsp;<?php endif; ?>
<?php endforeach; ?>
<?php echo $page; ?>&nbsp;<?php foreach ($after as $item): ?>
<?php if ($item): ?><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=<?php echo $item; ?>"><?php echo $item; ?></a>&nbsp;<?php endif; ?>
<?php endforeach; ?>
<!--<?php if ($last && $after->current() < $last - 1): ?>...<?php endif; ?>-->
<?php if ($next): ?><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=<?php echo $next; ?>">></a>&nbsp;<?php endif; ?>
<?php if ($last): ?><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=<?php echo $last; ?>">Last >></a>&nbsp;<?php endif; ?>