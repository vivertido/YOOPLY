<style>

</style>
<form action="/referral/add/<?= $student->userid ?>" method="POST" data-ajax="false">
	<div id="referral-form-wrapper" class="ui-shadow">
	<a href="#positionWindow" data-rel="popup"><img class="help-button" src="/images/question-mark-icon.png"/></a>
		<h3 class="center-heading">Referral Form  |  Status: <span>Incomplete</span></h3> 
		<div data-role="popup" id="positionWindow">
		<p class="instructions">To start a referral you must enter an incident reason. For a referral to be complete all required information must be entered and/or edited prior to submission.</p>
		</div>
		<hr>

		<div id="section1">
			<div id="basic-info">
				<div id="basic-info-left">
				<p class="category-label" ><strong>Date:</strong> <span  class="attribute"><?= date('m/d/Y g:i a') ?></span> </p> 
					<p class="category-label"><strong>Name:</strong><span class="attribute"><?= htmlentities($student->firstname) ?> <?= htmlentities($student->lastname) ?></span> <span class="attribute" ><strong> Gr:</strong> <?= $student->grade ?></span></p>
					<p class="category-label" ><strong>Location:</strong> <select name="location">
						<?php foreach($locations as $location): ?>
						<option value="<?= htmlentities($location) ?>"><?= htmlentities($location) ?></option>
						<?php endforeach; ?>
					</select></p>  
					 
				</div><!--//basic info left-->
				<div id="basic-info-right">
		 			<?php inflate_form($settings->questions, 'f'); ?> 
			 	</div>
			</div>

		</div>
 


		<div id="additional-info">

			<div id="motive">
			<hr>
				<p class="category-label"><strong>Possible Motivation</strong> </p>  
				<fieldset id="motivation-selector" data-role="controlgroup" data-type="horizontal">    
		  		  <select name="motivation">
		  		  	<?php foreach($motivations as $motivation): ?>
		  		  		<option value="<?= htmlentities($motivation) ?>"><?= $motivation ?></option>
		  		  	<?php endforeach; ?>
		  		  		<option value="">Other</option>
		    	  </select>
		    	</fieldset>
				<input style="font-family:'News Cycle'" id="enter-other-motivation" type="text" name="othermotivation" data-mini="true" data-theme="a" placeholder="Other" value="">
			</div>
			<div id="others">
				<p class="category-label"><strong>Others Involved:</strong></p>  
				<textarea style="font-family:'News Cycle'" id="enter-other-involved" data-mini="true" name="othersinvolved" placeholder="Names"></textarea>
			</div>
		</div>
		<div id="action-taken">
			<h3 >Actions Suggested</h3>
			<p class="instructions">Administrators will review and decide final consequence(s).</p>
			<div data-role="collapsible" data-content-theme="c">
				<h3>Consequences</h3>

		  		<?php $i = 0; foreach($consequences as $consequence): ?>
					<input type="checkbox" name="consequence[]" value="<?= htmlentities($consequence) ?>" id="checkbox-<?= $i ?>" class="custom" data-theme="a"/>
					<label for="checkbox-<?= $i ?>"><?= htmlentities($consequence) ?></label>
				<?php $i++; endforeach; ?>
		 
				<label for="basic">Other:</label>
				<input type="text" name="consequence[]" value="" />

				<div data-role="rangeslider" data-track-theme="b" data-theme="a">
					<?php $detention_minutes = 0; ?>
					<label for="minutes"><?= $labels->detention ?> <?= $labels->detentionunits ?>:</label>
					<input name="detention" id="minutes" min="0" max="50" value="<?= $detention_minutes ?>" type="range" step="1" />
				</div>
			</div>
		</div>

		<input type="submit" name="submit" value="Submit to office" data-inline="true" data-mini="true" data-theme="c" style="float:left"/>
		<input type="submit" name="save" value="Save Changes" data-inline="true" data-mini="true" data-theme="c" style="float:right"/>
	</div>
</form>