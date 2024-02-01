<style>
.student
{
	background-color:#F4FFFF;
	width: 250px;
	float: left;
	padding:10px;
	border-color:#38e0FF;
	border-width:2px;
	border-radius:15px;
	border-style:solid;
	margin:7px;
}

div.selected
{
	background-color: #2db7e5;
	border-width: 2px;
}

img.quickavatar{
	width:50px;
	vertical-align:text-top;
	padding-right: 10px;
}

p.truncate{
	display:inline;
	font-size:18px;
	font-weight:normal;
	text-shadow:0px 0px 0px;
	margin:0px;
	display:inline;
}

a.buttonlink {
}
 
bottonlink.img{ width:20px; }
 #buttonselectall{ font-family:'Dosis'; } 
 #buttonselectnone{ font-family:'Dosis'; text-align:left; margin:0px; }

 p.buttonsubtitle{ font-size:10px; font-weight:normal; margin-left:0px; text-shadow: 0px 0px; text-align:center; }
 
 #groupid{
 margin-top:-20px;
 position:absolute;
 
  
}
 .box-icon{

 width:20px; margin-top:5px; margin-bottom:-3px; margin-left: 20px; margin-right:5px;
 
 
 }
 .student-status{
 
 
  position: absolute; font-size: 12px; text-align:left; margin-top:-5px; margin-left:15%;
 }
 p.box-labels{
 
 
 font-size:18px;
 margin-left:40px;
 
 }
 @media only screen and (max-device-width: 539px) {


p.buttonsubtitle{ display:none}
#groupid{

 font-size:9px;
 color:red;
 

}

.student
{
	background-color:#F4FFFF;
	width: 120px;
	float: left;
	padding:5px;
	border-color:#38e0FF;
	border-width:2px;
	border-radius:15px;
	border-style:solid;
	margin:3px;
}
p.truncate{
    width: 100px;
	font-size:12px;
	font-weight:normal;
	text-shadow:0px 0px 0px;
	margin:0px;
	 
	 
}

.box-icon{
 
 width:10px; margin-top:5px; margin-bottom:-3px; margin-left: 10px; margin-right:5px;
 
 
 }
 img.quickavatar{
	width:30px;
	 
	vertical-align:text-top;
	padding-right: 5px;
	 
}
.student-status{
 
 
  position: absolute; font-size: 10px; text-align:right; margin-top:-5px; margin-left:40px;color:red;  
 }
 p.box-labels{
 
 
 font-size:11px;
 padding:3px;
 margin-bottom:2px;
 margin-top:-5px;
 margin-left:10px;
 
 }
	}

</style>
 
<script>
    
