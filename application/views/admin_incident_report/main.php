<script>
	$(document).ready(function () {
<?php if (validation_errors()) : ?>
	$('#add_incident_report').modal({
		show: 'true'
	})
<?php endif; ?>
	});
</script>
<style>
	.dropdown-menu li{
		cursor: pointer;
	}
	.dropdown-menu{
		position:initial;
	}
	.table > tbody > tr > td {
		vertical-align: middle;
	}
</style>
<?php

function determineStatus($status)
{
    if ($status == 0) {
        echo '<span class = "badge badge-secondary">Finished</span>';
    } else {
        echo '<span class = "badge badge-danger" style = "background:#ff3232;">Active</span>';
    }
}
?>
<script>
	$(function () { /* DOM ready */
		if ($(this).find(":selected").attr("data-type") == "other") {
			$("#classification_other").show();
			$("#nature").prop("disabled", false);
		} else {
			$("#classification_other").hide();
			$("#nature").prop("disabled", true);
		}


		$('#classification').change(function () {
			if ($(this).find(":selected").attr("data-type") == "other") {
				//Creation of new violation
				$("#nature").prop("disabled", false);
				$("#classification_other").show();
			} else {
				//Existing Violation
				$("#classification_other").hide();
				$("#nature").prop("disabled", true);
				$("#nature").val($(this).find(":selected").attr("data-type"));
			}
		});

		$('.datetimepicker').datetimepicker({
			maxDate: moment()
		}).on('dp.show', function () {
			$('#datetimepicker').data("DateTimePicker").maxDate(moment());
		});

	});
</script>

<div class="row">
	<ol class="breadcrumb">
		<li><a href="<?= base_url(); ?>AdminDashboard">
				<em class="fa fa-home"></em>
			</a></li>
		<li class="active">Incident Report</li>
	</ol>
</div><!--/.row breadcrumb-->

<div class = "row">
	<div class="col-xs-12 text-right">
		<br/>
		<button type ="button" class="btn btn-primary" data-toggle = "modal" data-target = "#add_incident_report"><i class="fa fa-plus" ></i> Add Incident Report</button>
		<button type ="button" class="btn btn-info" data-toggle = "modal" data-target = "#view_request_reports"><i class="fa fa-eye" ></i> View Request Reports</button>
	</div>
</div>

