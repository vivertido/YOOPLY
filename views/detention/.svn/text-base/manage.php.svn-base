<style>

</style>

<div id="expired" style="margin: 10px; padding: 10px; border: 5px solid #F9AEAE; background-color: #FBD2D2; display:none">
	The following students have served their detention:
	<ul id="expiredlist">

	</ul>
	<input type="button" class="_dismissExpired" value="Dismiss" />
</div>

<div id="button-section">
	<a class="detention-manage-btn" href="#popupadjusttime" data-rel="popup">Adjust</a>
	<a class="detention-manage-btn" href="#popupaddsubtract" data-rel="popup">+ /-  </a>
	<a class="detention-manage-btn" id="startAll" class="startAll" href="javascript:void(0);">Start All</a>
	<a class="detention-manage-btn" id="stopAll" class="stopAll" href="javascript:void(0);">Stop All</a>
	<a class="detention-manage-btn" id="resetAll" href="javascript:void(0);">Reset All</a>
</div>

<?php
$header = '';
foreach($detentions as $detention):
	$is_active = false;
	$time_elapsed = 0;

	foreach($activedetentions as $d):
		if($d->studentid == $detention->studentid):
			$is_active = true;
			$time_elapsed = time()-$d->timecreated;
		endif;
	endforeach;
	
	$detention->servedminutes = empty($detention->servedminutes) ? 0 : $detention->servedminutes;
	$h = addOrdinalNumberSuffix($detention->grade).' Grade';
	if($header != $h)
	{
		$header = $h;
?>
					<div class="section-header" style="clear:both; padding-top: 10px">
						<h1><?= $header ?></h1>
					</div>

					<div style="overflow:auto">
<?php
	}
?>


						<div class="wrapper student" data-id="<?= $detention->studentid ?>">
							<div class="student-left" data-rel="popup">
								<p class="name" style="max-width:120px; white-space: nowrap;overflow: hidden;"><?= $detention->lastname ?>, <?= $detention->firstname ?></p>
								<p class="time" style="max-width:120px; white-space: nowrap;overflow: hidden;"><span class="remaining"><?= $detention->assignedminutes-$detention->servedminutes ?></span> Min. | <strong>To Date: </strong><span class="assigned"><?= $detention->assignedminutes ?></span></p>
							</div>
							<div class="student-right _startstop">
								<p class="start-btn-lbl">Start</p>
								<img class="clock" src="/images/detentionclock.png" />
							</div>
						</div>

<?php endforeach; ?>

<script>
var timers = {};
var expired = [];