</script>
<script>
$(document).ready(function(){
	$('#groupid').on('change', function()
	{
		var groupid = $(this).children('option:selected').attr('value');
		window.location = '/teacher/quickentry/'+groupid;
	});

	$('#buttonselectnone').click(function(){
		$('.student').removeClass('selected');
	});

	$('#buttonselectall').bind('click', function(){
		$('.student').addClass('selected');
	});

	$('#buttonselectrandom').bind('click', function(){
			$('.student').removeClass('selected');

			var rand_num = Math.floor(Math.random()*$('.student').size());
			$(".student").eq(rand_num).addClass('selected');
	});

	$('.student').bind('click', function(){
		$(this).toggleClass('selected');
	});

	$('.buttonlink').on('click', function()
	{
		if($('.selected').length == 0)
		{
			alert('Please select at least one student first.');
			return false;
		}
	});

	$('._assignpositivechoice').bind('click', function()
	{
		var choice = $(this).attr('data-choice');

		var selected = $('.selected');

		if(selected.length == 0) return;

		var ids = [];
		for(var i=0;i<selected.length;i++)
		{
			ids[ids.length] = $(selected[i]).attr('data-studentid');
		}

<?php switch($settings->reinforcementsoptions->quantitytype):
			case 'number':
			case 'range': ?>
				if(!($('#positiveamount').val() > 0))
				{
					alert('Please enter a positive number.');
					return;
				}
<?php break;
			case 'fixed':

			break;
endswitch; ?>

		$.post("/api/addreinforcement", {
				reinforcement: choice,
<?php switch($settings->reinforcementsoptions->quantitytype):
			case 'number':
			case 'range': ?>
				amount: $('#positiveamount').val(),
<?php break;
			case 'fixed':

			break;
endswitch; ?>
				studentid: ids.join('_')
			},function(data)
			{
				for(var i=0; i<data.length; i++)
				{
					$('#s'+data[i].student+'d').text(data[i].dollars);
				}
			},
			"json"
		);
		
		var sound = document.getElementById('positivesound');
		sound.volume = 0.2;
		sound.play();

		$('.student').removeClass('selected');
		$('#popuppositivechoice').popup('close');
	});

	$('._assignnegativechoice').bind('click', function()
	{
		var choice = $(this).attr('data-choice');

		var selected = $('.selected');

		if(selected.length == 0) return;

		var ids = [];
		for(var i=0;i<selected.length;i++)
		{
			ids[ids.length] = $(selected[i]).attr('data-studentid');
		}

		$.post("/api/adddemerit", {
				demerit: choice,
				studentid: ids.join('_')
			},function(data)
			{
				for(var i=0; i<data.length; i++)
				{
					$('#s'+data[i].student+'n').text(data[i].total);
				}
			},
			"json"
		);

		$('.student').removeClass('selected');
		$('#popupnegativechoice').popup('close');
		
		//Play the negative sound
		
		var sound = document.getElementById('negativesound');
		sound.volume = 0.2;
		sound.play();
		
		
	});

	$('._assignstatuschoice').bind('click', function()
	{
		var choice = $(this).attr('data-choice');

		var selected = $('.selected');

		if(selected.length == 0) return;

		var ids = [];
		for(var i=0;i<selected.length;i++)
		{
			ids[ids.length] = $(selected[i]).attr('data-studentid');
		}

		$.post("/api/addstatus", {
				status: choice,
				studentid: ids.join('_')
			},function(data)
			{
				for(var i=0; i<data.length; i++)
				{
					$('#s'+data[i].student+'s').text(data[i].status);
				}
			},
			"json"
		);

		$('.student').removeClass('selected');
		$('#popupstatuschoice').popup('close');		
	});	

	$('._assigndetention').bind('click', function()
	{
		var reason = $('#detentionreason').val();
		var time = $('#detentiontime').val();

		var selected = $('.selected');

		if(selected.length == 0) return;

		var ids = [];
		for(var i=0;i<selected.length;i++)
		{
			ids[ids.length] = $(selected[i]).attr('data-studentid');
		}

		$.post("/api/adddetention", {
				reason: reason,
				time: time,
				studentid: ids.join('_')
			},function(data)
			{
				for(var i=0; i<data.length; i++)
				{
					$('#s'+data[i].student+'m').text(data[i].minutes);
				}
			},
			"json"
		);

		$('.student').removeClass('selected');
		$('#popupdetention').popup('close');
	});

	$('._assignreferral').bind('click', function()
	{
		var reason = $('#referralreason').val();
		var notes = $('#referralnotes').val();

		var selected = $('.selected');

		if(selected.length == 0) return;

		var ids = [];
		for(var i=0;i<selected.length;i++)
		{
			ids[ids.length] = $(selected[i]).attr('data-studentid');
		}

		$.post("/api/addreferral", {
				reason: reason,
				notes: notes,
				studentid: ids.join('_')
			},function(data)
			{
			},
			"json"
		);

		$('.student').removeClass('selected');
		$('#popupreferral').popup('close');
	});

	$('._assignintervention').bind('click', function()
	{
		var reason = $('#interventionreason').val();
		var notes = $('#interventionnotes').val();

		var selected = $('.selected');

		if(selected.length == 0) return;

		var ids = [];
		for(var i=0;i<selected.length;i++)
		{
			ids[ids.length] = $(selected[i]).attr('data-studentid');
		}

		$.post("/api/addintervention", {
				reason: reason,
				notes: notes,
				studentid: ids.join('_')
			},function(data)
			{
			},
			"json"
		);

		$('.student').removeClass('selected');
		$('#popupintervention').popup('close');
	});

	$('._assignhallpass').bind('click', function()
	{
		var reason = $('#hallpassreason').val();
		var notes = $('#hallpassnotes').val();

		var selected = $('.selected');

		if(selected.length == 0) return;

		var ids = [];
		for(var i=0;i<selected.length;i++)
		{
			ids[ids.length] = $(selected[i]).attr('data-studentid');
		}

		$.post("/api/addhallpass", {
				reason: reason,
				notes: notes,
				studentid: ids.join('_')
			},function(data)
			{
			},
			"json"
		);

		$('.student').removeClass('selected');
		$('#popuphallpass').popup('close');
	});
	
	

 });

