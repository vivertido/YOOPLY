<?php if(isset($error)): switch($error):
case 'emptytitle':
?>
<b>Please enter a group title.</b>
<?php
break;
endswitch; endif; ?>
<form action="/group/edit/<?= $group->groupid ?>" method="POST" data-ajax="false">
Group name:<br />
<input type="text" name="title" value="<?= isset($title) ? htmlentities($title) : htmlentities($group->title) ?>" />

<input type="submit" name="submit" value="Save Changes" />
</form>