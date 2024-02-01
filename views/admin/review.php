<p><b>SID:</b> <?= $student->studentid ?><br />
<b>Grade:</b> <?= $student->grade ?><br />
<b>Date:</b> <?= date('m/d/Y', $referral->timecreated) ?></p>
<p>This referral has not been submitted</p>

<form action="/admin/review/<?= $referral->referralid ?>" method="POST" data-ajax="false">
	<strong>Incident Categories</strong><br />
	<select name="reason" data-native-menu="false">
<?php foreach($settings->reasons as $reason): ?>
		<option value="<?= htmlentities($reason) ?>"><?= $reason ?></option>
<?php endforeach; ?>
	</select>

	<strong>Internal Summary</strong>
	<textarea name="internalnotes"></textarea>

	<strong>External Summary</strong>
	<textarea name="externalnotes"></textarea>

	<div data-role="collapsible" data-content-theme="c">
		<h3>Consequences</h3>

<?php $i = 1; foreach($settings->consequences as $consequence): ?>
		<input type="checkbox" name="consequence[]" value="<?= htmlentities($consequence) ?>" id="checkbox-<?= $i ?>" class="custom" />
		<label for="checkbox-<?= $i ?>"><?= $consequence ?></label>
<?php $i++; endforeach; ?>

		<label for="basic">Other:</label>
		<input type="text" name="other" value=""  />

		<div data-role="rangeslider" data-track-theme="b" data-theme="a">
			<label for="minutes">Detention Minutes:</label>
			<input name="detention" id="minutes" min="0" max="100" value="0" type="range" step="5" />
		</div>

		<div data-role="rangeslider" data-track-theme="b" data-theme="a">
			<label for="days">Suspension Days </label>
			<input name="suspension" id="days" min="0" max="5" value="0" type="range" />
		</div>

	</div>
	
	
	 
		 <fieldset data-role="collapsible">
        <legend>Follow Ups</legend>
        
        <div data-role="controlgroup">
        <p>Who should receive a follow up?</p>
            <input type="checkbox" name="checkbox-1-a" id="checkbox-1-a">
            <label for="checkbox-1-a">Teacher of Student 1</label>
            <input type="checkbox" name="checkbox-2-a" id="checkbox-2-a">
            <label for="checkbox-2-a">Teacher of Student 2</label>
            <input type="checkbox" name="checkbox-3-a" id="checkbox-3-a">
            <label for="checkbox-3-a">Teacher of Student 3</label>
        </div>
        <label for="textinput-f">Note:</label>
        <input type="text" name="textinput-f" id="textinput-f" placeholder="Please take the following action" value="">
        
        <p>By when should follow up be completed?</p>
        <input type="text" data-role="date">
    </fieldset>
    
    
	<br>
<hr> 
	
 <input type="submit" name="submit" value="Print" data-inline="true" data-theme="b" />
<!--	<input type="button" value="Upload Evidence" data-icon="alert" data-inline="true"/><br /><br />-->

	<input type="submit" name="submit" value="Submit" data-inline="true" data-theme="b" />
	<input type="submit" name="cancel" value="Cancel" data-icon="delete" data-inline="true" />
</form>