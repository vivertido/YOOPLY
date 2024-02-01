<style>
.tools
{
	float:right;
}
</style>
<script>
$().ready(function() {
	$(".selectperson").autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "/api/findconnected/"+request.term+'/all',
				dataType: "json",
				success: function( data ) {
					response($.map( data.names, function( item ) {
						return {
							label: item.name,
							value: item.name,
							userid: item.userid
						}
					}));
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			$('#notify').append('<li><input type="hidden" name="notify[]" value="'+ui.item.userid+'" /><a href="#">'+ui.item.label+'</a><a href="#" class="_removep" data-rel="popup" data-position-to="window" data-transition="pop"></a></li>');
			$('#notify').listview("refresh");
			$(this).val('');
			return false;
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});

	$('#notify').on('click', '._removep', function()
	{
		$(this).parents('li').remove();
	});
});
</script>


<form action="/consequence/add/<?= $incidenttype?>/<?= $incidentid ?>" method="POST" data-ajax="false">
	<select name="consequence">
	<?php foreach($consequences as $c): ?>
		<option value="<?= $c ?>"><?= $c ?></option>
	<?php endforeach; ?>
	</select>

	Status
	<?php $statuses = array('Pending', 'In Progress', 'Completed', 'Dismissed'); ?>
	<select name="status">
		<?php foreach($statuses as $v): ?>
		<option value="<?= $v ?>"><?= $v ?></option>
		<?php endforeach; ?>
	</select>

	Notes:
	<textarea name="notes"></textarea>

	Notify:
	<ul data-role="listview" data-inset="true" id="notify" data-split-icon="delete">

	</ul>

	<input type="text" name="person" class="selectperson" placeholder="Search by name..." />


	<input type="submit" name="submit" value="Add Consequence" />
</form>