<div class = "row">
	<div class = "col-md-12">
		<h1><?= $cms->incident_report_title; ?></h1>
		<h5><?= $cms->incident_report_text; ?></h5>
		<div class ="table-responsive">
			<table class="table table-striped datatable" style="width:100%">
				<thead>
					<tr>
						<th>Date &amp; Time</th>
						<th>Status</th>
						<th>Student</th>
						<th>Reported By</th>
						<th>Violation</th>
						<th>Sanction</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($incident_reports) : ?>
						<?php foreach ($incident_reports as $report) : ?>
							<tr>
								<td><span class = "hidden"><?= $report->incident_report_datetime; ?></span><?= date('F d, Y \a\t h:i A', $report->incident_report_datetime); ?></td>
								<td><?= determineStatus($report->incident_report_status); ?></td>
								<td><?= $report->user_firstname.' '.($report->user_middlename == '' ? '' : substr($report->user_middlename, 0, 1).'. ').$report->user_lastname; ?></td>
								<td>
									<?php
                                if ($report->reportedby_id != '') {
                                    //if REPORTED_BY teacher, get user's name
                                    echo $report->reportedby_firstname.' '.($report->reportedby_middlename == '' ? '' : substr($report->reportedby_middlename, 0, 1).'. ').$report->reportedby_lastname;
                                    echo " <small class = 'text-muted'><b>(".$report->reportedby_access.')</b></small>';
                                } else {
                                    //if REPORTED_BY admin, get admin's name
                                    echo 'Admin';
                                }
                                ?>
								</td>

								<td><?= ucfirst($report->violation_name); ?></td>
								<td><?= ucfirst($report->effect_name); ?></td>
								<td>
									<div class="btn-group-vertical" role="group">
										<button type = "button" class="btn btn-primary" data-toggle="modal" data-target="#details_<?= sha1($report->incident_report_id); ?>">Details</button>
										<?php if ($report->incident_report_status != 0) : ?>
											<a href="<?= base_url(); ?>AdminIncidentReport/edit_exec/<?= $report->incident_report_id; ?>" class="btn btn-warning">Edit</a>
											<a href="<?= base_url(); ?>AdminIncidentReport/print_tempId_exec/<?= $report->incident_report_id; ?>" class="btn btn-info" target="_blank">Print Gatepass ID</a>
										<?php endif; ?>
									</div>
								</td>
							</tr>

							<!-- DETAILS MODAL -->
						<div class="modal fade text-left" id="details_<?= sha1($report->incident_report_id); ?>" tabindex="-1" role="dialog" aria-labelledby="detailsTitle" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h3 class="modal-title" id="detailsTitle">Details</h3>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-xs-12">
												<div class="row">
													<div class="col-xs-12">
														<center>
															<?php if($report->user_id != NULL):?>
															<img src="<?= base_url().$report->user_picture; ?>" class="img-responsive img-circle" width="150">
															<?php endif;?>
															<h4><?= $report->user_firstname.' '.($report->user_middlename != '' ? $report->user_middlename : '').' '.$report->user_lastname; ?></h4>
															<h5><?= ucfirst($report->user_access); ?></h5>
															<h6><?= determineStatus($report->incident_report_status); ?></h6>

															<?php if ($report->incident_report_status == 1) : ?>
																<?php if ($report->effect_id == 1) : ?>
																	<a href="<?= base_url().'AdminDusap/view_exec/'.$report->incident_report_id."/".$report->user_id; ?>" class="btn btn-primary"><i class="fa fa-search"></i> Manage DUSAP Attendance</a>
																<?php elseif ($report->effect_id == 2) : ?>
																	<a href="#" data-toggle = "modal" data-target = "#view_suspension_detail" class="btn btn-primary">See Suspension Details</a>
																<?php elseif ($report->effect_id == 3) : ?>
																	<a href="#" data-toggle = "modal" data-target = "#view_nonReadmission_detail" class="btn btn-primary">See Non Readmission Details</a>
																<?php else : ?>
																	<a href="#" data-toggle = "modal" data-target = "#view_expulsion_detail"  class="btn btn-primary">See Expulsion Details</a>
																<?php endif; ?>
															<?php else : ?>
																<a href="<?= base_url().'AdminOffenseReport/view_exec/'.$report->incident_report_id."/".$report->user_id; ?>" class="btn btn-primary"><i class="fa fa-file-alt"></i> Offense Report</a>
															<?php endif; ?>
														</center>
													</div>
													<div class="col-xs-6 margin-top-lg text-center">
														<h5><strong>Reported By:</strong></h5>
														<span><?php
                                                                            if ($report->reportedby_id != '') {
                                                                                //if REPORTED_BY teacher, get user's name
                                                                                echo $report->reportedby_firstname.' '.($report->reportedby_middlename == '' ? '' : substr($report->reportedby_middlename, 0, 1).'. ').$report->reportedby_lastname;
                                                                                echo " <small class = 'text-muted'><b>(".$report->reportedby_access.')</b></small>';
                                                                            } else {
                                                                                //if REPORTED_BY admin, get admin's name
                                                                                echo 'Admin';
                                                                            }
                                                                            ?></span>
														<br/>
														<br/>
														<h5><strong>Place</strong></h5>
														<span><?= $report->incident_report_place; ?></span>
														<br/>
														<br/>
														<h5><strong>Sanction</strong></h5>
														<span><?= $report->effect_name; ?></span>
													</div>
													<div class="col-xs-6  margin-top-lg  text-center">
														<h5><strong>Violation</strong></h5>
														<span><?= ucfirst($report->violation_name); ?></span>
														<br/>
														<br/>
														<h5><strong>Time</strong></h5>
														<span><?= date('F d, Y \a\t h:i A', $report->incident_report_datetime); ?></span>
														<br/>
														<br/>
														<h5><strong>Message</strong></h5>
														<p><?= $report->incident_report_message; ?></p>
													</div>
													<?php if(isset($report->img_src) && $report->img_src):?>
													<div class="col-xs-12">
														<center>
															<h5><strong>Image</strong></h5>
															<img src="<?= base_url().'uploads/images/'.$report->img_src?>" width="350" class="thumbnail"/>
														</center>
													</div>
													<?php endif;?>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div> <!--END DETAILS MODAL-->
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- ADD INCIDENT REPORT MODAL -->
<div class="modal fade" id="add_incident_report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form action = "<?= base_url(); ?>AdminIncidentReport/incident_report_exec" method="POST" autocomplete="off">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Add Incident Report</h4>
				</div>
				<div class="modal-body">
					<div class = "row">
						<div class = "col-sm-8">
							<div id = "classification_other" class="form-group <?= !empty(form_error('classification_other')) ? 'has-error' : ''; ?>">
								<small class="control-label">Name of Violation</small>
								<input type = "text" name = "classification_other" class="form-control " placeholder = "Name of Violation" value = "<?= set_value('classification_other'); ?>">
								<small><?= form_error('classification_other'); ?></small>
								<br/>
							</div>
							<span>Classification of Offense/Violation</span>
							<select id = "classification" name = "classification" class = "form-control">
								<option disabled="disabled" style = "background:#ddd;">-- Major --</option>
								<?php
                            foreach ($major_violations as $violation) {
                                ?>
									<option value = "<?= $violation->violation_id; ?>" data-type = "<?= $violation->violation_type; ?>" title = "<?= ucfirst($violation->violation_name); ?>" <?= set_select('classification', $violation->violation_id); ?>><?= ucfirst($violation->violation_name); ?></option>    
									<?php
                            }
                            ?>
								<!-- <option disabled="disabled" style = "background:#ddd;">-- New Violation --</option> -->

								<!-- <option value="0" data-type = "other" title = "Other Violation" <?= set_select('classification', '0'); ?>>Other Violation</option> -->
							</select>

						</div>
						<div class = "col-sm-4">
							<span class="control-label">Nature of Violation</span>
							<select id = "nature" name = "nature" class = "form-control">
								<option value="major" <?= set_select('nature', 'major'); ?>>Major</option>
								<option value="minor" <?= set_select('nature', 'minor'); ?>>Minor</option>
							</select>
						</div>
					</div>
					<br/>
					<div class = "row">
						<div class = "col-sm-4 <?= !empty(form_error('date_time')) ? 'has-error' : ''; ?>">
							<span class="control-label" id="date_time">Date &AMP; Time</span>
							<input type="text" class="form-control datetimepicker" name = "date_time" placeholder="Type Here" aria-describedby="date_time" value = "<?= set_value('date_time'); ?>">
							<small><?= form_error('date_time'); ?></small>
						</div>
						<div class="col-sm-4">
							<span class="control-label">Sanction</span>
							<select id = "effect" name = "effect" class = "form-control">
								<?php foreach ($effects as $effect) : ?>
								<option value="<?= $effect->effect_id; ?>" <?= set_select('effect', $effect->effect_id); ?>><?= $effect->effect_name; ?></option>
								<?php endforeach; ?>
							</select>
							<small><?= form_error('effect'); ?></small>
						</div>
						<div class = "col-sm-4 <?= !empty(form_error('place')) ? 'has-error' : ''; ?>">
							<span class="control-label">Place of the Offense Committed</span>
							<input type="text" class="form-control" name = "place" placeholder="Type Here" value = "<?= set_value('place'); ?>">
							<small><?= form_error('place'); ?></small>
						</div>
					</div>
					<br/>
					<div class ="row">
						<div class = "col-sm-8 col-sm-offset-2 <?= !empty(form_error('user_number')) ? 'has-error' : ''; ?>" >
							<span class="control-label">User Number</span><br/>
							<input onkeypress = 'return keypresshandler(event)' maxlength="9" type="text" class="form-control" name = "user_number" id = "user_number" placeholder="Type Here" readonly value = "<?= set_value('user_number'); ?>" >          
							<small><?= form_error('user_number'); ?></small>
						</div>
					</div>
					<div class ="row">
						<div class = "col-sm-8 col-sm-offset-2">
							<br/>
							<div class = "<?= !empty(form_error('user_lastname')) ? 'has-error' : ''; ?>">
								<span class="control-label">Lastname</span><br/>
								<input type="text" class="form-control autocomplete2" name = "user_lastname" id = "user_lastname" placeholder="Lastname" data-toggle="dropdown" value="<?= set_value('user_lastname'); ?>">
								<ul class="dropdown-menu" role="menu" id = "user_lastname_menu" style="width:100%;"></ul>          
								<small><?= form_error('user_lastname'); ?></small>
								<br/>
							</div>
							<div class = "<?= !empty(form_error('user_firstname')) ? 'has-error' : ''; ?>">
								<span class="control-label">Firstname</span><br/>
								<input type="text" class="form-control" name = "user_firstname" id = "user_firstname" placeholder="Firstname" readonly="" value = "<?= set_value('user_firstname'); ?>">
								<small><?= form_error('user_firstname'); ?></small>
								<br/>
							</div>
							<span class="control-label">Middlename</span><br/>
							<input type="text" class="form-control" name = "user_middlename" id = "user_middlename" placeholder="Middlename" readonly="">
						</div>
					</div>
					<div class = "row">
						<div class = "col-xs-4 col-xs-offset-2 <?= !empty(form_error('user_course')) ? 'has-error' : ''; ?>">
							<br/>
							<span class="control-label">Course</span><br/>
							<input type="text" class="form-control" name = "user_course" id = "user_course" placeholder="Course" readonly="" value = "<?= set_value('user_course'); ?>">
							<small><?= form_error('user_course'); ?></small>
						</div>
						<div class = "col-xs-4 <?= !empty(form_error('user_access')) ? 'has-error' : ''; ?>">
							<br/>
							<span class="control-label">User Access</span><br/>
							<input type="text" class="form-control" name = "user_access" id = "user_access" placeholder="User Access" readonly="" value = "<?= set_value('user_access'); ?>">
							<small><?= form_error('user_access'); ?></small>
						</div>
					</div>

					<!--
					<div class="row">
						<div class = "col-xs-3 col-xs-offset-2 <?= !empty(form_error('user_age')) ? 'has-error' : ''; ?>">
							<br/>
							<span class="control-label">Age</span><br/>
							<input type="text" maxlength="3" onkeypress = 'return keypresshandler(event)' class="form-control" name = "user_age" id = "user_age" placeholder="Age" value = "<?= set_value('user_age'); ?>">
							<small><?= form_error('user_age'); ?></small>
						</div> 
						<div class = "col-xs-8 col-xs-offset-2 <?= !empty(form_error('user_section_year')) ? 'has-error' : ''; ?>">
							<br/>
							<span class="control-label">Section/Year</span><br/>
							<input type="text" class="form-control" name = "user_section_year" id = "user_section_year" placeholder="Type Here" value = "<?= set_value('user_section_year'); ?>">
							<small><?= form_error('user_section_year'); ?></small>
						</div>
					</div>
					-->
					<div class = "row">
						<div class = "col-xs-8 col-xs-offset-2 <?= !empty(form_error('message')) ? 'has-error' : ''; ?>">
							<br/>
							<span class="control-label">Message</span><br/>
							<textarea class="form-control" rows ="5" name = "message" style = "resize: none;" placeholder="Write a message. . ."><?= set_value('message'); ?></textarea>
							<small><?= form_error('message'); ?></small>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- VIEW REQUEST REPORTS MODAL -->
