<style>
h3.aHeading3
{
	color:orange;
}
</style>

<div class="ui-grid-a" style="background-color:#fff; padding:10px" >
	<div class="ui-block-a">
		<img src="/images/<?= $student->profileimage ?>" style=" margin-right:10px; float:left; border-style:solid; border-width:2px; border-color:#2489ce" width="30">
		<h3 style="text-shadow: 0px 0px; margin-bottom:0px"><?= $student->firstname ?> <?= $student->lastname ?></h3>
		<p style="text-shadow: 0px 0px; font-weight:normal; font-size: 10px ; text-align:left; margin:0px;">referral ID: <?= $referral->referralid ?>
		</p>
	</div>
	<div class="ui-block-b">
		<p style="text-shadow: 0px 0px; font-weight:normal; text-align:right; margin:0px;">Grade: <?= $student->grade ?></p>
		<p style="text-shadow: 0px 0px; font-weight:normal; text-align:right; margin:0px;">SID: <?= $student->studentid ?></p>
		<h4 style="text-shadow: 0px 0px; font-weight:normal; text-align:right; margin:0px;" ><?=date('m/d/Y')?></h4>
	</div>
</div>

<form action="/reflections/reflect/<?= $referral->referralid ?>" method="POST" data-ajax="false">
	<?php inflate_form($questions); ?>

	<input type="submit" name="submit" value="Submit" data-inline="true"  data-theme="b"/>
	<input type="button" value="Cancel" data-icon="delete" data-inline="true"/>
</form>