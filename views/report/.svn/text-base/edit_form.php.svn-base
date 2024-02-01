<?php $response = json_decode($report->report); ?>

<?php $form_data = json_decode($form->formdata); ?>

<form action="/report/edit/<?= $report->reportid ?>" method="POST" data-ajax="false">
<?php inflate_form($form_data->questions, 'f', false, $response); ?>
<input type="submit" name="submit" value="Save Changes" />
</form>