$().ready(function()
{
	var isSelected = false;
	<?php foreach($detentions as $detention):
	$is_active = false; $time_elapsed = 0;
	foreach($activedetentions as $d):
		if($d->studentid == $detention->studentid):
			$is_active = true;
			$time_elapsed = time()-$d->timecreated;
		endif;
	endforeach; ?>
timers[<?= $detention->studentid ?>] = [<?= $time_elapsed ?>, <?= $detention->servedminutes*60 ?>, <?= $detention->assignedminutes*60 ?>, <?= $is_active ? 1 : 0 ?>];

<?php endforeach; ?>

	function startTimer(student)
	{
		$.ajax({
			dataType: "json",
			url: "/api/startdetention/"+student,
		}).done(function( msg ) {
			console.log(msg);
			for(var i=0; i<msg.length; i++)
			{
				var s = msg[i];

				if(s.status)
				{
					alert('Uh oh! Please refresh.');
					break;
				}

				console.log(s);
				timers[s.studentid] = [parseInt(s.timeelapsed), parseInt(s.timeserved), parseInt(s.timeassigned), parseInt(s.active)];

				refreshStudent(s.studentid);

				console.log(timers[s.studentid]);
			}
		});
	}

	function resetTimer(student)
	{
		$.ajax({
			dataType: "json",
			url: "/api/resetdetention/"+student,
		}).done(function( msg ) {
			console.log(msg);
			for(var i=0; i<msg.length; i++)
			{
				var s = msg[i];

				if(s.status)
				{
					alert('Uh oh! Please refresh.');
					break;
				}

				console.log('Resetting...');
				console.log(s);
				timers[s.studentid] = [parseInt(s.timeelapsed), parseInt(s.timeserved), parseInt(s.timeassigned), parseInt(s.active)];

				refreshStudent(s.studentid);

				console.log(timers[s.studentid]);
			}
		});
	}

	function stopTimer(student)
	{
		$.ajax({
			dataType: "json",
			url: "/api/stopdetention/"+student,
		}).done(function( msg ) {
			console.log(msg);
			for(var i=0; i<msg.length; i++)
			{
				var s = msg[i];

				if(s.status)
				{
					alert('Uh oh! Please refresh.');
					break;
				}

				console.log(s);
				timers[s.studentid] = [parseInt(s.timeelapsed), parseInt(s.timeserved), parseInt(s.timeassigned), parseInt(s.active)];

				refreshStudent(s.studentid);

				console.log(timers[s.studentid]);
			}
		});
	}

	function adjustTimer(student, balance)
	{
		$.ajax({
			dataType: "json",
			url: "/api/adjustdetention/"+student+"/"+balance,
		}).done(function( msg ) {
			console.log(msg);
			for(var i=0; i<msg.length; i++)
			{
				var s = msg[i];

				if(s.status)
				{
					alert('Uh oh! Please refresh.');
					break;
				}

				console.log(s);
				timers[s.studentid] = [0, parseInt(s.timeassigned)-parseInt(s.minutes), parseInt(s.timeassigned), 0];

				refreshStudent(s.studentid);

				if(timers[s.studentid][0]+timers[s.studentid][1] >= timers[s.studentid][2])
				{
					expired.push(s.studentid);
					showExpired();
				}

				console.log(timers[s.studentid]);
			}
		});
	}	

	function showExpired()
	{
		console.log('showing expired list');
		console.log(expired);

		var list = '';
		for(var i=0; i<expired.length; i++)
		{
			list += '<li>'+$('.student[data-id='+expired[i]+'] .name').text()+'</li>';			
		}

		$('#expiredlist').html(list);
		$('#expired').show();
	}

	function refreshStudent(studentid)
	{
		var timeRemaining = Math.ceil((timers[studentid][2]-timers[studentid][1]-timers[studentid][0])/60);

		$(".student[data-id='"+studentid+"'] .remaining").text(Math.max(timeRemaining,0));

		$(".student[data-id='"+studentid+"'] .assigned").text(Math.ceil(timers[studentid][2]/60));

		if(timers[studentid][3] == 1)
		{
			$(".student[data-id='"+studentid+"']").addClass('activestudent');
			$(".student[data-id='"+studentid+"']").removeClass('timeexpired');

			$(".student[data-id='"+studentid+"']").find('.start-btn-lbl').text('Stop');
		}
		else
		{
			$(".student[data-id='"+studentid+"']").find('.start-btn-lbl').text('Start');
			$(".student[data-id='"+studentid+"']").removeClass('activestudent');
		}

		if(Math.max(timeRemaining, 0) == 0)
		{
			$(".student[data-id='"+studentid+"']").addClass('timeexpired');
		}
	}

	$('._startstop').click(function()
	{
		var studentid = $(this).closest('.student').attr('data-id');

		if(timers[studentid][3] == 0)
		{			
			startTimer(studentid);
		}
		else
		{
			stopTimer(studentid);
		}
	});

	$('._adjusttime').on('click', function()
	{
		var newBalance = $('#adjusttime').val();

		if(isNaN(newBalance) || parseInt(newBalance) != newBalance)
		{
			alert('Please enter a valid integer.');
			return;
		}

		var selected = $('.selected');

		if(selected.length == 0) return;

		$("#popupadjusttime").popup("close");
		$('.student').removeClass('selected');

		var ids = [];
		for(var i=0;i<selected.length;i++)
		{
			ids[ids.length] = $(selected[i]).attr('data-id');
		}

		if(ids.length > 0)
		{
			console.log('Adjusting '+ids.length+' students');
			adjustTimer(ids.join('_'), newBalance);
		}
	});	

	$('#startAll').on('click', function()
	{
		var ids = [];
		for(var student in timers)
		{
			if(timers[student][3] == 0)
			{
				ids[ids.length] = student;
			}
		}

		if(ids.length > 0)
		{
			console.log('Starting '+ids.length+' students');
			startTimer(ids.join('_'));
		}
	});

	$('#resetAll').on('click', function()
	{
		var ids = [];
		for(var student in timers)
		{
			if(timers[student][3] == 1)
			{
				ids[ids.length] = student;
			}
		}

		if(ids.length > 0)
		{
			console.log('Resetting '+ids.length+' students');
			resetTimer(ids.join('_'));
		}
		else
		{
			console.log("Nothing to reset");
		}
	});

	$('#stopAll').on('click', function()
	{
		var ids = [];
		for(var student in timers)
		{
			if(timers[student][3] == 1)
			{
				ids[ids.length] = student;
			}
		}

		if(ids.length > 0)
		{
			console.log('Stopping '+ids.length+' students');
			stopTimer(ids.join('_'));
		}
	});

	$('._addTime').on('click', function()
	{
		var minutes = $('#detentiontime').val();
		var mode = $('#timemode').val();

		var selected = $('.selected');

		if(selected.length == 0) return;

		$("#popupaddsubtract").popup("close");
		$('.student').removeClass('selected');

		var ids = [];
		for(var i=0;i<selected.length;i++)
		{
			ids[ids.length] = $(selected[i]).attr('data-id');
		}

		if(ids.length > 0)
		{
			$.ajax({
				dataType: "json",
				url: "/api/"+mode+"detentiontime/"+ids.join('_')+"/"+minutes,
			}).done(function(msg) {
				for(var i=0; i<msg.length; i++)
				{
					var s = msg[i];

					timers[s.studentid] = [parseInt(s.timeelapsed), parseInt(s.timeserved), parseInt(s.timeassigned), parseInt(s.active)];

					refreshStudent(s.studentid);

					console.log(timers[s.studentid]);
				}
			});			
		}
		else
		{
			console.log("Nothing to reset");
		}
	});

	$('.student-left').click(function ()
	{
		$(this).parent().toggleClass('selected');
	});

	$('._dismissExpired').on('click', function()
	{
		$('#expired').hide();

		for(var i=0; i<expired.length; i++)
		{
			if(timers[expired[i]][0]+timers[expired[i]][1] >= timers[expired[i]][2])
			{
				$('.student[data-id='+expired[i]+']').remove();
			}
		}

		expired = [];
	});

	function timerTick()
	{
		for(var student in timers)
		{
			if(timers[student][3] == 1)
			{
				timers[student][0]++;

				if(timers[student][0]+timers[student][1] >= timers[student][2])
				{
					console.log('Automatically stopping student #'+student+" with timer:"+timers[student][0]);

					timers[student][2] = 0;
					console.log(timers);
					$(".student[data-id='"+student+"']").removeClass('active').addClass('timeexpired');

					expired.push(student)
					showExpired();

					var doneMsg = $(".student[data-id='"+student+"']").attr('data-name');

					//alert(doneMsg);

					$.ajax({
						dataType: "json",
						url: "/api/stopdetention/"+student,
					}).done(function( msg ) {

						for (var student in msg)
						{
							timers[msg[student].studentid] = [0, msg[student].timeserved, msg[student].timeassigned, 0];

							refreshStudent(msg[student].studentid);

							console.log("updating panel with final stat");
							console.log(msg[student]);

							console.log("timers");
							console.log(timers);
						}
					});
				}

				refreshStudent(student);
			}
		}
	}

	setInterval(timerTick, 1000);

});
</script>

