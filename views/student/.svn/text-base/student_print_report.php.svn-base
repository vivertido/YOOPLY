<?php

$event_label = array('report' => 'Report', 'intervention' => $labels->intervention, 'demerit' => $labels->demerit, 'referral' => $labels->referral, 'reinforcement' => $labels->reinforcement, 'detention' => $labels->detention);

function ordinal($number)
{
  $ends = array('th','st','nd','rd','th','th','th','th','th','th');
  if (($number %100) >= 11 && ($number%100) <= 13)
     return $number. 'th';
  else
     return $number. $ends[$number % 10];
}

$is_admin = ($this->session->userdata('role') == 'a');
?>
<style>
#notesdisplay
{
  display:none;
}


@media print {
 #mainHeader, #footer
 {

  display:none;
  }


  .noprint
  {
    display:none;
  }

  #page
  {
    width:8.5in;
  }
  @page {
    margin: 0.5in;
    }

  #notesdisplay 
  {
    display:block;
  }
}
</style>


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      //google.setOnLoadCallback(drawChart);

      $(function()
      {
        $('.graph').on('change', function()
        {
          if($(this).val() == '')
          {
            $('#'+$(this).attr('data-graph')).empty();
            return;
          }

          var container = $(this).attr('data-graph');
          $.getJSON('/api/reportgraph/<?= $student->userid ?>/'+$(this).val(), function(response)
          {
          	var data = google.visualization.arrayToDataTable(response.data);

            var options = {
              title: response.title,
              colors:['darkGray' ],
              fontName:'Dosis',
              pointSize:3,
               
              legend:{
                position:'none'
              }
            };

            var chart = new google.visualization.LineChart(document.getElementById(container));

            chart.draw(data, options);
          });
        });

        $('.pie').on('change', function()
        {
          if($(this).val() == '')
          {
            $('#'+$(this).attr('data-graph')).empty();
            return;
          }

          var container = $(this).attr('data-graph');
          $.getJSON('/api/reportgraph/<?= $student->userid ?>/'+$(this).val(), function(response)
          {
            var data = google.visualization.arrayToDataTable(response.data);

            var options = {
              title: response.title,
              is3D: false,
              pieHole:0.2,
              colors:['#33ccff','#B0E0E6', '#D2B48C', '#5b5b5b', '#696969', '#f0f4f4', '#DB5705', '#DEB887', '#FFA500'],
            };

            var chart = new google.visualization.PieChart(document.getElementById(container));
            chart.draw(data, options);
          });
        });

        $('#signatureenabled').on('change', function()
        {
          if($(this).is(':checked'))
          {
            $('#signature').show();
          }
          else
          {
            $('#signature').hide();
          }
        });

        $('#notescontent').on('change', function()
        {
          $('#notesdisplay').text($(this).val());
        });
      });
    </script>
