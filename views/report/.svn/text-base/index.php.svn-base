<?php if(empty($reports)): ?>
	There are no reports to view at this time.
<?php endif; ?>

<ul data-role="listview" data-inset="true">
	<?php foreach($reports as $report): ?>
		<li><a href="/report/view/<?= $report->reportid ?>" data-ajax="false"><?= $report->title ?></a></li>
	<?php endforeach; ?>
</ul>

<?php if($this->session->userdata('role') == 'a'): ?>
<ul data-role="listview" data-inset="true" data-theme="c">
	<li><a href="/report/add/charts" data-ajax="false" >New Report</a></li>
</ul>
<?php endif; ?>