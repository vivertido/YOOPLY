<script>
$(function()
{
	$('#smsenable').on('change', function()
	{
		$.post("/api/enablesms/"+$(this).val());
	});
});
</script>

<h3>SMS Message Templates</h3>

<ul data-role="listview" data-inset="true" data-split-icon="delete">
	<?php foreach($messages as $k=>$message): if(!isset($message->title)): continue; endif; ?>
	<li<?= !$message->enabled ? ' style="opacity:.5"' : '' ?>><a href="/admin/settings/sms/edit/<?= $k ?>"><?= $message->title ?></a>
	<a href="/admin/settings/sms/remove/<?= $k ?>" data-ajax="false" data-rel="popup" data-position-to="window" data-transition="pop">Remove</a></li>
	<?php endforeach; ?>
</ul>

<ul data-role="listview" data-inset="true">
	<li><a href="/admin/settings/sms/add">New SMS text</a></li>	
</ul>

<h3>Enable/Disable All SMS messages</h3>
<p>You can disable all text messages by turning this switch to off. If turned on, any messages that have been enabled will be sent.</p>

<select name="smsenable" id="smsenable" data-role="slider">
	<option value="on"<?= $messages->enabled ? ' selected="selected"' : '' ?>>On</option>
	<option value="off">Off</option>
</select> 

<h3>Usage</h3>
Your school has used <?= number_format($smssent) ?> / <?= !isset($messages->quota) ? 'unlimited' : number_format($messages->quota) ?> SMS messages.