<div class="modal fade" id="view_request_reports" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">View Request Reports</h4>
			</div>
			<div class="modal-body">  
				<div class ="table-responsive">
					<table class="table table-striped datatable" style="width:100%">
						<thead>
							<tr>
								<th>Date &amp; Time</th>
								<th>Status</th>
								<th>Student</th>
								<th>Reported By</th>
								<th>Violation</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($request_reports) : ?>
								<?php foreach ($request_reports as $report) : ?>
									<tr>
										<td><span class = "hidden"><?= $report->incident_report_datetime; ?></span><?= date('F d, Y \a\t h:i A', $report->incident_report_datetime); ?></td>
										<td><?= determineStatus($report->incident_report_status); ?></td>
										<td><?= $report->user_firstname.' '.($report->user_middlename == '' ? '' : substr($report->user_middlename, 0, 1).'. ').$report->user_lastname; ?></td>
										<td>
											<?php
												if ($report->reportedby_id != '') {
														//if REPORTED_BY teacher, get user's name
														echo $report->reportedby_firstname.' '.($report->reportedby_middlename == '' ? '' : substr($report->reportedby_middlename, 0, 1).'. ').$report->reportedby_lastname;
														echo " <small class = 'text-muted'><b>(".$report->reportedby_access.')</b></small>';
												} else {
														//if REPORTED_BY admin, get admin's name
														echo 'Admin';
												}
											?>
										</td>
										<td><?= ucfirst($report->violation_name); ?></td>
										<td>
											<div class="btn-group-vertical" role="group">
												<button type = "button" class="btn btn-primary" data-toggle="modal" data-target="#details_<?= sha1($report->incident_report_id); ?>">Details</button>
												<?php if($report->user_id == NULL):?>
													<a href="<?= base_url()?>AdminIncidentReport/setUser_exec/<?= $report->incident_report_id; ?>" class="btn btn-warning">Set User</a>
												<?php else:?>
													<button class="btn btn-warning" data-toggle="modal" data-target="#set_sanction_<?= $report->incident_report_id; ?>">Set Sanction</button>
												<?php endif;?>
											</div>
										</td>
									</tr>
								<!-- SET_SANCTION MODAL -->
								<div class="modal fade" id="set_sanction_<?= $report->incident_report_id; ?>" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<form method="POST" action="<?= base_url(); ?>AdminIncidentReport/sendCallSlip_exec/<?= $report->user_number; ?>/<?= $report->incident_report_id; ?>">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">Set Sanction</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<span>Set Sanction</span>
													<select id="effect" name="effect" class="form-control">
														<?php foreach ($effects as $effect) : ?>
														<option value="<?= $effect->effect_id; ?>" <?= $report->effects_id == $effect->effect_id ? 'selected' : ''; ?>><?= $effect->effect_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													<button type = "submit" class="btn btn-primary" ><i class="fa fa-paper-plane"></i> Send Call Slip</button>
												</div>
											</div>
										</form>
									</div>
								</div>
								<!-- DETAILS MODAL -->
								<div class="modal fade text-left" id="details_<?= sha1($report->incident_report_id); ?>" tabindex="-1" role="dialog" aria-labelledby="detailsTitle" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h3 class="modal-title" id="detailsTitle">Details</h3>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12">
														<div class="row">
															<div class="col-xs-12">
																<center>
																	<?php if($report->user_id != NULL):?>
																	<img src="<?= base_url().$report->user_picture; ?>" class="img-responsive img-circle" width="150">
																	<?php endif;?>
																	<h4><?= $report->user_firstname.' '.($report->user_middlename != '' ? $report->user_middlename : '').' '.$report->user_lastname; ?></h4>
																	<h5><?= ucfirst($report->user_access); ?></h5>
																	<h6><?= determineStatus($report->incident_report_status); ?></h6>

																	<!-- <?php if ($report->incident_report_status == 1) : ?>
																		<a href="<?= base_url().'AdminDusap/view_exec/'.$report->incident_report_id."/".$report->user_id; ?>" class="btn btn-primary"><i class="fa fa-search"></i> Manage DUSAP Attendance</a>
																	<?php else : ?>
																		<a href="<?= base_url().'AdminOffenseReport/view_exec/'.$report->incident_report_id."/".$report->user_id; ?>" class="btn btn-primary"><i class="fa fa-file-alt"></i> Offense Report</a>
																	<?php endif; ?> -->

																</center>
															</div>
															<div class="col-xs-6 margin-top-lg text-center">
																<h5><strong>Reported By:</strong></h5>
																<span><?php
																	if ($report->reportedby_id != '') {
																		//if REPORTED_BY teacher, get user's name
																		echo $report->reportedby_firstname.' '.($report->reportedby_middlename == '' ? '' : substr($report->reportedby_middlename, 0, 1).'. ').$report->reportedby_lastname;
																		echo " <small class = 'text-muted'><b>(".$report->reportedby_access.')</b></small>';
																	} else {
																		//if REPORTED_BY admin, get admin's name
																		echo 'Admin';
																	}
																	?></span>
																<br/>
																<br/>
																<h5><strong>Place</strong></h5>
																<span><?= $report->incident_report_place; ?></span>
															</div>
															<div class="col-xs-6  margin-top-lg  text-center">
																<h5><strong>Violation</strong></h5>
																<span><?= ucfirst($report->violation_name); ?></span>
																<br/>
																<br/>
																<h5><strong>Time</strong></h5>
																<span><?= date('F d, Y \a\t h:i A', $report->incident_report_datetime); ?></span>
															</div>
															<div class="col-xs-12 text-center">
																<br/>
																<h5><strong>Message</strong></h5>
																<p><?= $report->incident_report_message; ?></p>
															</div>
															<?php if($report->img_src != NULL):?>
															<div class="col-xs-12 text-center">
																<br/>
																<h5><strong>Image</strong></h5>
																<img src="<?= base_url().'uploads/images/'.$report->img_src?>" width="350" class="thumbnail"/>
															</div>
															<?php endif;?>
														</div>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div> <!--END DETAILS MODAL-->
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- VIEW SUSPENSION DETAIL MODAL -->
<div class="modal fade" id="view_suspension_detail" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Suspension</h4>
			</div>
			<div class="modal-body">
				<p><b>7.4.2.2 Suspension.</b> a penalty that allows the higher education institution
					to deprive or deny the erring student from attending classes for a period
					not exceeding twenty percent(20%) of the prescribed total class days for the
					school term. A penalty of suspension for a period of time more than twenty
					percent (20%) of the total class days for the school term shall be deemed
					suspension for a period equivalent to twenty percent (20%) of the
					prescribed total class days for the school term. 
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<!-- VIEW NON READMISSION DETAIL MODAL -->
<div class="modal fade" id="view_nonReadmission_detail" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Suspension</h4>
			</div>
			<div class="modal-body">
				<p><b>7.4.2.3 Non-readmission.</b> A penalty that allows the institution to deny
					admission or enrollment of an erring student for the school term immediately
					following the term when the resolution or decision finding the student guilty
					of the offense charged and imposing the penalty or non-readmission was
					promulgated. Unlike the penalty of exclusion, the student is allowed to
					complete the current school term when the resolution for non-readmission
					was promulgated. Transfer credentials of the erring student shall be issued
					upon promulgation, subject to the other provisions of this Manual. 
 
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- VIEW EXPULSION DETAIL MODAL -->
<div class="modal fade" id="view_expulsion_detail" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Suspension</h4>
			</div>
			<div class="modal-body">
				<p><b>7.4.2.4 Expulsion. </b> A penalty which an institution on Higher Education
					declares an erring student disqualified for admission to any public or private
					higher education institution in the Philippines. In any case, the penalty of expulsion
					cannot be imposed without the approval of the Chairman of the Commission.
					This penalty may be imposed for acts or offenses involving moral turpitude
					or constituting gross misconduct, which are considered criminal pursuant to
					existing penal laws. 
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	function keypresshandler(event) {
		var charCode = event.keyCode;
		//Non-numeric character range
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
	}
