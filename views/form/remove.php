<form action="/form/remove/<?= $form->formid ?>" method="POST" data-ajax="false">
	Are you sure you want to remove the form title '<?= $form->title ?>'? This will remove the form and all the responses that were made using this form.

	<input type="submit" name="submit" value="Yes, delete the form and responses" /> <input type="submit" name="cancel" value="Cancel" />
</form>