<script>
$(function()
{
	$('._remove').on('click', function()
	{
		var group = $(this).parents('li').attr('data-group');
		var id = $(this).parents('li').attr('data-id');

		$.ajax('/api/removenotification/'+id);

		if($('li[data-group="'+group+'"').size() == 2)
		{
			$('li[data-group="'+group+'"').fadeOut('slow', function() 
			{ 
				$(this).remove(); 
				
				if($('li[data-group]').size() == 0)
				{
					$('#nonotification').show();
				}
			});
		}
		else
		{
			$(this).parents('li').fadeOut('slow', function() 
			{ 	
				$(this).remove(); 
			});
		}
	});
});
</script>
<div id="nonotification"<?php if(count($notifications) > 0): ?> style="display:none"<?php endif; ?>>
You have no notifications right now.
</div>
<?php if(count($notifications) > 0): ?>
<ul data-role="listview" data-inset="true" data-split-icon="delete" data-split-theme="d">
	<?php
$header = '';
foreach($notifications as $notification):
		

		    $date = date('d/m/Y', $notification->timecreated);
		    switch(true)
		    {
		    	case $date == date('d/m/Y'):
		      		$h = 'Today';
		      	break;
		    	case $date == date('d/m/Y', time() - (24 * 60 * 60)):
		      		$h = 'Yesterday';
		      	break;
		      	default:
		      		$h = date('m/d');
		      	break;
		    }

		    if($h != $header):
		    	$header = $h;
?>
<li data-role="list-divider" data-group="<?= md5($header) ?>"><?= $header ?></li>
<?php
		    endif;
?>
<li data-group="<?= md5($header) ?>" data-id="<?= $notification->notificationid ?>"><a href="/<?= $notification->link ?>">
						<h2><?= $notification->message ?></h2>
						<p class="ui-li-aside"><?= date('g:i a', $notification->timecreated); ?></p>
					</a><a href="#" data-rel="popup" data-position-to="window" data-transition="pop" class="_remove">Remove</a></li>
<?php endforeach; ?>

				</ul>
				<?php endif; ?>