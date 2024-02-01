<h2> Choose Settings to Edit</h2>
<p>Yooply is made to fit your own school needs. Settings can be made to fit a wide variety of school discipline plans or practices. Choose an item from below to set what your users will see, what behaviors you want to track and reward, and what features you want to enable.</p>
<ul data-role="listview" data-inset="true">
<?php /*	<li><a href="/admin/settings/school" data-ajax="false"><h3>School Settings</h3><p>Set your Student Information System preferences and general school policy</p></a></li>
	<li><a href="/admin/settings/user" data-ajax="false"><h3>User settings</h3><p>Set authorizations for user access and admin rights </p></a></li> */ ?>
	<?php if(strpos($features->referrals, $this->session->userdata('role')) !== false): ?>
	<li><a href="/admin/settings/referrals" data-ajax="false"><h3>Referrals</h3><p>Manage referral process and flow</p></a></li>
	<li><a href="/admin/settings/motivations" data-ajax="false"><h3>Referral motivations</h3><p>Specify motivations for student behaviors</p></a></li>
	<li><a href="/admin/settings/incident" data-ajax="false"><h3>Student referral questionaire</h3><p>Manage student referral questionaire questions</p></a></li>
	<?php endif; ?>
	<?php if(strpos($features->demerits, $this->session->userdata('role')) !== false): ?>
	<li><a href="/admin/settings/demerits" data-ajax="false"><h3>Negative Behaviors</h3><p>Customize and edit negative behavior options and lists</p></a></li>
	<?php endif; ?>
	<?php if(strpos($features->reinforcements, $this->session->userdata('role')) !== false): ?>
	<li><a href="/admin/settings/reinforcements" data-ajax="false"><h3>Positive Reinforcement</h3><p>Customize and edit positive behaviors and manage positive reinforcement settings, including currency and peer reinforcement </p></a></li>
	<?php endif; ?>
	<?php if(strpos($features->detentions, $this->session->userdata('role')) !== false): ?>
	<li><a href="/admin/settings/detentions" data-ajax="false"><h3>Detention Settings</h3><p>Set detention reasons</p></a></li>
	<?php endif; ?>
	<?php if(strpos($features->interventions, $this->session->userdata('role')) !== false): ?>
	<li><a href="/admin/settings/interventions" data-ajax="false"><h3>Interventions</h3><p>Customize in-class and other pre-referral intervention lists and preferences </p></a></li>
	<?php endif; ?>
	<?php if(strpos($features->referrals, $this->session->userdata('role')) !== false): ?>
	<li><a href="/admin/settings/reflection" data-ajax="false"><h3>Student Statements</h3><p>Customize student questions and options for referral reflections, witness statements and positive  </p></a></li>
	<?php endif; ?>
	<?php if(strpos($features->referrals, $this->session->userdata('role')) !== false): ?>
	<? // <li><a href="#0.1_" data-ajax="false"><h3>Notifications and Alert</h3><p>Manage user notification levels and alerts for referrals, positive reinforcement and detentions </p></a></li> ?>
	<li><a href="/admin/settings/consequences" data-ajax="false"><h3>Consequences</h3><p>Customize consequences</p></a></li>
	<?php endif; ?>
	<li><a href="/admin/settings/status" data-ajax="false"><h3>Statuses</h3><p>Customize statuses students can have</p></a></li>	
	<li><a href="/admin/settings/features" data-ajax="false"><h3>Features</h3><p>Enable and disable Yoop.ly features for your school</p></a></li>		
	<li><a href="/admin/settings/dashboard" data-ajax="false"><h3>Dashboard Menus</h3><p>Configure the dashboard menus for each user type</p></a></li>		
	<li><a href="/admin/settings/authentication" data-ajax="false"><h3>Authentication</h3><p>Configure how users login into Yooply</p></a></li>		
	<?php /*Not finished <li><a href="/admin/settings/sms" data-ajax="false"><h3>SMS messages</h3><p>Configure the messages that are sent.</p></a></li>			*/ ?>
	
	<?php // <li><a href="/admin/settings/form" data-ajax="false"><h3>Forms</h3><p>Create and modify custom forms</p></a></li> ?>
</ul>