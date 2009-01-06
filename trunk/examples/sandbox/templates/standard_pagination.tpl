<? if ($first): ?><a href="basic_example.php?page=<?=$first; ?>"><< First</a>&nbsp;<? endif; ?>
<? if ($previous): ?><a href="basic_example.php?page=<?=$previous; ?>"><</a>&nbsp;<? endif; ?>
<!--<? if ($first && $before->current() > $first + 1): ?>...<? endif; ?>-->
<? foreach ($before as $item): ?>
<? if ($item): ?><a href="basic_example.php?page=<?=$item; ?>"><?=$item; ?></a>&nbsp;<? endif; ?>
<? endforeach; ?>
<?=$page; ?>&nbsp;<? foreach ($after as $item): ?>
<? if ($item): ?><a href="basic_example.php?page=<?=$item; ?>"><?=$item; ?></a>&nbsp;<? endif; ?>
<? endforeach; ?>
<!--<? if ($last && $after->current() < $last - 1): ?>...<? endif; ?>-->
<? if ($next): ?><a href="basic_example.php?page=<?=$next; ?>">></a>&nbsp;<? endif; ?>
<? if ($last): ?><a href="basic_example.php?page=<?=$last; ?>">Last >></a>&nbsp;<? endif; ?>