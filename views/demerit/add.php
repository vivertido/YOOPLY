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

<form action="/demerit/add/<?= $studentid ?>" method="POST" data-ajax="false">
	Reason:<br />
	<select name="reason">

<?php foreach($settings->demerits as $option): ?>
		<option value="<?= htmlentities($option) ?>"><?= htmlentities($option) ?></option>
<?php endforeach; ?>
	</select>

	<div class="ui-grid-a">
	    <div class="ui-block-a">Date of incident:
			<input type="date" name="date" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d') ?>" />
		</div>
		<div class="ui-block-b">Time of incident:
			<input type="time" name="time" placeholder="HH:MM" value="<?= date('H:i') ?>" />
		</div>
	</div>

	Notes:
	<textarea name="notes"></textarea>

	Notify:
	<ul data-role="listview" data-inset="true" id="notify" data-split-icon="delete">

	</ul>

	<input type="text" name="person" class="selectperson" placeholder="Search by name..." />

	<input type="submit" name="submit" value="Assign <?= htmlentities($settings->demeritlabel) ?>" />
</form>