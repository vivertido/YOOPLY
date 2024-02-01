<script>
$(function()
{
	var studentid;

	$('._servedetention').on('click', function()
	{
		var el = $(this).parents('li');
		studentid = el.attr('data-studentid');

		$.get('/api/servealldetention/'+studentid, function(data)
		{
			if(data.status == 'success')
			{
				if((el.prev('li').hasClass('ui-li-divider') && el.next('li').hasClass('ui-li-divider')) || $('.student').size() == 1)
				{	
					el.prev('.ui-li-divider').remove();
				}
				el.remove();	
			}

			return;
		});



	});

	$('._editdetention').on('click', function()
	{
		var el = $(this).parents('li');
		studentid = el.attr('data-studentid');

		var quantity = parseInt($(this).parents('li').find('.assigned').text());
		$('#quantity').attr('max', quantity);
		$("#quantity").attr("value", quantity);
		
		$('#quantity').slider('refresh');
		$("#popupEdit").popup("open");
	});

	$('._adjustdetention').on('click', function()
	{
		var quantity = $("#quantity").val();
		$.get('/api/servedetention/'+studentid+'/'+quantity, function(data)
		{
			if(data.status == 'success')
			{
				var el = $('li[data-studentid="'+studentid+'"]');

				if(data.amount == 0)
				{
					if((el.prev('li').hasClass('ui-li-divider') && el.next('li').hasClass('ui-li-divider')) || $('.student').size() == 1)
					{	
						el.prev('.ui-li-divider').remove();
					}
					el.remove();
				}
				else
				{
					el.find('.assigned').text(data.amount);
				}

				$("#popupEdit").popup("close");
			}

			return;
		});
	});

	$('._closeedit').on('click', function()
	{
		$("#popupEdit").popup("close");
	});	
});
</script>
<?php if(empty($detentions)): ?>
Yay! None of your students have detention today!
<?php else: ?>

<div style="float:right;margin-bottom:5px"><a href="/detention/mystudents/print" data-ajax="false">Print</a></div>

<div data-role="collapsible-set" data-content-theme="b" id="bankOfQuestions"  data-collapsed="true" style="clear:both">
	<ul data-role="listview" data-inset="true">
		<?php
		$header = '';
		foreach($detentions as $detention): 
			$h = $detention->grade == '0' ? 'Kindergarten' : (addOrdinalNumberSuffix($detention->grade).' Grade');
			if($header != $h) { $header = $h;
?>
		<li data-role="list-divider"><?= $header ?></li>
<?php							}
			?>
		<li class="student" data-studentid="<?= $detention->userid ?>">
			<span class="ui-li-aside"><a class="_servedetention" data-role="button" data-icon="check" data-inline="true" data-iconpos="notext"></a> <a class="_editdetention" data-role="button" data-icon="gear" data-inline="true" data-iconpos="notext">Edit</a></span>
			<h2><?= $detention->lastname ?>, <?= $detention->firstname ?></h2>
			<p><strong><span class="assigned"><?= $detention->total ?></span> <?= htmlentities($labels->detentionunits) ?> to date</strong></p>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

<div style="min-width: 300px" data-role="popup" id="popupEdit" data-theme="a" class="ui-corner-all" data-dismissible="false" data-history="false" style="overflow:auto">
	<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right _closeedit">Close</a>
	<h2>Change</h2>
	How many <?= htmlentities($labels->detentionunits) ?> have been served?<br />
	<input type="range" id="quantity" min="1" max="20" value="1" />
	<input type="button" class="_adjustdetention" value="Save" />
</div>