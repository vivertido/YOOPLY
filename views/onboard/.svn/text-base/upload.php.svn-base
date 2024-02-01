Welcome to Yoop.ly

<?php if(isset($errors)): ?>
	Please correct the following problems:<br />
	<ul>
	<li><?= implode('</li><li>', $errors) ?></li>
	</ul>
<?php endif; ?>

<form action="/onboard/upload/<?= $invitecode ?>" method="POST" data-ajax="false" enctype="multipart/form-data"> 
	Students:
	<input type="file" name="students" />

	Parents:
	<input type="file" name="parents" />	

	Teachers:
	<input type="file" name="teachers" />	

	Admins:
	<input type="file" name="admins" />	

	Groups:
	<input type="file" name="groups" />

	<input type="submit" name="submit" value="Next" />
</form>