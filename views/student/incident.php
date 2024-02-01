<script>
$().ready(function() {
	$('#advanced').change(function() {
		$('#easyquestions').toggle();
		$('#detailedquestions').toggle();
	});

});
</script>

<form action="/student/incident/<?= $referralid ?>" method="POST" data-ajax="false">
	<h2 style="font-weight:normal">Step 1: Who, what, when, where...</h2>
	<div class="content-primary">
		<div data-role="fieldcontain">
			<select name="mode" id="advanced" data-role="slider">
				<option value="easy" checked="checked">Easy</option>
				<option value="detailed">Detail</option>
			</select>
		</div>
	</div>

	<div id="easyquestions">
		<?= inflate_form($questions->easy); ?>
	</div>

	<div id="detailedquestions" style="display:none">
		<?= inflate_form($questions->detailed); ?>
	</div>

	<input type="submit" name="submit" value="Continue with Reflection">
</form>