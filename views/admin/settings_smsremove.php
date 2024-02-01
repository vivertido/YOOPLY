<form action="/admin/settings/	sms/remove/<?= $type ?>" method="POST" data-ajax="false">
	Are you sure you want to remove the SMS template titled '<?= $message->title ?>'?

	<input type="submit" name="submit" value="Remove template" />
	<input type="submit" name="cancel" value="Cancel" />
</form>