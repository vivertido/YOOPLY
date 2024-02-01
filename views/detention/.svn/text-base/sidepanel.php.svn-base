<script>
 

var activeStudent;
var timers = {};
var expired = [];
<?php // timer => Current timer being served, time served, time assigned, 1 if timer is active ?>
$().ready(function()
{

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

			refreshPanel();
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

			refreshPanel();
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

			refreshPanel();
		});
	}

	$('#start').on('click', function()
	{
	 
		console.log('Starting student #'+activeStudent);
		startTimer(activeStudent);
		//$("#mypanel").panel("close");
		
	});

	$('#stop').on('click', function()
	{
		console.log('Stopping student #'+activeStudent+" with timer:"+timers[activeStudent][0]);
		stopTimer(activeStudent);
		$("#mypanel").panel("close");
	});

	$('#reset').on('click', function()
	{
		console.log('Resetting student #'+activeStudent+" with timer:"+timers[activeStudent][0]);
		resetTimer(activeStudent);
	});

	$('#startAll').on('click', function()
	{ 
	
	}
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
			alert('Starting '+ids.length+' students');
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
		var minutes = $(this).attr('data-minutes');
		var mode = $('#timemode').val();

		$.ajax({
			dataType: "json",
			url: "/api/"+mode+"detentiontime/"+activeStudent+"/"+minutes,
		}).done(function(msg) {
			for(var i=0; i<msg.length; i++)
			{
				var s = msg[i];

				timers[s.studentid] = [parseInt(s.timeelapsed), parseInt(s.timeserved), parseInt(s.timeassigned), parseInt(s.active)];

				refreshStudent(s.studentid);

				console.log(timers[s.studentid]);
				refreshPanel();

				$("#mypanel").panel("close");
			}
		});
	});

	$('.student').click(function() {
		activeStudent = $(this).attr('data-id');
		$('#panelStudentName').text($(this).attr('data-name'));
		refreshPanel();
	});

	$('#timemode').on('change', function()
	{
	 $('.timemode').text($(this).val());
	});

	$('._startStop').click(function()
	{
		var studentid = $(this).parent().parent().attr('data-id');

		if(timers[studentid][3] == 0)
		{
			$(this).addClass('stop').text('Stop');

			startTimer(studentid);
		}
		else
		{
			$(this).removeClass('stop').text('Start');

			stopTimer(studentid);
		}
	});

	$('._tagButton').click(function()
	{
		if($(this).hasClass('highlight'))
		{
			$(this).parent().parent().removeClass('highlight');
			$(this).text('Mark');
			$(this).removeClass('highlight');
		}
		else
		{
			$(this).parent().parent().addClass('highlight');
			$(this).text('Unmark');
			$(this).addClass('highlight');
		}
	});

	$('#showNotes').click(function()
	{
		alert('Need to build a customized note section that teachers can use for internal tracking of status. Notes button will trigger a <select> with options like serving detention with teacher X, or should bring lunch');
	});

	$('#showButtons').click(function()
	{
		$('.listOption').toggle();

		if($('#showButtonsAnchor').hasClass('hide'))
		{
			$('#showButtonsAnchor').text('Show Buttons').removeClass('hide');
		}
		else
		{
			$('#showButtonsAnchor').text('Hide Buttons').addClass('hide');
		}
	});

	function refreshStudent(studentid)
	{
		var timeRemaining = Math.ceil((timers[studentid][2]-timers[studentid][1]-timers[studentid][0])/60);

		$("li[data-id='"+studentid+"'] .remaining").text(Math.max(timeRemaining,0)+' minutes');

		$("li[data-id='"+studentid+"'] .assigned").text(Math.ceil(timers[studentid][2]/60));

		if(timers[studentid][3] == 1)
		{
			$("li[data-id='"+studentid+"']").addClass('activestudent');
			$("li[data-id='"+studentid+"']").removeClass('timeexpired');
		}
		else
		{
			$("li[data-id='"+studentid+"']").removeClass('activestudent');
		}

		if(Math.max(timeRemaining, 0) == 0)
		{
			$("li[data-id='"+studentid+"']").addClass('timeexpired');
		}
	}

	function refreshPanel()
	{
		if(activeStudent in timers)
		{
			$('#panelTimeServed').text(Math.floor(timers[activeStudent][0]/60)+':'+(timers[activeStudent][0]%60 < 10 ? '0'+timers[activeStudent][0]%60 : timers[activeStudent][0]%60));
			var timeRemaining = Math.ceil((timers[activeStudent][2]-timers[activeStudent][1]-timers[activeStudent][0])/60);

			$('#panelTimeAssigned').text(Math.max(timeRemaining, 0));
		}
		else
		{
			$('#panelTimeServed').text('0:00');
		}
	}

	function showExpired()
	{
		var list = '';
		for(var i=0; i<expired.length; i++)
		{
			list += '<li>'+$("li[data-id='"+expired[i]+"']").attr('data-name')+'</li>';
		}

		$('#expiredlist').html('<ul>'+list+'</ul>');
		$('#expired').show();
	}

	$('._dismissExpired').on('click', function()
	{
		$('#expired').hide();

    for(var i=0; i<expired.length; i++)
		{
			$("li[data-id='"+expired[i]+"']").remove();
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
					$("li[data-id='"+student+"']").removeClass('activestudent').addClass('timeexpired');

					expired.push(student)
					showExpired();

					var doneMsg = $("li[data-id='"+student+"']").attr('data-name');
					
					//alert(doneMsg);

					$.ajax({
						dataType: "json",
						url: "/api/stopdetention/"+student,
					}).done(function( msg ) {

						for (var student in msg)
						{
							timers[msg[student].studentid] = [0, msg[student].timeserved, msg[student].timeassigned, 0];

							refreshStudent(msg[student].studentid);
							refreshPanel();

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

		refreshPanel();
	}

	setInterval(timerTick, 1000);

	$('#mypanel').panel({close: function() {$('li.ui-btn-active').removeClass('ui-btn-active'); }});
});
</script>
	<div data-role="panel" id="mypanel" class="main_nav" data-display="overlay"  data-dismissible="true" data-theme="a">
		<h4 id="panelStudentName" style="font-weight:normnal; font-family:'Dosis'">Gilbert Alberts</h4>
		<p style="font-family:'Dosis';font-weight:normnal">Time served: <span id="panelTimeServed"></span></p>
		<p style="font-family:'Dosis';font-weight:normnal">Time remaining: <span id="panelTimeAssigned"></span> min</p>
		<hr>
		<div data-role="controlgroup" data-type="horizontal">
			<button id="stop">Stop</button>
			<button id="start">Start</button>
			<button id="reset">Reset</button>
		</div>
		<select id="timemode" data-role="slider">
			<option value="add">+</option>
			<option value="subtract">-</option>
		</select>
		<hr>
		<h4 style="font-weight:normnal; font-family:'Dosis'">Choose minutes to <span class="timemode">add</span></h4>
		<div data-role="controlgroup" data-type="horizontal">
			<button class="_addTime" data-minutes="5">05</button>
			<button class="_addTime" data-minutes="10">10</button>
			<button class="_addTime" data-minutes="15">15</button>
			<button class="_addTime" data-minutes="20">20</button>
		</div>
		<div data-role="controlgroup" data-type="horizontal">
			<button class="_addTime" data-minutes="25">25</button>
			<button class="_addTime" data-minutes="30">30</button>
			<button class="_addTime" data-minutes="35">35</button>
			<button class="_addTime" data-minutes="40">40</button>
		</div>
		<div data-role="controlgroup" data-type="horizontal">
			<button class="_addTime" data-minutes="45">45</button>
			<button class="_addTime" data-minutes="50">50</button>
			<button class="_addTime" data-minutes="55">55</button>
			<button class="_addTime" data-minutes="60">60</button>
		</div>
		<hr>
		<h4 style="font-weight:normnal;font-family:'Dosis'">Choose days to <span class="timemode">add</span></h4>
		<div data-role="controlgroup" data-type="horizontal">
			<button class="_addTime" data-minutes="720">.5</button>
			<button class="_addTime" data-minutes="1440">1</button>
			<button class="_addTime" data-minutes="2880">2</button>
		</div>
		<div data-role="controlgroup" data-type="horizontal">
			<button class="_addTime" data-minutes="4320">3</button>
			<button class="_addTime" data-minutes="5760">4</button>
			<button class="_addTime" data-minutes="7200">5</button>
		</div>
	</div>
