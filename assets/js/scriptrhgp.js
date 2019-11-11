$(function () {

  // Generic function to make an AJAX call
  var fetchData = function(dataURL, form_data = {}, dataTYPE = 'json', method = 'POST') {
      // Return the $.ajax promise
      return $.ajax({
          data: form_data,
          type: method,
          dataType: dataTYPE,
          url: dataURL,
          cache: false
      });
  }


  // highlight the current nav
  $("#teaching-load a:contains('Teaching Load')").parent().addClass('active');

  $('[data-toggle="tooltip"]').tooltip({'trigger' : 'focus', 'placement' : 'left'})

  var error = false;
  var next_idx=0;
  var focusFlag=false;
  var executeFlag = false;
  var enter_key_press = false;

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
      td_span.removeClass().addClass('label label-primary')
      $(this).removeClass().addClass('info')
    } else /*if (remarks == 'OFFICIALLY DROPPED' || remarks == 'LOA' || remarks == 'WC')*/ {
      td_span.removeClass().addClass('label label-default')
      $(this).removeClass().addClass('default')
    }

  });

/*  $(document).on('keyup', 'table#tblgrd tr td input[type=text]', function(e) {
    if (e.which == 39) { // right arrow
      $(this).closest('td').next().find('input').focus();

    } else if (e.which == 37) { // left arrow
      $(this).closest('td').prev().find('input').focus();

    } else if (e.which == 40) { // down arrow
      $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();

    } else if (e.which == 38) { // up arrow
      $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
    }

  });*/

  $(document).on('focusin', 'table#tblgrd tr td input[type=text]', function() {
    $(this).closest('tr').addClass('lead');
    //get the index value of text field
    // next_idx = $('input[type=text]').index(this) ;
    // $(this).select();
    $('#btnSend').attr('disabled', 'disabled');
    executeFlag = false;
  });


  $("input[type=text]").mouseup(function(e){
    e.preventDefault();
    focusFlag = true;
    return false;
  });

  $(document).on('focus', 'input[type=text], textarea', function() {
    // Check for the change from default value
    // if(this.value == this.defaultValue){
        $(this).select();
    // }
    enter_key_press = false;
    var center = $(window).height()/2;
        var top = $(this).offset().top ;
        if (top > center){
          $(window).scrollTop(top-center);
        }

    $('#btnSend').attr('disabled', 'disabled');
  });

  $(document).on('focusout', 'table#tblgrd tr td input[type=text]', function() {
      // var pageURL = 'http://test.umak.edu.ph/oleog/auth',
      //     ajax_req = fetchData(pageURL);
          input = $(this);
          console.log(input.val());
          next_idx = $('input[type=text]').index(this)+1;
          $(this).closest('tr').removeClass('lead');
          $tr = $(this).closest('tr');


          $.fn.submitAndValidate($tr, input);
          focusFlag = false;
  });


  $('input[type=text]').keypress(function(e){

      if(e.keyCode == 13 || e.keyCode == 9) { // enter button in ASCII code

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

  });




  $(document).on('click', '#btnSend', function(){
    var uri = $('input:hidden[name=confirm_url]').val();
    $("#modal_confirm .modal-header h3 strong").empty().append("Are you sure you want to save these data?");
    $("#modal_confirm .modal-body").empty().append("<p>Clicking <strong class='text-error'>YES</strong> will save these data and cannot be<br>edited without undergoing amendments of grades procedure.</p>");
    $("#modal_confirm .modal-footer").empty().append("<a id='btnYESConfirmed' href='" + uri + "' class='btn btn-success'>YES <i style='font-size:20px;' class='icon-ok'></i></a>");
    $("#modal_confirm .modal-footer").append("<button id='btnNo' class='btn btn-primary' data-dismiss='modal'>NO");
    $("#modal_confirm").modal('show');
  });

  $(document).on('click', '#btnYESConfirmed', function(){
     $(this).attr('disabled', 'disabled');
  });

  $(document).on('click', '#btnNo', function () {
    $("#btnSend").removeAttr('disabled');
  });

  $(document).on('click', '#prtgradesheet', function (e) {
    // window.location= "<?php echo base_url();?>index.php/grading"
    e.stopPropagation();
  });


  $.fn.submitAndValidate = function($tr, $input){

    if (executeFlag == false)
    {
      var sched_id = $('input:hidden[name=sched_id]').val(),
          Makadiyos_R1 = $tr.find('td:eq(2) input.Grade'),
          Makadiyos_R2 = $tr.find('td:eq(3) input.Grade'),
          Makatao_R1 = $tr.find('td:eq(4) input.Grade'),
          Makatao_R2 = $tr.find('td:eq(5) input.Grade'),
          Makakalikasan_R1 = $tr.find('td:eq(6) input.Grade'),
          Makabansa_R1 = $tr.find('td:eq(7) input.Grade'),
          Makabansa_R2 = $tr.find('td:eq(8) input.Grade'),
          // output = $tr.find('td:eq(6) span.output'),
          studno = $tr.find('td:eq(8) input:hidden.StudNo').val(),
          sgid = $tr.find('td:eq(8) input:hidden.studgrade_id').val(),
          uri = $('input:hidden[name=base_url]').val(),
          form_data = {
          'sched_id': sched_id,
          'studgrade_id': sgid,
          'StudNo': studno,
          'Makadiyos_R1': Makadiyos_R1.val(),
          'Makadiyos_R2': Makadiyos_R2.val(),
          'Makatao_R1': Makatao_R1.val(),
          'Makatao_R2': Makatao_R2.val(),
          'Makakalikasan_R1': Makakalikasan_R1.val(),
          'Makabansa_R1': Makabansa_R1.val(),
          'Makabansa_R2': Makabansa_R2.val(),
        };

      console.log(sched_id);
      var request = $.ajax({
        type: "POST",
        url: uri,
        data: form_data,
        beforeSend: function() {
          $tr.find('td:last').fadeIn().prepend('<i class="fa fa-cog fa-spin fa-2x text-primary"></i>');
          $('input').attr('disabled', 'disabled');
          $('#btnSend').attr('disabled', 'disabled');
        },
        complete: function () {
          $tr.find('td:last i').fadeOut('slow');
          // $('input').removeAttr('disabled');
          $('input').prop('disabled', false);
          $('#btnSend').prop('disabled', false);

          // if (enter_key_press == true)
          // {
          //   enter_key_press = false;
          //   $tr.next('tr').find('td:eq(2) input.Grade').focus();
          //   console.log($tr.next('tr').find('td:eq(2) input.Grade'));
          // }

          // $(':input:not(:disabled):eq(' + ($(':input').index(this) + 1) + ')');
          // $(":input:not(:disabled):eq(" + ($(":input:not(:disabled)").index(this) + 1) + ")").focus()
        },
        error: function () {
          $tr.find('td:last i').fadeOut('slow')
          $('input').removeAttr('disabled');
          // $('input').next('input').focus();
          // $(':input:eq(' + ($(':input').index(this) + 1) + ')');
        },
        cache: false,
      });

      $(this).closest('tr').removeClass('lead');
      // $tr.find('td:last').fadeIn().prepend('<i class="fa fa-cog fa-spin fa-2x text-primary"></i>');
      $.when( request ).then(function(data){
      // request.done(function(data){


          // reset to default values
          $tr.removeClass()
          $tr.addClass(data.c_code2)

          console.log(uri)
          if (data.error == true)
          {
            // remarks.removeClass()
            // remarks.addClass(data.c_code)
            $tr.addClass('lead')
            $input.focus();
            $input.val("NO");

            // prompt error message
            console.log(data.status);
            alert(data.status)

            // error flag
            error = true;
          }
          else
          {
              // move focus to the next textfield if it has no error
              next_idx = $('input[type=text]').index(this) + 1;
              // console.log('index: ' + $input.index($input));
              console.log('next_idx: ' + next_idx);
              // console.log('input length: ' + $('input:text').length);
              if(next_idx < $('input:text').length)
              {
                $('input[type=text]:eq(' + next_idx + ')').focus();
                // console.log('set focus')
              }
          }


          // grade.val(data.Grade)
          // strgrade.val(data.StrGrade)
          // output.html(data.StrGrade)
          // remarks.html(data.Remarks)
          // remarks.removeClass()
          // remarks.addClass(data.c_code)
          // console.log(grade.val())

      }).fail(function (jqXHR, textStatus) {
          console.log(uri);
          console.log(textStatus);
          console.log('Something went wrong with your internet connection. Please log out and check.');
          console.log(textStatus + ': ' + errorThrown);
          alert("The server has failed to received the data that you've sent. Please re-encode the data again.");
          // $('.modal').modal('hide');
          // $("#errMsg .modal-body").empty().append("The server has failed to received the data that you've sent. Please re-encode the data again.");
          // $("#errMsg").modal('show');
          $tr.find('td:last i').fadeOut('slow')
      });

      // $tr.find('td:last i').fadeOut('slow')
      focusFlag = false;
      executeFlag = true;

    }// endif
    // console.log('focusFlag ' + focusFlag)
    // console.log('executeFlag ' + executeFlag)
    console.log(sched_id);
    console.log(form_data);
  }// end function

})
