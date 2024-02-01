<script>
$().ready(function(){

	$('._addlocation').on('click', function()
	{
		var num = $('#locations input').size()+1;
		$('#locations').append('Location '+num+': <input type="text" name="location[]" value="" /><br />');
		$('#locations').trigger("create");
	});
});
</script>

<form action="/admin/settings/locations" method="POST" data-ajax="false">
<div id="locations">
	<?php $i = 1; foreach($locations as $option): ?>
	Location <?= $i ?>: <input type="text" name="location[]" value="<?= htmlentities($option) ?>" /><br />
	<?php $i++; endforeach; ?>
</div>

<a href="#" class="_addlocation">Add another location</a>

<input type="submit" name="submit" value="Save Changes" />
</form>