<div id="summary-print" class="ui-shadow">
	<h2 id="print-report-heading" style="margin-top: 0px">Yooply Student Report - <?= $student->firstname ?> <?= $student->lastname ?> </h2>

 	<div id="print-heading">
 	 <span style="float:left"><?= $student->grade ?>th grade - <?= $school->title ?></span> <span style="float:right">Report date: <?= date('m/d/y') ?></span> 
			<br><hr>
 	</div>
 	<div id="print-body">
		<div class="ui-grid-a">
			<div class="ui-block-a">
  		 	<h4>Last <?= count($incidents) ?> incident<?= count($incidents) == 1 ? '' : 's' ?></h4> <!--Mix of any record the school is keeping, see examples -->
        <table style="font-size: 10pt">
        <?php foreach(array_reverse($incidents) as $incident): 
        if($incident->incidenttype == 'detention'): 
            list($minutes, $reason) = preg_split('/\|/', $incident->label, 2); 
            $incident->label = $minutes.' minute'.($minutes == '1' ? '' : 's').', '.$reason; 
          endif; 
        ?>
          <tr>
            <td><?= date('m/d/y', $incident->timecreated) ?></td>
            <td><?= isset($event_label[$incident->incidenttype]) ? $event_label[$incident->incidenttype] : substr($incident->incidenttype, 0, 12).(strlen($incident->incidenttype) > 12 ? '...' : '') ?></td>
            <td><?= substr($incident->label, 0, 30).(strlen($incident->label) > 30 ? '...' : '') ?></td>
            <td><?= $incident->firstname ?> <?= $incident->lastname ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
  		</div>
  		<div class="ui-block-b" style="height:300px">
        <div class="noprint">
     			<select class="noprint pie" name="piegraph[]" data-graph="piechart" data-mini="true">
            <option value="">none</option>

            <?php if(strpos($settings->demerits, $this->session->userdata('role')) !== false): ?>
              <?php if($is_admin): ?><option value="demeritsbyteacher"><?= $labels->demerits ?> by teacher</option><?php endif; ?>
              <option value="demeritsbyreason"><?= $labels->demerits ?> by reason</option>
            <?php endif; ?>
     				<?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?>
              <?php if($is_admin): ?><option value="referralsbyteacher"><?= $labels->referrals ?> by teacher</option><?php endif; ?>
              <option value="referralsbyreason"><?= $labels->referrals ?> by reason</option>
            <?php endif; ?>
            <?php if(strpos($settings->reinforcements, $this->session->userdata('role')) !== false): ?>
              <?php if($is_admin): ?><option value="reinforcementsbyteacher"><?= $labels->reinforcements ?> by teacher</option><?php endif; ?>
              <option value="reinforcementsbyreason"><?= $labels->reinforcements ?> by reason</option>
            <?php endif; ?>
            <?php if(strpos($settings->detentions, $this->session->userdata('role')) !== false): ?>
              <option value="detentionsbyreason"><?= $labels->detention ?> <?= htmlentities($labels->detentionunits) ?> by reason</option>
              <?php if($is_admin): ?><option value="detentionsbyteacher"><?= $labels->detention ?> <?= htmlentities($labels->detentionunits) ?> by teacher</option><?php endif; ?>
            <?php endif; ?>
            <?php if(strpos($settings->interventions, $this->session->userdata('role')) !== false): ?>
              <option value="interventionsbyreason"><?= $labels->interventions ?> by reason</option>
              <?php if($is_admin): ?><option value="interventionsbyteacher"><?= $labels->interventions ?> by teacher</option><?php endif; ?>
            <?php endif; ?>            
            <?php foreach($forms as $form): ?>
            <?php if($is_admin): ?><option value="formbyteacher/<?= $form->formid ?>"><?= $form->title ?> by teacher</option><?php endif; ?>
            <option value="formbyreason/<?= $form->formid ?>"><?= $form->title ?> by reason</option>
            <?php endforeach; ?>
     			</select>
        </div>
  		  <div id="piechart"></div>
      </div>
    </div>
 	  <h4>Trends to date</h4>
 		<?php $number_of_graphs = 2; for($i=0; $i<$number_of_graphs; $i++): ?>
    <div class="ui-field-contain noprint" style="width:300px">
 			<p>Choose <?= ordinal($i+1) ?> chart</p>
 			 
 			<select class="noprint graph" name="graph[]" data-graph="graph<?= $i ?>" data-mini="true">
        <option value="" selected="selected">none</option>
 				<?php if(strpos($settings->demerits, $this->session->userdata('role')) !== false): ?>
          <option value="demerits"><?= $labels->demerits ?> to date</option>
        <?php endif; ?>
        <?php if(strpos($settings->reinforcements, $this->session->userdata('role')) !== false): ?>
          <option value="reinforcements"><?= $labels->reinforcements ?> to date</option>
        <?php endif; ?>
        <?php if(strpos($settings->referrals, $this->session->userdata('role')) !== false): ?>        
          <option value="referrals"><?= $labels->referrals ?> to date</option>
        <?php endif; ?>
        <?php if(strpos($settings->detentions, $this->session->userdata('role')) !== false): ?>
          <option value="detentions"><?= $labels->detention ?> <?= htmlentities($labels->detentionunits) ?> to date</option>
        <?php endif; ?>
        <?php if(strpos($settings->interventions, $this->session->userdata('role')) !== false): ?>
          <option value="interventions"><?= $labels->interventions ?> to date</option>
        <?php endif; ?>
        <?php foreach($forms as $form): ?>
        <option value="form/<?= $form->formid ?>"><?= $form->title ?></option>
        <?php endforeach; ?>
 			</select>
 		</div>
 		<div id="graph<?= $i ?>"></div>
    <?php endfor; ?>
 	</div>

 	<div id="print-footer">
    <h4>Notes</h4>    
    <div id="notesentry" class="noprint">
      <textarea style="margin-bottom:40px" maxlength="600" id="notescontent"></textarea>
    </div>
    <div id="notesdisplay"></div>
    <div class="noprint">
      <label class="noprint" for="flip-checkbox-4"><input class="noprint" type="checkbox" data-role="flipswitch" name="flip-checkbox-4" id="signatureenabled" checked="" style="margin-right:10px"/> 
      Include signature?</label>
    </div>
    
    <div style="margin-top: 10px">
      <span style="float:right">printed by: <?= $viewer->firstname ?> <?= $viewer->lastname ?></span>
      <span id="signature">Please sign and return: _________________________________________</span> 
    </div>
 	</div>
</div>
<div class="noprint" style="clear:both">
  <input data-role="Button" type="submit" onclick="window.print()" value="Print" style="background-color:'#33ccff'; color:white"/>
</div>