</script>
<script>
 
    $(function(){
    var w= $(document).width();
   
    
    if (w<=481){
      
        $('.truncate').succinct({
        
         
         
            size: 16
        });
   
         
     }

});
</script>
<div>
	<select id="groupid">
		<?php foreach($groups as $group): ?>
		<option style="color:blue;" value="<?= $group->groupid ?>"<?= $groupid == $group->groupid ? ' selected="selected"' : '' ?>><?= $group->title ?></option>
		<?php endforeach; ?>
	</select>
	<div style="width: 50%; float: left;">
		<a id="buttonselectall" href="#"  data-theme="b" data-mini="true">All</a> | <a id="buttonselectnone" href="#"  data-theme="b" data-mini="true">None</a> 
	</div>
 
	<div style="width: 50%; float: right;">
		 <?php /*<a id="buttonselectrandom" href="#"  data-theme="b" data-mini="true">Present</a> |  */ ?><a id="buttonselectrandom" href="#"  data-theme="b" data-mini="true">Random</a>
 </div>
 
	<br>
	<?php if(strpos($features->reinforcements, $this->session->userdata('role')) !== false): ?>
	<div style="width: 16%; float: left;">
		<a class="buttonlink" data-corners="false" href="#popuppositivechoice" data-rel="popup" data-role="button" data-theme="a">
			<img src="/images/plus-sign.png" />
			<p class="buttonsubtitle"><?= htmlentities($labels->reinforcements) ?></p>
		</a>
	</div>
	<?php endif; ?>
	<?php if($this->session->userdata('schoolid') != 19 && strpos($features->demerits, $this->session->userdata('role')) !== false): ?>
	<div style="width: 16%; float: left;">
		<a class="buttonlink" data-corners="false" href="#popupnegativechoice" data-rel="popup" data-role="button" data-theme="a">
			<img src="/images/minus-sign.png" />
			<p class="buttonsubtitle"><?= htmlentities(trim($demeritlabel)) ?></p>
		</a>
	</div>
	<?php endif; ?>
	<div style="width: 16%; float: left;">
		<a class="buttonlink" data-corners="false" href="#popupstatuschoice" data-rel="popup" data-role="button" data-theme="a">
			<img src="/images/ticket.png" />
			<p class="buttonsubtitle">Status</p>
		</a>
	</div>
	<?php if(strpos($features->detentions, $this->session->userdata('role')) !== false): ?>
	<div style="width: 16%; float: left;">
		<a class="buttonlink" data-corners="false" href="#popupdetention"  data-role="button" data-rel="popup" data-theme="a">
			<img src="/images/clock.png" />
			<p class="buttonsubtitle"><?= htmlentities($labels->detentions) ?></p>
		</a>
	</div>
	<?php endif; ?>
	<?php if(strpos($features->referrals, $this->session->userdata('role')) !== false): ?>
	<div style="width: 16%; float: left;">
		<a class="buttonlink" data-corners="false" href="#popupreferral" data-role="button" data-rel="popup" data-theme="a">
			<img src="/images/whistle.png" >
			<p class="buttonsubtitle"><?= htmlentities($labels->referrals) ?></p>
		</a>
	</div>
	<?php endif; ?>
	<?php if(strpos($features->interventions, $this->session->userdata('role')) !== false): ?>
	<div style="width: 16%; float: left;">
		<a class="buttonlink" data-corners="false" href="#popupintervention" data-role="button" data-rel="popup" data-theme="a">
			<img src="/images/caution.png" >
			<p class="buttonsubtitle"><?= htmlentities($labels->interventions) ?></p>
		</a>
	</div>
