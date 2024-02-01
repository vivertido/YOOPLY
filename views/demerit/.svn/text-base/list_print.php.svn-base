<script>
window.print();
</script>
<style>

@media print{  
  	.ui-header, .ui-footer
  	{
  		 display:none; 
  	}
  	 
  	.print
	{
		page-break-after:always;
		/*height:975px;*/
		margin-left:50px;
	}
}
</style>
<?php foreach($demerits as $demerit): $student = new stdClass(); $student->firstname = $demerit->studentfirstname; $student->lastname = $demerit->studentlastname; ?>
<div class="print">
<h3>Yooply report</h3>
<hr/>
<?php $this->load->view('demerit/view', array('demerit' => $demerit, 'mode' => 'print', 'student' => $student)); ?>
</div>
<?php endforeach; ?>