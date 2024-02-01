<script>
$().ready(function() {
	$('#advanced').change(function() {
		$('#easyquestions').toggle();
		$('#detailedquestions').toggle();
	});

});
</script>

<form action="/admin/settings/incident" method="POST" data-ajax="false">
   <h2>Edit studemt response questionaire</h2>
   <p>These are the questions your students will see when they are asked to write a reflection, if you have added that functionality</p>
	<p>Toggle between easy and detailed modes and click on the text to edit questions.</p>

	<div class="content-primary">
		<div data-role="fieldcontain">
			<select name="mode" id="advanced" data-role="slider">
				<option value="easy" checked="checked">Easy</option>
				<option value="detailed">Detail</option>
			</select>
		</div>
	</div>

	<div id="easyquestions">
	<?= inflate_edit_form($settings->questions->easy, 'easy', true); ?>
	</div>

	<div id="detailedquestions" style="display:none">
	<?= inflate_edit_form($settings->questions->detailed, 'detailed'); ?>
	</div>
	<input type="submit" name="submit" value="Save Changes" data-theme="c"/>
</form>