<!--teacher -->
<?php if(!empty($referral))
{
	$teacher_notes = json_decode($referral->teachernotes);
	
	$values = array();

	foreach($teacher_notes as $e)
	{
		if(isset($e->id))
		{
			$values[$e->id] = $e->value;
		}
	}
}
?>

 
<form action="/referral/edit/<?= $referral->referralid ?>" method="POST" data-ajax="false">
	<div id="referral-form-wrapper">
		<h3 class="center-heading">Referral Form --- Status: <span> <?= $referral->timeteachersave == '0' ? 'Incomplete' : 'Sent to Office' ?> </span></h3>
		<p class="instructions">To start a referral you must enter an incident reason and select 'Start Referral'. For a referral to be complete all required information must be entered and/or edited prior to submission.</p>
		<?php if($referral->timeteachersave == 0): ?><input type="submit" name="submit" value="Submit to office" data-inline="true" data-mini="true" data-theme="b"/><?php endif; ?>
		<input type="submit" name="save" value="Save Changes" data-inline="true" data-mini="true" data-theme="b"/>
		<hr>

		<div id="section1">
			<div id="basic-info">
				<div id="basic-info-left">
					<p class="category-label"><strong>Name:</strong><span class="attribute"><?= htmlentities($student->firstname) ?> <?= htmlentities($student->lastname) ?></span> <span class="attribute" ><strong> Gr:</strong> <?= $student->grade ?></span></p>
					<p class="category-label" ><strong>Location:</strong> <select name="location">
						<?php foreach($locations as $location): ?>
						<option value="<?= htmlentities($location) ?>"><?= htmlentities($location) ?></option>
						<?php endforeach; ?>
					</select></p>  
					<p class="category-label" ><strong>Date:</strong> <span  class="attribute"><?= date('m/d/Y g:i a', empty($referral) ? time() : $referral->timecreated) ?></span> </p>  
				</div><!--//basic info left-->
				<div id="basic-info-right">
		 			<?php inflate_form($settings->questions, 'f', false, $teacher_notes); ?> 
			 	</div>
			</div>
		</div>
		<div id="additional-info">
			<div id="motive">
				<p class="category-label"><strong>Possible Motivation</strong> </p>  
				<fieldset id="motivation-selector" data-role="controlgroup" data-type="horizontal">    
		  		  <select name="motivation">
		  		  	<?php $selected_motivation = $values[md5('Possible Motivation')];

		  		  	$motivation_in_list = false; 
		  		  	foreach($motivations as $motivation): 
		  		  		if($selected_motivation == $motivation): 
		  		  			$motivation_in_list = true; 
		  		  		endif; ?>
		  		  		<option value="<?= htmlentities($motivation) ?>"<?= $selected_motivation == $motivation ? ' selected="selected"' : '' ?>><?= $motivation ?></option>
		  		  	<?php endforeach; ?>
		  		  		<option value=""<?= !$motivation_in_list ? ' selected="selected"' : '' ?>>Other</option>
		    	  </select>
		    	</fieldset>
				<input style="font-family:'News Cycle'" id="enter-other-motivation" type="text" name="othermotivation" data-mini="true" data-theme="a" placeholder="Other" value="<?= !$motivation_in_list ? htmlentities($selected_motivation) : '' ?>">
			</div>
			<div id="others">
				<p class="category-label"><strong>Others Involved:</strong></p>  
				<textarea style="font-family:'News Cycle'" rows="5" cols="20" name="othersinvolved" placeholder="others..."><?= isset($values[md5('Others Involved')]) ? htmlentities($values[md5('Others Involved')]) : '' ?></textarea>
			</div>
		</div>
		<div id="action-taken">
			<h3 class="center-heading">Actions Suggested</h3>
			<p class="instructions">Administrators will review and decide final consequence(s).</p>
			<div data-role="collapsible" data-content-theme="c">
				<h3>Consequences</h3>

		  		<?php $i = 0; foreach($consequences as $consequence): ?>
					<input type="checkbox" name="consequence[]" value="<?= htmlentities($consequence) ?>" id="checkbox-<?= $i ?>" class="custom" <?= isset($values[md5('Actions Suggested')]) && in_array($consequence, $values[md5('Actions Suggested')]) ? ' checked="checked"' : ''; ?> />
					<label for="checkbox-<?= $i ?>"><?= htmlentities($consequence) ?></label>
				<?php $i++; endforeach; ?>
		 

				<label for="basic">Other:</label>
				<input type="text" name="consequence[]" value="<?php if(isset($values[md5('Actions Suggested')])): 
					foreach($values[md5('Actions Suggested')] as $c): 
						$consequence_in_list = false; 
						foreach($consequences as $consequence): 
							if($c == $consequence): 
								$consequence_in_list = true;
								break;
							endif;
						endforeach;

						if(!$consequence_in_list && !preg_match('/Detention: /', $c)): echo $c; endif; 
						endforeach; endif; ?>"  />

				<div data-role="rangeslider" data-track-theme="b" data-theme="a">
					<?php $detention_minutes = 0; 
					if(isset($values[md5('Actions Suggested')])): 
						foreach($values[md5('Actions Suggested')] as $c):
							$matches = array();
							if(preg_match('/Detention: (.*?) minute(s)?/', $c, $matches)):
								$detention_minutes = $matches[1];
							endif;
						endforeach;
					endif;
					?>
					<label for="minutes">Detention Minutes:</label>
					<input name="detention" id="minutes" min="0" max="100" value="<?= $detention_minutes ?>" type="range" step="5" />
				</div>
			</div>
		</div>
	</div>
</form>