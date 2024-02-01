<?php $messages = $settings->messages; ?>

<script>
$().ready(function()
{
	$('#selectfriend').on('click', function() {
		$('#selectfriendcontent').simpledialog2();
	});

	$('.friend').on('click', function()
	{
		$('#studentid').val($(this).attr('data-studentid'));
		$('#sendtodisplay').text($(this).attr('data-studentname'));

		$(document).trigger('simpledialog', {'method':'close'});
	});

	$('.reason').on('click', function()
	{
		$('#reason').val($(this).attr('data-reason'));
		$('#reasondisplay').text($(this).attr('data-reason'));

		$(document).trigger('simpledialog', {'method':'close'});
	});

	$('#selectreason').on('click', function() {
		$('#selectreasoncontent').simpledialog2({
			dialogAllow:true,
			width:400
		});
	});
});
</script>

<style>
	img.avatarSmall{
		width:80%;
		height:80%
	}
</style>
<img src="/images/speaker-electric.png" style="float:left; margin-top:20px; margin-right:10px"> <h2 style="font-weight:normal">New Shoutout?</h2>

<form action="/shoutout/send" method="POST" data-ajax="false">
	<select name="to">
	<?php
		$columns = array('a', 'b', 'c');
		$column = 0;
		$user_id = $this->session->userdata('userid');

		foreach($classmates as $classmate):
		if($classmate->userid == $user_id): continue; endif; ?>
			<option value="<?= $classmate->userid ?>"><div class="ui-bar ui-bar-c" style="height:100px"><img  class="avatarSmall" src="/images/luchadorsmall.png" /><p class="avatarSubtitle" style="font-width:normal; font-size:10px"><?= $classmate->firstname ?> <?= $classmate->lastname ?></option>
	<?php endforeach; ?>
	</select>

	<select name="content">
		<?php foreach($messages as $category): ?>
			<optgroup label="<?= $category->title ?>">
				<?php foreach($category->options as $option): ?>
					<option value="<?= htmlentities($option) ?>"><?= $option ?></option>
				<?php endforeach; ?>
			</optgroup>
		<?php endforeach; ?>
	</select>
	<input type="submit" name="submit" value="Send Shoutout">
</form>

