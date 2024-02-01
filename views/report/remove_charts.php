Are you sure you want to remove the report title '<?= htmlentities($report->title) ?>'?
<form action="/report/remove/<?= $report->reportid ?>" method="POST" data-ajax="false">
	<input type="submit" name="submit" value="Remove Report" />
	<input type="submit" name="cancel" value="Cancel" />
</form>