</script>

<!-- Dropdown for User Number -->
<!-- <script type="text/javascript">
	window.onload = function () {

		$(document).on("focusin keyup", "#user_number.autocomplete", function () {
			$.ajax({
				"method": "POST",
				"url": '<?= base_url(); ?>' + "AdminIncidentReport/search_user_number",
				"dataType": "JSON",
				"data": {
					'id': $(".autocomplete").val()
				},
				success: function (res) {
					$("#user_number_menu").empty();
					if (res.length == 0) {
						$("#user_number_menu").append("<li class = 'no-matches'><a>No match found</a></li>");
					} else {
						for (var i = 0; i < res.length; i++) {
							$("#user_number_menu").append("<li title = '" + res[i].user_firstname + " " + res[i].user_lastname + "' data-firstname = '" + res[i].user_firstname + "' data-lastname = '" + res[i].user_lastname + "' data-middlename = '" + res[i].user_middlename + "' data-course = '" + res[i].user_course + "' data-access = '" + res[i].user_access + "'><a>" + res[i].user_number + "</a></li>");
						}
					}
				},
				error: function (res) {
					console.log(res);
				}
			});
			// Cache useful selectors
			var $input = $(this);
			var $dropdown = $input.next("ul.dropdown-menu");

			// Create the no matches entry if it does not exists yet
			if (!$dropdown.data("containsNoMatchesEntry")) {
				$("input.autocomplete + ul.dropdown-menu").append(
						'<li class="no-matches hidden"><a>No matches</a></li>'
						);
				$dropdown.data("containsNoMatchesEntry", true);
			}

			// Show only matching values
			$dropdown.find("li:not(.no-matches)").each(function (key, li) {
				var $li = $(li);
				$li[new RegExp($input.val(), "i").exec($li.text()) ? "removeClass" : "addClass"]("hidden");
			});

			// Show a specific entry if we have no matches
			$dropdown.find("li.no-matches")[$dropdown.find("li:not(.no-matches):not(.hidden)").length > 0 ? "addClass" : "removeClass"]("hidden");

		});

		$(document).on("focus click", "input.autocomplete + ul.dropdown-menu li", function (e) {
			// Prevent any action on the window location
			e.preventDefault();

			// Cache useful selectors
			$li = $(this);
			$input = $li.parent("ul").prev("input");
			$firstname = $("#user_firstname");
			$lastname = $("#user_lastname");
			$middlename = $("#user_middlename");
			$course = $("#user_course");
			$access = $("#user_access");

			// Update input text with selected entry
			if (!$li.is(".no-matches")) {
				$input.val($li.text());
				$firstname.val($li.data('firstname'));
				$lastname.val($li.data('lastname'));
				$middlename.val($li.data('middlename'));
				$course.val($li.data('course'));
				$access.val($li.data('access'));
			}
		});

	}
