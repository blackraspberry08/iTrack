<style>
  .dropdown-menu li{
      cursor: pointer;
  }
  .dropdown-menu{
      position: initial;
  }
</style>
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
        <li><a href="<?= base_url() ?>AdminDashboard">
                <em class="fa fa-home"></em>
            </a></li>
        <li><a href="<?= base_url() ?>AdminIncidentReport">
                Incident Report
            </a></li>
        <li class="active">Edit</li>
    </ol>
</div><!--/.row breadcrumb-->

<div class="row">
  <div class="col-xs-12">
      <h1>Edit Incident Report</h1>
  </div>
</div>
<div class="row margin-top-lg">
  <div class="col-xs-8 col-xs-offset-2">
    <form action = "<?= base_url() ?>AdminIncidentReport/edit_submit_exec/<?= $incident_report->incident_report_id?>" method="POST" autocomplete="off">
      <div class = "row">
          <div class = "col-sm-8">
            <div id = "classification_other" class="form-group <?= !empty(form_error("classification_other")) ? "has-error" : ""; ?>">
              <small class="control-label">Name of Violation</small>
              <input type = "text" name = "classification_other" class="form-control " placeholder = "Name of Violation" value = "<?= set_value("classification_other") ?>">
              <small><?= form_error("classification_other") ?></small>
              <br/>
            </div>
            <span>Classification of Offense/Violation</span>
            <select id = "classification" name = "classification" class = "form-control">
              <option disabled="disabled" style = "background:#ddd;">-- Major --</option>
              <?php
              foreach ($major_violations as $violation) {
                  ?>
                  <option value = "<?= $violation->violation_id ?>" data-type = "<?= $violation->violation_type ?>" title = "<?= ucfirst($violation->violation_name) ?>" <?= $incident_report->violation_id == $violation->violation_id ? 'selected' : ''?>><?= ucfirst($violation->violation_name) ?></option>    
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
              <option value="major" <?= $incident_report->violation_type == "major" ? 'selected' : ''?>>Major</option>
              <option value="minor" <?= $incident_report->effects_id == "minor" ? 'selected' : ''?>>Minor</option>
            </select>
          </div>
      </div>
      <br/>
      <div class = "row">
        <div class = "col-sm-4 <?= !empty(form_error("date_time")) ? "has-error" : ""; ?>">
          <span class="control-label" id="date_time">Date &AMP; Time</span>
          <input type="text" class="form-control datetimepicker" name = "date_time" placeholder="Type Here" aria-describedby="date_time" value = "<?= set_value("date_time", date('m/d/Y h:i A', $incident_report->incident_report_datetime)) ?>">
          <small><?= form_error("date_time") ?></small>
        </div>
        <div class="col-sm-4">
          <span class="control-label">Sanction</span>
            <select id = "effect" name = "effect" class = "form-control">
              <?php foreach($effects as $effect):?>
              <option value="<?= $effect->effect_id?>" <?= $incident_report->effects_id == $effect->effect_id ? 'selected' : ''?>><?= $effect->effect_name?></option>
              <?php endforeach;?>
            </select>
          <small><?= form_error("effect") ?></small>
        </div>
        <div class = "col-sm-4 <?= !empty(form_error("place")) ? "has-error" : ""; ?>">
          <span class="control-label">Place of the Offense Committed</span>
          <input type="text" class="form-control" name = "place" placeholder="Type Here" value = "<?= set_value("place", $incident_report->incident_report_place) ?>">
          <small><?= form_error("place") ?></small>
        </div>
      </div>
      <br/>
      <div class ="row">
        <div class = "col-sm-8 col-sm-offset-2 <?= !empty(form_error('user_number')) ? 'has-error' : ''; ?>" >
          <span class="control-label">User Number</span><br/>
          <input onkeypress = 'return keypresshandler(event)' maxlength="9" type="text" class="form-control" name = "user_number" id = "user_number" placeholder="Type Here" readonly value = "<?= set_value('user_number', $incident_report->user_number); ?>" >          
          <small><?= form_error('user_number'); ?></small>
        </div>
      </div>
      <div class ="row">
        <div class = "col-sm-8 col-sm-offset-2">
          <br/>
          <div class = "<?= !empty(form_error('user_lastname')) ? 'has-error' : ''; ?>">
              <span class="control-label">Lastname</span><br/>
              <input type="text" class="form-control autocomplete2" name = "user_lastname" id = "user_lastname" placeholder="Lastname" data-toggle="dropdown" value="<?= set_value('user_lastname', $incident_report->user_lastname); ?>">
              <ul class="dropdown-menu" role="menu" id = "user_lastname_menu" style="width:100%;"></ul>          
              <small><?= form_error('user_lastname'); ?></small>
              <br/>
          </div>
          <div class = "<?= !empty(form_error("user_firstname")) ? "has-error" : ""; ?>">
            <span class="control-label">Firstname</span><br/>
            <input type="text" class="form-control" name = "user_firstname" id = "user_firstname" placeholder="Firstname" readonly="" value = "<?= set_value("user_firstname", $incident_report->user_firstname) ?>">
            <small><?= form_error("user_firstname") ?></small>
            <br/>
          </div>
          <span class="control-label">Middlename</span><br/>
          <input type="text" class="form-control" name = "user_middlename" id = "user_middlename" placeholder="Middlename" readonly="" value = "<?= set_value("user_middlename", $incident_report->user_middlename) ?>">
        </div>
      </div>
      <div class = "row">
        <div class = "col-xs-4 col-xs-offset-2 <?= !empty(form_error("user_course")) ? "has-error" : ""; ?>">
          <br/>
          <span class="control-label">Course</span><br/>
          <input type="text" class="form-control" name = "user_course" id = "user_course" placeholder="Course" readonly="" value = "<?= set_value("user_course", $incident_report->user_course) ?>">
          <small><?= form_error("user_course") ?></small>
        </div>
        <div class = "col-xs-4 <?= !empty(form_error("user_access")) ? "has-error" : ""; ?>">
          <br/>
          <span class="control-label">User Access</span><br/>
          <input type="text" class="form-control" name = "user_access" id = "user_access" placeholder="User Access" readonly="" value = "<?= set_value("user_access", $incident_report->user_access) ?>">
          <small><?= form_error("user_access") ?></small>
        </div>
      </div>

      <!-- 
      <div class="row">
        <div class = "col-xs-3 col-xs-offset-2 <?= !empty(form_error("user_age")) ? "has-error" : ""; ?>">
          <br/>
          <span class="control-label">Age</span><br/>
          <input type="text" maxlength="3" onkeypress = 'return keypresshandler(event)' class="form-control" name = "user_age" id = "user_age" placeholder="Age" value = "<?= set_value("user_age", $incident_report->incident_report_age) ?>">
          <small><?= form_error("user_age") ?></small>
        </div>
        <div class = "col-xs-8 col-xs-offset-2 <?= !empty(form_error("user_section_year")) ? "has-error" : ""; ?>">
          <br/>
          <span class="control-label">Section/Year</span><br/>
          <input type="text" class="form-control" name = "user_section_year" id = "user_section_year" placeholder="Type Here" value = "<?= set_value("user_section_year", $incident_report->incident_report_section_year) ?>">
          <small><?= form_error("user_section_year") ?></small>
        </div>
      </div>
       -->
      <div class = "row">
        <div class = "col-xs-8 col-xs-offset-2 <?= !empty(form_error("message")) ? "has-error" : ""; ?>">
          <br/>
          <span class="control-label">Message</span><br/>
          <textarea class="form-control" rows ="5" name = "message" style = "resize: none;" placeholder="Write a message. . ."><?= set_value("message", $incident_report->incident_report_message) ?></textarea>
          <small><?= form_error("message") ?></small>
          <div class="row margin-top-lg margin-bottom-lg">
            <div class="col-xs-12">
              <center>
                <div class="btn-group" role="group">
                  <button type="reset" class="btn btn-secondary"><i class="fa fa-undo"></i> Reset</button>
                  <button type="submit" class="btn btn-primary"><i class="fa fa-pen"></i> Save Changes</button>
                </div>
              </center>
            </div>
          </div>
        </div>
      </div>
    </form>
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
<script type="text/javascript">
    window.onload = function () {

        $(document).on("focusin keyup", "#user_number.autocomplete", function () {
            $.ajax({
                "method": "POST",
                "url": '<?= base_url() ?>' + "AdminIncidentReport/search_user_number",
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

</script>

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