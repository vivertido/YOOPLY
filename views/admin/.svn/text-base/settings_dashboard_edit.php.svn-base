<?php $roles = array('teacher' => 'Teacher', 'admin' => 'Admin', 'student' => 'Student', 'parent' => 'Parent'); ?>

<?= $roles[$role] ?>

<?php 

$options = array(
	'teacher' => array(
			'Student lists' => array('/teacher/students' => 'My Students'),
			'Reports' => array('/report' => 'Reports'),
			$labels->detentions => array('/detention/mystudents' => 'Today\'s '.$labels->detentions),
			'Interventions' => array(
				'/intervention/mine' => 'Interventions - Today', 
				'/intervention/mine/week' => 'Interventions - This Week', 
				'/intervention/mine/month' => 'Interventions - This Month'
			),
			'Goals' => array(
				'/goal/mine' => 'Goals - Today', 
				'/goal/mine/week' => 'Goals - This Week', 
				'/goal/mine/month' => 'Goals - This Month'
			),			
			'Negative Behaviors' => array(
				'/demerit/mine' => 'Negative Behaviors - Today', 
				'/demerit/mine/week' => 'Negative Behaviors - This Week', 
				'/demerit/mine/month' => 'Negative Behaviors - This Month'
			),
			'Reinforcements' => array(
				'/reinforcement/mine' => 'Reinforcements - Today', 
				'/reinforcement/mine/week' => 'Reinforcements - This Week', 
				'/reinforcement/mine/month' => 'Reinforcements - This Month'
			),			
			'Referrals' => array(
				'/referral/mine' => 'Referrals - Today', 
				'/referral/mine/week' => 'Referrals - This Week', 
				'/referral/mine/month' => 'Referrals - This Month'
			)		
	),
	'admin' => array(
			'Student lists' => array('/admin/students' => 'My Students'),
			'Reports' => array(
				'/report' => 'Reports',
			),
			$labels->detentions => array('/detention/mystudents' => 'Today\'s '.$labels->detentions),
			'Interventions' => array(
				'/intervention/school' => 'Interventions - Today', 
				'/intervention/school/week' => 'Interventions - This Week', 
				'/intervention/school/month' => 'Interventions - This Month'
			),
			'Goals' => array(
				'/goal/school' => 'Goals - Today', 
				'/goal/school/week' => 'Goals - This Week', 
				'/goal/school/month' => 'Goals - This Month'
			),				
			'Negative Behaviors' => array(
				'/demerit/school' => 'Negative Behaviors - Today', 
				'/demerit/school/week' => 'Negative Behaviors - This Week', 
				'/demerit/school/month' => 'Negative Behaviors - This Month'
			),
			'Reinforcements' => array(
				'/reinforcement/school' => 'Reinforcements - Today', 
				'/reinforcement/school/week' => 'Reinforcements - This Week', 
				'/reinforcement/school/month' => 'Reinforcements - This Month'
			),
			'Referrals' => array(
				'/referral/school' => 'Referrals - Today', 
				'/referral/school/week' => 'Referrals - This Week', 
				'/referral/school/month' => 'Referrals - This Month'
			)	
	),
	'student' => array(
			'Rewards' => array(
				'/student/awards' => 'My Classroom Bucks'
			),
			$labels->detentions => array(
				'/student/mydetentions' => 'My '.$labels->detentions
			),
			'Shoutouts' => array(
				'/student/shoutouts' => 'Shout-outs!', 
				'/shoutout/send' => 'New Shoutout'
			),
			'Interventions' => array(
				'/intervention/mine' => 'My Interventions - Today', 
				'/intervention/mine/week' => 'My Interventions - This Week', 
				'/intervention/mine/month' => 'My Interventions - This Month',
				'/intervention/mine/all' => 'My Interventions - All'
			),
			'Goals' => array(
				'/goal/mine' => 'Goals - Today', 
				'/goal/mine/week' => 'My Goals - This Week', 
				'/goal/mine/month' => 'My Goals - This Month',
				'/goal/mine/all' => 'My Goals - All'
			),			
			'Negative Behaviors' => array(
				'/demerit/mine' => 'My Behavior Incidents',
				'/demerit/mine/week' => 'My Behavior Incidents - This Week',
				'/demerit/mine/month' => 'My Behavior Incidents - This Month',
				'/demerit/mine/all' => 'My Behavior Incidents - All'
			),
			'Reinforcements' => array(
				'/reinforcement/mine' => 'My Reinforcements - Today', 
				'/reinforcement/mine/week' => 'My Reinforcements - This Week', 
				'/reinforcement/mine/month' => 'My Reinforcements - This Month',
				'/reinforcement/mine/all' => 'My Reinforcements - All'
			),			
			'Referrals' => array(
				'/referral/mine' => 'My Referrals - Today', 
				'/referral/week' => 'My Referrals - This Week', 
				'/referral/month' => 'My Referrals - This Month', 
				'/reflections/mine' => 'My Reflections'
			)
	)
);