<div data-role="popup" id="popupadjusttime">
	<p>Number of minutes:</p>
	<input type="number" id="adjusttime" min="0" data-mini="true" step="1" data-theme="c" value="1" data-inset="true" />
	<input type="button" class="_adjusttime" value="Adjust" />
</div>

<?php /*
<div data-role="popup" id="popupsendstudent">
	<p>Send to:</p>
	<select id="sendtoselect" data-native-menu="false">
		<?php foreach($teachers as $teacher): ?>
		<option value="<?= $teacher->userid ?>"><?= $teacher->lastname ?>, <?= $teacher->firstname ?></option>
	<?php endforeach; ?>
	</select>
	<p>Notes:</p>
	<textarea id="interventionnotes"></textarea>

	<input type="button" class="_sendstudent" value="Send Student" />
</div> */ ?>

<div data-role="popup" id="popupaddsubtract">
	<div id="option1-container" style="height:50px; margin-bottom:0px;">
	  <div id="option1-container-left" style="float:left; width:40%">
		 <p style="position:relative; margin-top:0px; margin-left:5px;">Time to add or subtract</p>
	  </div>
	  <div id="option1-container-right" style="float:right; width:40%; margin-right:5px;">
		<select style=" margin-top:25px;" id="timemode" data-role="slider">
		<option value="add">+</option>
		<option value="subtract">-</option>
		</select>
	  </div>
	</div>
	<br>
	<input type="range" id="detentiontime" class="slider" min="0" data-mini="true" max="200" value="10" step="5" data-theme="c" />
	<input type="button" class="_addTime" value="Adjust" />	
</div>
