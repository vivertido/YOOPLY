<ul data-role="listview" data-inset="true">
	<?php foreach($forms as $form): ?>
	<li><a href="/form/view/<?= $form->formid ?>"><?= $form->title ?></a></li>
	<?php endforeach; ?>
</ul>

<ul data-role="listview" data-inset="true" data-theme="c">
	<li><a href="/form/add" data-ajax="false">New Form</a></li>
</ul>
