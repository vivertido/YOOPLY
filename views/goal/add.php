<?php 
$objects = array(
	'demerit' => $labels->demerits, 
	'intervention' => $labels->interventions,
	'reinforcement' => $labels->reinforcements,
	'detention' => $labels->detentionunits
); //array('detention' => 'detentions', 'reinforcements' => 'awards', 'demerit' => 'demerits', 'referral' => 'referrals');

foreach($forms as $form):
	$objects['form_'.$form->formid] = $form->title.' reports';
endforeach;

natcasesort($objects); // Sort by titles case-insensitive

if(isset($error)): ?>
<?= $error ?>
<?php endif; ?>
<script>
$().ready(function() {
	$(".selectperson").autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "/api/findconnected/"+request.term+'/all',
				dataType: "json",
				success: function( data ) {
					response($.map( data.names, function( item ) {
						return {
							label: item.name,
							value: item.name,
							userid: item.userid
						}
					}));
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			$('#notify').append('<li><input type="hidden" name="notify[]" value="'+ui.item.userid+'" /><a href="#">'+ui.item.label+'</a><a href="#" class="_removep" data-rel="popup" data-position-to="window" data-transition="pop"></a></li>');
			$('#notify').listview("refresh");
			$(this).val('');
			return false;
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});

	$('#notify').on('click', '._removep', function()
	{
		$(this).parents('li').remove();
	});
});
</script>
<p>Use this form to automatically set triggers when goal is met, including notifications.</p>
<form action="/goal/add/<?= $student->userid ?>" method="POST" data-ajax="false">
	<div style="background-color:#ffffff; padding:15px;" class="ui-shadow">
		<h3>Goal</h3>
		<p><?= $student->firstname ?> <?= $student->lastname ?> will receive 
			<select name="goaltype" data-inline="true">
				<?php foreach(array(
					'atleast' => 'at least', 
					'atmost' => 'at most'
				) as $k=>$v): ?>
				<option value="<?= $k ?>"><?= $v ?></option>
			<?php endforeach; ?>
			</select>

			<input type="text" placeholder="quantity" name="quantity" value="5" data-role="none" />
			<select id="behavior-select" name="metric" data-inline="true">
				<?php foreach($objects as $k=>$v): ?>
				<option value="<?= $k ?>"><?= $v ?></option>
				<?php endforeach; ?>
			</select>
			by <input type="date" name="timedue" data-role="none" width="10" value="<?= date('Y-m-d', time()+(7*3600*24)) ?>" />
		</p>

		<hr/>

		<h3>Notes</h3>
		<textarea name="notes" placeholder="notes" value=""></textarea>

		<h3>Notify</h3>
		<p>Select who will be notified when this goal is created, edited and met.</p>
		<?php foreach(array(
				$student->userid => $student->firstname.' '.$student->lastname,
				'teachers' => $student->firstname.'\'s teachers', 
				'parents' => $student->firstname.'\'s parents', 
				'admins' => 'Admins'
		) as $k=>$v): ?>
			<label for="<?= md5($k) ?>"><input type="checkbox" name="notify[]" value="<?= $k ?>" id="<?= md5($k) ?>"><?= $v ?></label>
		<?php endforeach; ?>
		
		<ul data-role="listview" data-inset="true" id="notify" data-split-icon="delete">

		</ul>
		<input type="text" name="person" class="selectperson" placeholder="Search by name..." />	

		<input type="submit" name="submit" value="Add Goal" />
	</div>
</form>