$form_options = array();
foreach($forms as $form)
{
	if(strpos($form->viewers, substr($role, 0, 1)) !== false)
	{
		$options[$role]['Reports']['/report/form/'.$form->formid.'/today'] = $form->title.' - Today';
		$options[$role]['Reports']['/report/form/'.$form->formid.'/week'] = $form->title.' - This Week';
		$options[$role]['Reports']['/report/form/'.$form->formid.'/month'] = $form->title.' - This Month';
	}

	if(!empty($form->subject))
	{
		continue;
	}

	if(strpos($form->contributors, substr($role, 0, 1)) !== false)
	{
		$form_options['/form/respond/'.$form->formid] = $form->title;
	}
}

foreach($reports as $report)
{
	$options[$role]['Reports']['/report/view/'.$report->reportid] = $report->title;
}

if($role == 'admin')
{
	$form_options['/form'] = 'Forms';
}

if(!empty($form_options))
{
	$options[$role]['Forms'] = $form_options;
}
?>
<script>
$(function()
{
	$('._addanotheritem').on('click', function()
	{
		$(this).before($('#menuitemtemplate').html());
	});
})
</script>
<form action="/admin/settings/dashboard/<?= $role ?>" method="POST" data-ajax="false">
	The values in the left column will be shown in the dashboard menu. Use the dropdown to select where the <?= strtolower($roles[$role]) ?> will be taken to. <?php
foreach($menu->menu as $k=>$v): ?>
	<div class="ui-grid-a">
		<div class="ui-block-a"><input type="text" name="menu[]" value="<?= htmlentities($v) ?>" /></div>
		<div class="ui-block-b"><select name="path[]" data-mini="true">
		<?php foreach($options[$role] as $g_k=>$group): ?>
			<optgroup label="<?= $g_k ?>">
				<?php foreach($group as $p=>$title): ?>
					<option value="<?= $p ?>"<?= $k == $p ? ' selected="selected"' : '' ?>><?= $title ?></option>
				<?php endforeach; ?>
			</optgroup>
		<?php endforeach; ?>
		</select></div>
	</div>

<?php endforeach; ?>
	<a href="#" class="_addanotheritem">Add another item</a>
<input type="submit" name="submit" value="Save Changes" data-theme="c" />
</form>

<div style="display:none" id="menuitemtemplate">
		<div class="ui-grid-a">
		<div class="ui-block-a"><input type="text" name="menu[]" value="" /></div>
		<div class="ui-block-b"><select name="path[]" data-mini="true">
		<?php foreach($options[$role] as $g_k=>$group): ?>
			<optgroup label="<?= $g_k ?>">
				<?php foreach($group as $p=>$title): ?>
					<option value="<?= $p ?>"><?= $title ?></option>
				<?php endforeach; ?>
			</optgroup>
		<?php endforeach; ?>
		</select></div>
	</div>
</div>