<script>
$(function()
{
	var id = 0;

	$('#sortable').on('click', '._remove', function()
	{
		$(this).parents('li').find('*').remove();
	});

	$('._additem').on('click', function()
	{
		$('#elements').append($('#template'+$(this).attr('data-type')).html().replace(/__ID__/g, id));
		id++;
		$('#addItemMenu').popup('close');
		$('#page').trigger("refresh");
	});
});
</script>
<?php

function row($items, $item_name, $item_key, $title = 'title', $selected_id = '')
{
?>
	<?php foreach($items as $item): ?>
		<option value="<?= $item_name ?>/<?= $item->$item_key ?>"<?php if($item->$item_key == $selected_id): ?> selected="selected"<?php endif; ?>><?= htmlentities($item->$title) ?> (<?= $item_name ?> #<?= $item->$item_key ?>)</option>
	<?php endforeach; ?>
<?php	
}
?>
<form action="/library/add" method="POST">
	<div id="elements">
	</div>

	<a href="#addItemMenu" id="addElement" data-rel="popup" data-position-to="window" data-role="button" data-inline="true"  data-theme="c" data-transition="fade" style="border-radius:50px; border-style:solid;"> + </a>

	<br /><br />
	Package Title: <input type="text" name="title" />
	Description: <textarea name="description"></textarea>

	<input type="submit" name="submit" value="Add Package" />
</form>

<div id="templateform" style="display:none" data-role="none">
	<input type="hidden" name="elements[__ID__]" value="form" />
	<select name="forms[__ID__]">
		<option value="">Select...</option>

		<?php row($forms, 'form', 'formid', 'title', '') ?>
	</select>
</div>
<div id="templatereport" style="display:none" data-role="none">
	<input type="hidden" name="elements[__ID__]" value="charts" />
	<select name="charts[__ID__]">
		<option value="">Select...</option>

		<?php row($reports, 'report', 'reportid', 'title', '') ?>
	</select>
</div>
<div id="templatedashboard" style="display:none" data-role="none">
	<input type="hidden" name="elements[__ID__]" value="dashboard" />
	<div class="ui-grid-b">
		<div class="ui-block-a">User Dashboard:<br />
			<select name="role[__ID__]">
				<option value="admin">Admin</option>
				<option value="teacher">Teacher</option>
				<option value="student">Student</option>
			</select>
		</div>
		<div class="ui-block-b">Title<br />
			<input type="text" name="titles[__ID__]" />
		</div>		
		<div class="ui-block-c">Link to<br />
			<select name="link[__ID__]">
				<?php row($forms, 'form/respond/', 'formid', 'title', '') ?>
				<?php row($reports, 'report/view/', 'reportid', 'title', '') ?>
			</select>
		</div>		
	</div>
</div>

<div data-role="popup" id="addItemMenu" data-overlay-theme="b">
	<ul data-role="listview" data-inset="true" style="width:180px;" data-theme="b">
		<li><a class="_additem" data-before="addElement" data-type="form">Form</a></li>
		<li><a class="_additem" data-before="addElement" data-type="report">Report</a></li>
		<li><a class="_additem" data-before="addElement" data-type="dashboard">Dashboard</a></li>
	</ul>
</div>