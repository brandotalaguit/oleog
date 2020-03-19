$(function () {
  $('[data-toggle="tooltip"]').tooltip()

  var uri = $('form').attr('action');
  var error = false;
  var next_idx=0;
  var focusFlag=false;


  $("#tblgrd tbody tr").each(function(){

    var remarks = $.trim($(this).find('td:last span').text());
    var td_span = $(this).find('td:last span');

    if (remarks == 'PASSED') {
      td_span.removeClass().addClass('label label-success')
      $(this).removeClass().addClass('success')
    } else if (remarks == 'FAILED') {
      td_span.removeClass().addClass('label label-danger')
      $(this).removeClass().addClass('danger')
    } else if (remarks == 'UNOFFICIALLY DROPPED') {
      td_span.removeClass().addClass('label label-warning')
      $(this).removeClass().addClass('warning')
    } else if (remarks == 'INCOMPLETE') {
      td_span.removeClass().addClass('label label-info')
      $(this).removeClass().addClass('info')
    } else/* if (remarks == 'OFFICIALLY DROPPED')*/ {
      td_span.removeClass().addClass('label label-default')
      $(this).removeClass().addClass('default')
    }

  });


  $(document).on('focusin', 'table#tblgrd tr td input[type=text]', function() {
    $(this).closest('tr').addClass('lead');
    //get the index value of text field
    next_idx = $('input[type=text]').index(this) ;
    $(this).select()
  });

  $(" input[type=text]").mouseup(function(e){
    e.preventDefault();
  });

  $(document).on('focus', 'input[type=text], textarea', function() {
    // Check for the change from default value
    if(this.value == this.defaultValue){
        this.select();
    }
  });

  $(document).on('focusout', 'table#tblgrd tr td input[type=text]', function() {
    next_idx = $('input[type=text]').index(this)+1;
    console.log('hey');
    $tr = $(this).closest('tr');
    $tr.find('td:last').fadeIn().prepend('<i class="fa fa-cog fa-spin fa-2x text-primary"></i>')
    $.fn.submitAndValidate($tr, $(this));
    $tr.find('td:last i').fadeOut('slow')
    focusFlag=false;
  });

  // $(document).on('focusout', 'table#tblgrd tr td input[type=text]', function() {
  //   $(this).closest('tr').removeClass('lead');
  //   $(this).change();
  // });

  $( "input[type=text]").keypress(function(e){

  	  if(e.keyCode == 13 || e.keyCode == 9) 
  	  { // enter button in ASCII code

        e.stopPropagation();
       $('input[type=text]:focus').blur();
        // e.preventDefault();
        // return false;
        // var input = $(this),
        //     focusFlag = false,
        //     $tr = $(this).closest('tr');

        if (e.keyCode == 13)
        {
          enter_key_press = true;
        }

      }
      // if(e.keyCode == 13 || e.keyCode == 9) { // enter button in ASCII code
      //   e.preventDefault();
      //   focusFlag=false;
      //   next_idx = $('input[type=text]').index(this) + 1;
      //   var tot_idx = $('body').find('input[type=text]').length;

      //   if(tot_idx == next_idx)
      //   $('#btnSend').focus().select();

      //   // to run the focusout
      //   // $(this).change();
      // }

  });


  $("#btnSend").click(function() {

    // $(this).attr('disabled','disabled');

    var passed=0, failed=0, ud=0, inc=0, od=0;others=0;
    isinputempty = 0;

    

    $("#tblgrd tbody tr").each(function(){

      var remarks = $.trim($(this).find('td:last').text());
      if (remarks == 'PASSED') {
        passed++;
      } else if (remarks == 'FAILED') {
        failed++;
      } else if (remarks == 'UNOFFICIALLY DROPPED') {
        ud++;
      } else if (remarks == 'INCOMPLETE') {
        inc++;
      } else if (remarks == 'OFFICIALLY DROPPED') {
        od++;
      }
      else if (remarks == 'LOA') {
        others++;
      }
      else if (remarks == 'HD') {
        others++;
      }
      else if (remarks == 'WC') {
        others++;
      }
      else
        isinputempty++

    });

    if (false) 
    {
       $("#modal_message .modal-header h3 strong").empty().append("WARNING");
       $("#modal_message .modal-body").empty().append("Data cannot be saved due to remaining <b>BLANK</b> grades, Please verify your encoding again");
      
      // $("#modal_message .modal-footer").empty().append("<button id='btnConfirm' class='btn btn-primary' data-dismiss='modal'>I CONFIRM <i style='font-size:20px;' class='icon-ok'></i></button>");
      $("#modal_message .modal-footer").empty().append("<button id='btnNo' class='btn btn-primary' data-dismiss='modal'>OK <i style='font-size:20px;' class='icon-remove'></i></button>");
    }
    else
    {

      $("#modal_message .modal-header h3 strong").empty().append("PLEASE CONFIRM THAT YOU GIVE THE FOLLOWING GRADES");

      $("#modal_message .modal-body").empty().append("<strong>("+passed+")</strong> <strong class='text-success'>PASSED</strong><br>");
      $("#modal_message .modal-body").append("<strong>("+failed+")</strong> <strong class='text-danger'>FAILED</strong><br>");
      $("#modal_message .modal-body").append("<strong>("+inc+")</strong> <strong class='text-info'>INCOMPLETE</strong><br>");
      $("#modal_message .modal-body").append("<strong>("+ud+")</strong> <strong class='textplea-warning'>UNOFFICIALLY DROPPED</strong><br>");
      $("#modal_message .modal-body").append("<strong>("+od+")</strong> <strong class='text-default'>OFFICIALLY DROPPED</strong><br>");
      $("#modal_message .modal-body").append("<strong>("+isinputempty+")</strong> <strong class='text-default' style=' color : #D2691E'>BLANK GRADE(S)</strong><br>");
      $("#modal_message .modal-footer").empty().append("<button id='btnConfirm' class='btn btn-primary' data-dismiss='modal'>I CONFIRM <i style='font-size:20px;' class='icon-ok'></i></button>");
      $("#modal_message .modal-footer").append("<button id='btnNo' class='btn' data-dismiss='modal'>CANCEL <i style='font-size:20px;' class='icon-remove'></i></button>");
    }

    $("#modal_message").modal('show');
  });

  $(document).on('click', '#btnConfirm', function(){
    var uri = $('input:hidden[name=confirm_url]').val();
    $("#modal_confirm .modal-header h3 strong").empty().append("Are you sure you want to save these data?");
    $("#modal_confirm .modal-body").empty().append("<p>Clicking <strong class='text-error'>YES</strong> will save these data and cannot be<br>edited without undergoing amendments of grades procedure.</p>");
    $("#modal_confirm .modal-footer").empty().append("<a id='btnYESConfirmed' href='" + uri + "' class='btn btn-success'>YES <i style='font-size:20px;' class='icon-ok'></i></a>");
    $("#modal_confirm .modal-footer").append("<button id='btnNo' class='btn btn-primary' data-dismiss='modal'>NO <i style='font-size:20px;' class='icon-remove'></i></button>");
    $("#modal_confirm").modal('show');
  });

  $("#btnSendGrad").click(function() {

    // $(this).attr('disabled','disabled');

    var passed=0, failed=0, ud=0, inc=0, od=0;
    $("#tblgrd tbody tr").each(function(){

      var remarks = $.trim($(this).find('td:last').text());
      if (remarks == 'PASSED') {
        passed++;
      } else if (remarks == 'FAILED') {
        failed++;
      } else if (remarks == 'UNOFFICIALLY DROPPED') {
        ud++;
      } else if (remarks == 'INCOMPLETE') {
        inc++;
      } else if (remarks == 'OFFICIALLY DROPPED') {
        od++;
      }

    });

    $("#modal_message .modal-header h3 strong").empty().append("PLEASE CONFIRM IF THE STUDENT ENCODED IS/ARE GRADUATING STUDENTS");

    $("#modal_message .modal-body").empty().append("<strong>("+passed+")</strong> <strong class='text-success'>PASSED</strong><br>");
    $("#modal_message .modal-body").append("<strong>("+failed+")</strong> <strong class='text-danger'>FAILED</strong><br>");
    $("#modal_message .modal-body").append("<strong>("+inc+")</strong> <strong class='text-info'>INCOMPLETE</strong><br>");
    $("#modal_message .modal-body").append("<strong>("+ud+")</strong> <strong class='text-warning'>UNOFFICIALLY DROPPED</strong><br>");
    $("#modal_message .modal-body").append("<strong>("+od+")</strong> <strong class='text-default'>OFFICIALLY DROPPED</strong><br>");
    $("#modal_message .modal-footer").empty().append("<button id='btnConfirmGrad' class='btn btn-primary' data-dismiss='modal'>I CONFIRM <i style='font-size:20px;' class='icon-ok'></i></button>");
    $("#modal_message .modal-footer").append("<button id='btnNo' class='btn btn-default' data-dismiss='modal'>CANCEL</button>");

    $("#modal_message").modal('show');
  });

  $(document).on('click', '#btnConfirmGrad', function(){
    var uri = $('input:hidden[name=confirm_url_grad]').val();
    $("#modal_confirm .modal-header h3 strong").empty().append("Are you sure you want to save these data?");
    $("#modal_confirm .modal-body").empty().append("<p>Clicking <strong class='text-error'>YES</strong> will save these data and cannot be<br>edited without undergoing amendments of grades procedure.</p>");
    $("#modal_confirm .modal-footer").empty().append("<a id='btnYESConfirmed' href='" + uri + "' class='btn btn-success'>YES <i style='font-size:20px;' class='icon-ok'></i></a>");
    $("#modal_confirm .modal-footer").append("<button id='btnNoGrad' class='btn btn-primary' data-dismiss='modal'>NO");
    $("#modal_confirm").modal('show');
  });

  $(document).on('click', '#btnYESConfirmed', function(){
     $(this).attr('disabled', 'disabled');
  });

  $(document).on('click', '#btnNo', function () {
    $("#btnSend").removeAttr('disabled');
  });

  $(document).on('click', '#prtgradesheet', function (e) {
    window.location= "<?php echo base_url();?>index.php/grading"
  });

  $.fn.submitAndValidate = function($tr, $input){

    var sched_id = $('input:hidden[name=sched_id]').val();
    var grade = $tr.find('td:eq(3) input.Grade');
    var strgrade = $tr.find('td:eq(4) input.StrGrade');
    var labgrade = $tr.find('td:eq(5) input.LabGrade');
    var strlab = $tr.find('td:eq(6) input.StrLab');
    var remarks = $tr.find('td:last span');
    var output = $tr.find('td:eq(4) span.output');
    var outputlab = $tr.find('td:eq(6) span.outputleclab');
    var studno = $tr.find('td:eq(2) input:hidden.StudNo').val();
    var sgid = $tr.find('td:eq(2) input:hidden.studgrade_id').val();
    var uri = $('input:hidden[name=base_url]').val();
    var form_data = {
        'sched_id': sched_id,
        'studgrade_id': sgid,
        'StudNo': studno,
        'Grade': grade.val(),
        'StrGrade': strgrade.val(),
        'LabGrade': labgrade.val(),
        'StrLab': strlab.val(),
        'Remarks' : $.trim(remarks.text())
      };

    console.log(form_data);
    $.ajax({
      type: "POST",
      url: uri,
      data: form_data,
      success: function(data){

        // reset to default values
        $tr.removeClass()
        $tr.addClass(data.c_code2)
        console.log(data)
        if (data.error == true)
        {
          // remarks.removeClass()
          remarks.addClass(data.c_code)
          $tr.addClass('lead')
          // prompt error message
          console.log(data.status);
          alert(data.status)

          //retain focus in the textfield if it has an error
          // var retain = next_idx-1;
          // if (next_idx==0)
          // {
          //   retain=0;
          // }
          // $('input[type=text]:eq(' + retain + ')').focus();
          // $('input[type=text]:eq(' + retain + ')').select();

          // error flag
          error = true
        }
        else
        {
            //move focus to the next textfield if it has no error
            // $('input[type=text]:eq(' + next_idx + ')').focus();
            $('input[type=text]:eq(' + next_idx + ')').select();
        }




        grade.val(data.Grade)
        strgrade.val(data.StrGrade)
        labgrade.val(data.LabGrade)
        strlab.val(data.StrLab)
        output.html(data.StrGrade)
        outputlab.html(data.StrLab)
        remarks.html(data.Remarks)
        remarks.removeClass()
        remarks.addClass(data.c_code)
        console.log(grade.val())

      },
      dataType: 'json'
    });
  }

})
