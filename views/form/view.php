<style>

@media screen and (min-device-width: 1000px){


.ui-checkbox{

	position: relative;
	float: left;
	width:50%;
	margin-right:0px;
}
.aHeading3{
	  clear:both;
	 display: block;

}

}
</style>
<div style="opacity:.5">
	<?php $form_data = json_decode($form->formdata); inflate_form($form_data->questions, 'f', true); ?>
</div>

<ul data-role="listview" data-inset="true" style="clear:both">
	<li><a href="/form/edit/<?= $form->formid ?>" data-ajax="false">Edit</a></li>
	<li><a href="/form/actions/<?= $form->formid ?>" data-ajax="false">Set Triggers/Actions</a></li>
	<li><a href="/form/remove/<?= $form->formid ?>">Delete</a></li>
</ul>	