</script> -->

<!-- Dropdown for User Lastname -->
<script type="text/javascript">
	window.onload = function () {

		$(document).on("focusin keyup", "#user_lastname.autocomplete2", function () {
			$.ajax({
				"method": "POST",
				"url": '<?= base_url(); ?>' + "AdminIncidentReport/search_user_lastname",
				"dataType": "JSON",
				"data": {
					'id': $(".autocomplete2").val()
				},
				success: function (res) {
					$("#user_lastname_menu").empty();
					if (res.length == 0) {
						$("#user_lastname_menu").append("<li class = 'no-matches'><a>No match found</a></li>");
					} else {
						for (var i = 0; i < res.length; i++) {
							$("#user_lastname_menu").append("<li title = '" + res[i].user_firstname + " " + res[i].user_lastname + "' data-firstname = '" + res[i].user_firstname + "' data-number = '" + res[i].user_number + "' data-lastname = '" + res[i].user_lastname +"' data-middlename = '" + res[i].user_middlename + "' data-course = '" + res[i].user_course + "' data-access = '" + res[i].user_access + "'><a>" + res[i].user_lastname + ', ' + res[i].user_firstname + ' ' + res[i].user_middlename + "</a></li>");
						}
					}
				},
				error: function (res) {
					console.log(res);
				}
			});
			// Cache useful selectors
			var $input = $(this);
			var $dropdown = $input.next("ul.dropdown-menu");

			// Create the no matches entry if it does not exists yet
			if (!$dropdown.data("containsNoMatchesEntry")) {
				$("input.autocomplete2 + ul.dropdown-menu").append(
						'<li class="no-matches hidden"><a>No matches</a></li>'
						);
				$dropdown.data("containsNoMatchesEntry", true);
			}

			// Show only matching values
			$dropdown.find("li:not(.no-matches)").each(function (key, li) {
				var $li = $(li);
				$li[new RegExp($input.val(), "i").exec($li.text()) ? "removeClass" : "addClass"]("hidden");
			});

			// Show a specific entry if we have no matches
			$dropdown.find("li.no-matches")[$dropdown.find("li:not(.no-matches):not(.hidden)").length > 0 ? "addClass" : "removeClass"]("hidden");

		});

		$(document).on("focus click", "input.autocomplete2 + ul.dropdown-menu li", function (e) {
			// Prevent any action on the window location
			e.preventDefault();

			// Cache useful selectors
			$li = $(this);
			$input = $li.parent("ul").prev("input");
			$number = $("#user_number");
			$firstname = $("#user_firstname");
			$lastname = $("#user_lastname");
			$middlename = $("#user_middlename");
			$course = $("#user_course");
			$access = $("#user_access");

			// Update input text with selected entry
			if (!$li.is(".no-matches")) {
				$input.val($li.text());
				$number.val($li.data('number'));
				$firstname.val($li.data('firstname'));
				$lastname.val($li.data('lastname'));
				$middlename.val($li.data('middlename'));
				$course.val($li.data('course'));
				$access.val($li.data('access'));
			}
		});
	}
</script>
