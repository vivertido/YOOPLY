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

<?php $notes = json_decode($referral->adminnotes); ?>

<p><b>SID:</b> <?= $student->studentid ?><br />
<b>Grade:</b> <?= $student->grade ?><br />
<b>Date:</b> <?= date('m/d/Y', $referral->timecreated) ?></p>

<form action="/referral/edit/<?= $referral->referralid ?>" method="POST" data-ajax="false">
	<strong>Incident Categories</strong><br />
	<select name="reason" data-native-menu="false">
	<?php foreach($settings->reasons as $reason): ?>
		<option value="<?= htmlentities($reason) ?>"><?= $reason ?></option>
	<?php endforeach; ?>
	</select>

	<strong>Actions Taken</strong>
	<textarea name="actionstaken"><?= isset($notes->external->actionstaken) ? htmlentities($notes->external->actionstaken) : '' ?></textarea>

	<strong>Internal Summary</strong>
	<textarea name="internalnotes"><?= isset($notes->internal->note) ? htmlentities($notes->internal->note) : '' ?></textarea>

	<strong>External Summary</strong>
	<textarea name="externalnotes"><?= isset($notes->external->note) ? htmlentities($notes->external->note) : '' ?></textarea>

	Notify:
	<ul data-role="listview" data-inset="true" id="notify" data-split-icon="delete">

	</ul>

	<input type="text" name="person" class="selectperson" placeholder="Search by name..." />

	<input type="submit" name="submit" value="Submit" data-inline="true" data-theme="b" />
	<input type="submit" name="cancel" value="Cancel" data-icon="delete" data-inline="true" />
</form>