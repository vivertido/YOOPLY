<form action="/admin/settings/sms/<?= $action ?><?= !empty($type) ? '/'.$type : '' ?>" method="POST" data-ajax="false">
	Title: <input type="text" name="title" value="<?= htmlentities($message->title) ?>" />
	<label for="enabled">Enabled</label>
	<input type="checkbox" name="enabled" id="enabled" value="1"<?= $message->enabled ? ' checked="checked"' : '' ?> />
	<textarea name="message"><?= htmlentities($message->message) ?></textarea>
	<input type="submit" name="submit" value="<?= $action == 'add' ? 'Add SMS Message' : 'Save Changes' ?>" />
</form>

Tip: Depending on the type of action this SMS message is sent for, you can include a student's name using %%STUDENT%% and the reporter's (teacher/admin) name using %%REPORTER%%.