<?php endif; ?>

	<div data-role="popup" id="popuppositivechoice">
	<?php switch($settings->reinforcementsoptions->quantitytype):
	case 'number': ?>
		<p>Assign <?= htmlentities($labels->reinforcements) ?> for Reason:</p>
		<ul data-role="listview">
			<input type="number" id="positiveamount" min="0" data-mini="true" step="1" data-theme="c" value="1" data-inset="true" />
	<?php break;
	case 'range': ?>
		<p>Award <?= htmlentities($labels->reinforcements) ?> for Reason:</p>
		<ul data-role="listview">
			<input type="range" id="positiveamount" class="slider" min="<?= $settings->reinforcementsoptions->awardamountmin ?>" data-mini="true" max="<?= $settings->reinforcementsoptions->awardamountmax ?>" value="<?= $settings->reinforcementsoptions->awardamountmin ?>" step="1" data-theme="c" />
	<?php break;
	case 'fixed': ?>
		<p>Award <b><?= $settings->reinforcementsoptions->awardamount ?></b> <?= htmlentities($labels->reinforcements) ?> for Reason:</p>
		<ul data-role="listview">
	<?php break;
	endswitch; ?>

			<?php foreach($settings->reinforcements as $reinforcement): ?>
			<li><a href="#" class="_assignpositivechoice" data-choice="<?= htmlentities($reinforcement) ?>"><?= htmlentities($reinforcement) ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div data-role="popup" id="popupnegativechoice">
		<p><?= htmlentities(trim($demeritlabel)) ?>:</p>
		<ul data-role="listview">
			<?php foreach($settings->negatives as $negative): ?>
			<li><a href="#" class="_assignnegativechoice" data-choice="<?= htmlentities($negative) ?>"><?= htmlentities($negative) ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div data-role="popup" id="popupstatuschoice">
		<p>Status:</p>
		<ul data-role="listview">
			<?php foreach($settings->statuses as $status): ?>
			<li><a href="#" class="_assignstatuschoice" data-choice="<?= htmlentities($status) ?>"><?= htmlentities($status) ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>	

	<div data-role="popup" id="popupdetention" style="padding:20px">
		<p>Reason:</p>
		<select id="detentionreason" data-native-menu="false">
			<?php foreach($settings->detentions as $detention): ?>
			<option value="<?= htmlentities($detention) ?>"><?= htmlentities($detention) ?></option>
			<?php endforeach; ?>
		</select>
		<hr>
		<p># <?= htmlentities($labels->detentionunits) ?>:</p>
		<input type="range" id="detentiontime" class="slider" min="1" data-mini="true" max="200" value="1"  data-theme="c" />
		
		<p>Notes (optional):</p>
		<textarea id="detentionnotes"></textarea>
		<input class="_assigndetention" type="button" value="Assign" style="margin-top:15px" />
	</div>

	<div data-role="popup" id="popupreferral" style="padding:20px">
		<p>Referral Reason:</p>
		<select id="referralreason" data-native-menu="false">
			<?php foreach($settings->referrals as $referral): ?>
			<option value="<?= htmlentities($referral) ?>"><?= htmlentities($referral) ?></option>
			<?php endforeach; ?>
		</select>
		<hr>
		<p>Notes:</p>
		<textarea id="referralnotes"></textarea>
		<input class="_assignreferral" type="button" value="Assign" style="margin-top:15px" />
	</div>

	<div data-role="popup" id="popupintervention" style="padding:20px">
		<p>Intervention:</p>
		<select id="interventionreason" data-native-menu="false">
			<?php foreach($settings->interventions as $intervention): ?>
			<option value="<?= htmlentities($intervention) ?>"><?= htmlentities($intervention) ?></option>
			<?php endforeach; ?>
		</select>
		<hr>
		<p>Notes:</p>
		<textarea id="interventionnotes"></textarea>
		<input class="_assignintervention" type="button" value="Assign" style="margin-top:15px" />
	</div>

	<div data-role="popup" id="popuphallpass" style="padding:20px">
		<p>Hallpass to:</p>
		<select id="hallpassreason" data-native-menu="false">
			<?php foreach($settings->hallpasses as $hallpass): ?>
			<option value="<?= htmlentities($hallpass) ?>"><?= htmlentities($hallpass) ?></option>
			<?php endforeach; ?>
		</select>
		<hr>
		<p>Notes:</p>
		<textarea id="hallpassnotes"></textarea>
		<input class="_assignhallpass" type="button" value="Assign" style="margin-top:15px" />
	</div>
</div>

<div style="clear: both">
<?php foreach($students as $student): ?>
	<div class="student" data-studentid="<?= $student->userid ?>" style="overflow: auto">
		<img src="/images/<?= $student->profileimage ?>" class="quickavatar" style="float: left">
		
		<p class="truncate"><?= $student->firstname ?> <?= $student->lastname ?></p>
	<p class="box-labels">	$<span id="s<?= $student->userid ?>d"><?= isset($student->dollars) ? $student->dollars : 0 ?></span> <img class="box-icon"   src="/images/minus-sign.png" /><span id="s<?= $student->userid ?>n"><?= isset($student->negativepoints) ? $student->negativepoints : 0 ?></span>  <img class="box-icon" src="/images/clock.png" /><span id="s<?= $student->userid ?>m"><?= isset($student->detentionminutes) ? $student->detentionminutes : 0 ?></span>  </p><span id="s<?= $student->userid ?>s" class="student-status"><?= isset($student->statusvalue) && !empty($student->statusvalue) ? $student->statusvalue : 'In class' ?></span>

	</div>
<?php endforeach; ?>
</div>

<audio id="positivesound" src="/sounds/pointsup2.mp3" volume="0.1">
 <p> Your browser does not support etc</p>
       </audio>
       
       <audio id="negativesound" src="/sounds/sound8.mp3"  volume="0.1">
 <p> Your browser does not support etc</p>
       </audio>