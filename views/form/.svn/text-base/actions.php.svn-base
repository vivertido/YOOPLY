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
			var role = $(event.target).attr('data-list');
			console.log('#'+role);
			$('#notify'+role).append('<li><input type="hidden" name="notify'+role+'[]" value="'+ui.item.userid+'" /><a href="#">'+ui.item.label+'</a><a href="#" class="_removep" data-rel="popup" data-position-to="window" data-transition="pop"></a></li>');
			$('#notify'+role).listview("refresh");
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

	$('.notify').on('click', '._removep', function()
	{
		$(this).parent('li').remove();
		//console.log($(this).closest('.notify')); //listview('refresh');
	});
});
</script>

When this form is submitted by a:

<form action="/form/actions/<?= $form->formid ?>" method="POST" data-ajax="false">
	<?php foreach(str_split($form->contributors) as $role): ?>
	<h3><?= $roles[$role] ?></h3>
	<ul data-role="listview" data-inset="true">
		<li><input type="checkbox" name="<?= strtolower($roles[$role]) ?>[emailparent]" id="<?= strtolower($roles[$role]) ?>emailparent"<?= isset($actions[$role]) && array_key_exists('emailparent', $actions[$role]) ? ' checked="checked"' : '' ?> /> <label for="<?= strtolower($roles[$role]) ?>emailparent">Email parent report</label></li>
		<li>
			Notify:
			<ul data-role="listview" data-inset="true" class="notify" id="notify<?= strtolower($roles[$role]) ?>" data-split-icon="delete">
<?php if(isset($form->actions[$role]['notify'])): foreach($form->actions[$role]['notify'] as $uid): ?>
	<li><input type="hidden" name="notify<?= strtolower($roles[$role]) ?>[]" value="<?= $uid ?>" /><a href="#"><?= $notifyusers[$uid]->firstname ?> <?= $notifyusers[$uid]->lastname ?></a><a href="#" class="_removep" data-rel="popup" data-position-to="window" data-transition="pop"></a></li>
<?php endforeach; endif; ?>
			</ul>

			<input type="text" name="person" class="selectperson" data-list="<?= strtolower($roles[$role]) ?>" placeholder="Search by name..." />
		</li>

<?php /*		<li><input type="checkbox" name="<?= strtolower($roles[$role]) ?>[smsparent]" id="<?= strtolower($roles[$role]) ?>smsparent"<?= isset($actions[$role]) && array_key_exists('smsparent', $actions[$role]) ? ' checked="checked"' : '' ?> /> <label for="<?= strtolower($roles[$role]) ?>smsparent">Send parent SMS</label> Message: <select name="<?= strtolower($roles[$role]) ?>smsparentmessage" data-inline="true" data-mini="true">
			<?php foreach($sms as $key=>$message): if(!isset($message->enabled)): continue; endif; ?>
			<option value="<?= $key ?>"<?= isset($actions[$role]) && array_key_exists('smsparent', $actions[$role]) && $actions[$role]['smsparent']['message'] == $key ? ' selected="selected"' : '' ?>><?= $message->title ?></option>
		<?php endforeach; ?></select></li>
		<?php if(strtolower($roles[$role]) != 'teacher'): ?>
		<li><input type="checkbox" name="<?= strtolower($roles[$role]) ?>[smsteacher]" id="<?= strtolower($roles[$role]) ?>smsteacher"<?= isset($actions[$role]) && array_key_exists('smsteacher', $actions[$role]) ? ' checked="checked"' : '' ?> /> <label for="<?= strtolower($roles[$role]) ?>smsteacher">Send teacher SMS</label> Message: <select name="<?= strtolower($roles[$role]) ?>smsteachermessage" data-inline="true" data-mini="true">
			<?php foreach($sms as $key=>$message): if(!isset($message->enabled)): continue; endif; ?>
			<option value="<?= $key ?>"<?= isset($actions[$role]) && array_key_exists('smsteacher', $actions[$role]) && $actions[$role]['smsteacher']['message'] == $key ? ' selected="selected"' : '' ?>><?= $message->title ?></option>
		<?php endforeach; ?></select></li>		
		<?php endif; ?>
		<?php if(strtolower($roles[$role]) != 'admin'): ?>
		<li><input type="checkbox" name="<?= strtolower($roles[$role]) ?>[smsadmin]" id="<?= strtolower($roles[$role]) ?>smsadmin"<?= isset($actions[$role]) && array_key_exists('smsadmin', $actions[$role]) ? ' checked="checked"' : '' ?> /> <label for="<?= strtolower($roles[$role]) ?>smsadmin">Send admin SMS</label> Message: <select name="<?= strtolower($roles[$role]) ?>smsadminmessage" data-inline="true" data-mini="true">
			<?php foreach($sms as $key=>$message): if(!isset($message->enabled)): continue; endif; ?>
			<option value="<?= $key ?>"<?= isset($actions[$role]) && array_key_exists('smsadmin', $actions[$role]) && $actions[$role]['smsadmin']['message'] == $key ? ' selected="selected"' : '' ?>><?= $message->title ?></option>
		<?php endforeach; ?></select></li>		
		<?php endif; ?>
*/ ?>
	</ul>
	<?php endforeach; ?>

	<input type="submit" name="submit" value="Save Changes" />
</form>