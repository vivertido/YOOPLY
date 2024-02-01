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

	$('#intervention').on('change', function()
	{
		if($(this).val() == '')
		{
			$('#interventionother').show();
		}
		else
		{
			$('#interventionother').hide();
		}
	});
});
</script>

<form action="/intervention/edit/<?= $intervention->interventionid ?>" method="POST" data-ajax="false">

<select name="intervention" id="intervention">
<?php $i = 0; $matched = false; foreach($interventions as $option): ?>
<option value="<?= htmlentities($option) ?>"<?= $matched = $matched || $intervention->intervention == $option; $intervention->intervention == $option ? ' selected="selected"' : '' ?>><?= htmlentities($option) ?></option>
<?php $i++;  endforeach; ?>
<option value=""<?= !$matched ? ' selected="selected"' : '' ?>>Other (see below)</option>
</select>

<div id="interventionother" style="<?php if($matched): ?>display:none<?php endif; ?>">
	<label for="basic">Enter Intervention:</label>
	<input type="text" name="other" id="basic" value="<?= !$matched ? htmlentities($intervention->intervention) : ''?>"  />
</div>

<div class="ui-grid-a">
    <div class="ui-block-a">Date of incident:
		<input type="date" name="date" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d', $intervention->timeincident > 0 ? $intervention->timeincident : $intervention->timecreated) ?>" />
	</div>
	<div class="ui-block-b">Time of incident:
		<input type="time" name="time" placeholder="HH:MM" value="<?= date('H:i', $intervention->timeincident > 0 ? $intervention->timeincident : $intervention->timecreated) ?>" />
	</div>
</div>

Notes:<br />
<textarea name="notes"><?= htmlentities($intervention->notes) ?></textarea>

Notify:
<ul data-role="listview" data-inset="true" id="notify" data-split-icon="delete">

</ul>

<input type="text" name="person" class="selectperson" placeholder="Search by name..." />

<input type="submit" name="submit" value="Save Changes" data-inline="true"  data-theme="b"/>
</form>