<?php if(isset($saved)): ?>
Changes saved.<br /><br />
<?php endif; ?>
<?php if(isset($previous)): ?><a href="/admin/users/<?= $previous ?>" data-ajax="false">Previous</a><?php endif; ?>&nbsp;
<?php if(isset($next)): ?><a href="/admin/users/<?= $next ?>" data-ajax="false">Next</a><?php endif; ?>

<form action="/admin/users/<?= $page ?>" method="POST" data-ajax="false">
<table>
<tr>
	<td width="25%">Lastname</td>
	<td width="25%">Firstname</td>
	<td width="50%">Email</td>
</tr>
<?php foreach($users as $user): ?>
<tr>
	<td width="25%"><input type="text" name="lastname[<?= $user->userid ?>]" value="<?= htmlentities($user->lastname) ?>" /></td>
	<td width="25%"><input type="text" name="firstname[<?= $user->userid ?>]" value="<?= htmlentities($user->firstname) ?>" /></td>
	<td width="50%"><input type="text" name="email[<?= $user->userid ?>]" value="<?= htmlentities($user->email) ?>" /></td>
</tr>
<?php endforeach; ?>
</table>

<input type="submit" name="submit" value="Save" />
</form>

<?php if(isset($previous)): ?><a href="/admin/users/<?= $previous ?>" data-ajax="false">Previous</a><?php endif; ?>&nbsp;
<?php if(isset($next)): ?><a href="/admin/users/<?= $next ?>" data-ajax="false">Next</a><?php endif; ?>