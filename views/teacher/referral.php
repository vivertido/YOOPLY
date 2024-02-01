<?php if(!empty($referral))
{
	$teacher_notes = json_decode($referral->teachernotes);
}
?>

<script src="https://rawgithub.com/jquery/jquery-ui/1-10-stable/ui/jquery.ui.datepicker.js"></script>
<script src="https://rawgithub.com/arschmitz/jquery-mobile-datepicker-wrapper/master/jquery.mobile.datepicker.js"></script>
 
<style>

@media  (min-width: 1200px) {

	#referral-form-wrapper{
 
padding:10px; font-family:'Dosis';
 text-shadow:none; 
background: #f9f9f9;
 


height: auto;
 }
 /*
 
 
 

#additional-info{
position:relative;
float:left;
border-style:solid;
 
border-color:#2db7e5;
border-top-width:1px;
border-bottom-width:1px;
border-right-width:1px;
border-left-width:1px;

width:100%;
 
 
 
}


}

@media all and (min-width: 645px) {
 
#referral-form-wrapper{
position: absolute;
padding:10px; font-family:'Dosis';
 text-shadow:none; 
background: #f9f9f9;

margin-right:6%;
height: auto;
 
 
 
 
 
}
.center-heading{
color:#603813;
text-align:left;
 margin-top:-5px;

}
.instructions{

font-size:16px;  padding:5px; border-radius:4px;
margin-top:-20px;

}
.category-label{
  
margin-right:20px;
color:#603813;


} 
#section1{

width:100%;

}
 
#basic-info{
margin-left:10px;
text-align:center;
width:100%;
 
height:auto;

}
#basic-info-right{
 
 padding:10px;
 
width:46%;
 float:right;
 

}
#basic-info-left{
 
padding:10px;
 
width:46%;
float:left;
 

}

#additional-info{

position:relative;
float:left;
border-style:solid;
 
border-color:#2db7e5;
border-top-width:1px;
border-bottom-width:1px;
border-right-width:1px;
border-left-width:1px;

width:100%;
 
 
}

#motive{
text-align:center;
background-color:#bbbbbb;
 position:relative;
width:46%;
height:180px;
 
float:left;
padding:10px;
 
}
#enter-other-motivation{

border-radius:10px;
font-size:18px;
}
#enter-other-involved{

border-radius:10px;
font-size:18px;
}


#others{
 position:relative; 
 text-align:center;
 background-color:#bbbbbb;
padding:10px;
 float:left;
 border-width:0px;
width:46%;
height:180px;
}
#action-taken{
 
 padding:10px;
 margin-left:30px;
 width:95%;
 height:100%;
 background-color:rgba(51,204,255, 0.5);


}
.attribute{

 margin-left:5px;
 font-size:20px;
}
 
  */
 }

 /*For smartphones*/
@media all and (max-width: 644px) {


 



}
</style>
<form action="/teacher/referral/<?= $studentid ?><?= !empty($referral) ? '/'.$referral->referralid : '' ?>" method="POST" data-ajax="false">

<div id="referral-form-wrapper" class="ui-shadow">
		<h3 class="center-heading">Referral Form --- Status: <span> Incomplete </span></h3>
		<p class="instructions">To start a referral you must enter an incident reason and select 'Start Referral'. For a referral to be complete all required information must be entered and/or edited prior to submission.</p>
		<input type="submit" name="submit" value="Start Referral" data-inline="true" data-mini="true" data-theme="c" />
		<input type="submit" name="submit" value="Submit" data-inline="true" data-mini="true" data-theme="b"/>
		<input id="edit-button" type="button" name="edit" value="Edit" data-inline="true" data-mini="true" data-theme="a"/>
		<hr>
	
	<div id="basic-info" >
		<div id="basic-info-left">
						<p class="category-label"><strong>Name:</strong><span class="attribute"><?= htmlentities($student->firstname) ?> <?= htmlentities($student->lastname) ?></span> <span class="attribute" ><strong> Gr:</strong> <?= $student->grade ?></span></p>
						<p class="category-label" ><strong>Referred by:</strong> <span id="referred-by-name" class="attribute"><?= htmlentities($user->firstname) ?> <?= htmlentities($user->lastname) ?></span> </p>  
						<p class="category-label" ><strong>Location:</strong> <span class="attribute">Class</span></p>  
						<p class="category-label" ><strong>Date:</strong> <span  class="attribute"><?= date('m/d/Y h:i a', empty($referral) ? time() : $referral->timecreated) ?></span> </p>  
		</div><!--//basic info left-->


		<div id="basic-info-right">
					 
			 			<?php inflate_form($settings->questions); ?> 
		</div>
		
	</div>

	<div id="additional-info">

	<div id="motive">
				<?php $motivations = array(
					'Get Peer Attention',
					'Get Adult Attention',
					'Avoid Peer Attention',
					'Avoid Adult Attention',
					'Item/Activity',
					'Other: see below',
				); ?>
				<p class="category-label" ><strong>Possible Motivation</strong> </p>  
				<fieldset id="motivation-selector" data-role="controlgroup" data-type="horizontal">    
		  		  <select name="motivation" >
		  		  	<?php foreach($motivations as $motivation): ?>
		  		  		<option value="<?= htmlentities($motivation) ?>"><?= $motivation ?></option>
		  		  	<?php endforeach; ?>
		    	  </select>
		    	</fieldset>
				<input style="font-family:'News Cycle'" id="enter-other-motivation" type="text" name="othermotivation" data-mini="true" data-theme="a" placeholder="Other">
			</div>
			<div id="others">
				<p class="category-label" ><strong>Others Involved:</strong></p>  
				<textarea style="font-family:'News Cycle'" rows="5" cols="20" name="othersinvolved" placeholder="others..."></textarea>
			</div>




	</div>
	<div id="action-taken">
			<h3 class="center-heading">Actions Suggested</h3>
			<p class="instructions">Administrators will review and decide final consequence(s).</p>
			<div data-role="collapsible" data-content-theme="c">
				<h3>Consequences</h3>
		  
		  		<?php $consequences = array(
		  			'Reflection',
		  			'Time Out/Detention',
		  			'Loss of Privileges',
		  			'Parent Contact',
		  			'Individualized Instruction',
		  			'In-School Suspension',
		  			'Out-of-School Suspension',
		  			'Conference With Student',
		  			'Action Pending'
		  		); ?>

		  		<?php $i = 0; foreach($consequences as $consequence): ?>
					<input type="checkbox" name="consequence[]" value="<?= htmlentities($consequence) ?>" id="checkbox-<?= $i ?>" class="custom" />
					<label for="checkbox-<?= $i ?>"><?= htmlentities($consequence) ?></label>
				<?php $i++; endforeach; ?>
		 
				<label for="basic">Other:</label>
				<input type="text" name="consequence[]" value=""  />

				<div data-role="rangeslider" data-track-theme="b" data-theme="a">
					<label for="minutes">Detention Minutes:</label>
					<input name="detention" id="minutes" min="0" max="100" value="0" type="range" step="5" />
				</div>

			</div>	
  	</div>
		

</div><!--//wrapper-->


<script>
$(document).ready( function(){

$('#edit-button').click(function(){

$('#referred-by-name').text('');
$('#referred-from-location').text('');
$('#referred-on-date').text('');
 
 

var inputField1=$("<input type='text' name='field' value=''  />");
var inputField2=$("<input type='text' name='field2' value=''  />");
 
 
$('#referred-by-name').append(inputField1);
$('#referred-from-location').append(inputField2);
 
 

 
});

});

</script>