<?php Header("content-type: application/x-javascript"); ?>
$(function(){
  $('input[type=text]').focusout(function(e){
  e.preventDefault();
  var classdum = $(this).val(),
      grade = $(this).parent().closest('td').find('input[type=hidden]'),
      <!-- output = $(this).parent().closest('td').next('td').find('span.uneditable-input'), -->
      output = $(this).parent().closest('tr').find('span.output'),
      remarks = $(this).parent().closest('td').next('td').next('td'),
      hRemarks = $(this).parent().closest('td').next('td').find('input[type=hidden]'),
      error = false;
      classdum = classdum.toUpperCase();
      alert(output.html())
    switch( true ) {
      <?php
        for($i = 1.00, $y = 1.00, $z = 95; $i <= 2.95; $i = $i + 0.05, $z = $z - 0.50) {
          $num = number_format($i,2);
          if (substr($num, -1) == "5") {
            echo " case ( classdum > " .number_format($i,2)." && classdum". (number_format($i+0.05,2)==3.00 ? " <= " : " < ") . number_format($i+0.05,2)." ) || ( classdum  >= ".(number_format($z-0.50,2) == 75.00 ? 74.50 : number_format($z-0.50,2)) ." && classdum <= " .number_format($z,2)." ) : $y;\n\t\t";
            echo " grade.val('".number_format($y,2)."'); output.empty().append(grade.val()); remarks.empty().append('<span class=\'label label-info\'>PASSED</span>'); \n\t\t";
            echo " hRemarks.val('Passed'); \n\t\t";
          } else {
            echo " case ( classdum >= " .number_format($i,2)." && classdum  <= ".number_format($i+0.05,2)." ) || ( classdum  > ".number_format($z-0.50,2)." && classdum <" .($z==95 ? "=100" : number_format($z,2) ) . " ) : $y;\n\t\t";
            echo " grade.val('".number_format($y,2)."'); output.empty().append(grade.val()); remarks.empty().append('<span class=\'label label-info\'>PASSED</span>'); \n\t\t";
            echo " hRemarks.val('Passed'); \n\t\t";
            $y = $y + 0.10;
          }
        ?>
      <?php
          // echo "$(this).parent().closest('tr').removeAttr('bgcolor').attr('bgcolor','#dff0d8');\n\t\t";
          echo " break; \n\t\t";
        }
      ?>
      case ( classdum >= 40.00 && classdum < 74.50 ) || classdum == 5.00 :
          grade.val('5.00'); output.empty().append(grade.val()); hRemarks.val('Failed');
          remarks.empty().append('<span class=\'label label-important\'>FAILED</span>');
          <!-- $(this).parent().closest('tr').removeAttr('bgcolor').attr('bgcolor','#F2DEDE'); -->
          break;
      case ( classdum == 6.00 ) :
          grade.val('INC'); output.empty().append(grade.val()); hRemarks.val('Incomplete');
          remarks.empty().append('<span class=\'label label-important\'>INCOMPLETE</span>');
          <!-- $(this).parent().closest('tr').removeAttr('bgcolor').attr('bgcolor','#FFFF99'); -->
          break;
      case ( classdum == 7.00 ) :
          grade.val('UD'); output.empty().append(grade.val()); hRemarks.val('Unofficially Dropped');
          remarks.empty().append('<span class=\'label label-warning\'>UNOFFICIALLY DROPPED</span>');
          <!-- $(this).parent().closest('tr').removeAttr('bgcolor').attr('bgcolor','#FFFFFF'); -->
          break;
      default:
          alert('INVALID GRADE ENTERED');
          $(this).val('7.00');
          grade.val('UD'); output.empty().append(grade.val()); hRemarks.val('Unofficially Dropped');
          remarks.empty().append('<span class=\'label label-warning\'>UNOFFICIALLY DROPPED</span>');
          <!-- $(this).parent().closest('tr').attr('bgcolor','#FFFFFF'); -->
          this.select();
          error = true;
          break;
    } // end switch
  });

  $('input[type=text]').keydown(function(e){

      if(e.keyCode == 13) { // enter button in ASCII code

        e.preventDefault();
        var classdum = $(this).val(),
            grade = $(this).parent().closest('td').find('input[type=hidden]'),
            output = $(this).parent().closest('td').next('td').find('span.uneditable-input'),
            remarks = $(this).parent().closest('td').next('td').next('td'),
            hRemarks = $(this).parent().closest('td').next('td').find('input[type=hidden]'),
            error = false;
            classdum = classdum.toUpperCase();
            switch( true ) {
              <?php
                for($i = 1.00, $y = 1.00, $z = 95; $i <= 2.95; $i = $i + 0.05, $z = $z - 0.50) {
                  $num = number_format($i,2);
                  if (substr($num, -1) == "5") {
                    echo " case ( classdum > " .number_format($i,2)." && classdum". (number_format($i+0.05,2)==3.00 ? " <= " : " < ") . number_format($i+0.05,2)." ) || ( classdum  >= ".(number_format($z-0.50,2) == 75.00 ? 74.50 : number_format($z-0.50,2)) ." && classdum <= " .number_format($z,2)." ) : $y;\n\t\t";
                    echo " grade.val('".number_format($y,2)."'); output.empty().append(grade.val()); remarks.empty().append('<span class=\'label label-info\'>PASSED</span>'); \n\t\t";
                    echo " hRemarks.val('Passed'); \n\t\t";
                  } else {
                    echo " case ( classdum >= " .number_format($i,2)." && classdum  <= ".number_format($i+0.05,2)." ) || ( classdum  > ".number_format($z-0.50,2)." && classdum <" .($z==95 ? "=100" : number_format($z,2) ) . " ) : $y;\n\t\t";
                    echo " grade.val('".number_format($y,2)."'); output.empty().append(grade.val()); remarks.empty().append('<span class=\'label label-info\'>PASSED</span>'); \n\t\t";
                    echo " hRemarks.val('Passed'); \n\t\t";
                    $y = $y + 0.10;
                  }
                ?>
              <?php
                  // echo "$(this).parent().closest('tr').removeAttr('bgcolor').attr('bgcolor','#DFF0D8');\n\t\t";
                  echo " break; \n\t\t";
                }
              ?>
              case ( classdum >= 40.00 && classdum < 74.50 ) || classdum == 5.00 :
                  grade.val('5.00'); output.empty().append(grade.val()); hRemarks.val('Failed');
                  remarks.empty().append('<span class=\'label label-important\'>FAILED</span>');
                  <!-- $(this).parent().closest('tr').removeAttr('bgcolor').attr('bgcolor','#F2DEDE'); -->
                  break;
              case ( classdum == 6.00 ) :
                  grade.val('INC'); output.empty().append(grade.val()); hRemarks.val('Incomplete');
                  remarks.empty().append('<span class=\'label label-important\'>INCOMPLETE</span>');
                  <!-- $(this).parent().closest('tr').removeAttr('bgcolor').attr('bgcolor','#FCF8E3'); -->
                  break;
              case ( classdum == 7.00 ) :
                  grade.val('UD'); output.empty().append(grade.val()); hRemarks.val('Unofficially Dropped');
                  remarks.empty().append('<span class=\'label label-warning\'>UNOFFICIALLY DROPPED</span>');
                  <!-- $(this).parent().closest('tr').removeAttr('bgcolor').attr('bgcolor','#FFFFFF'); -->
                  break;
              default:
                  alert('INVALID GRADE ENTERED');
                  $(this).val('7.00');
                  grade.val('UD'); output.empty().append(grade.val()); hRemarks.val('Unofficially Dropped');
                  remarks.empty().append('<span class=\'label label-warning\'>UNOFFICIALLY DROPPED</span>');
                  <!-- $(this).parent().closest('tr').removeAttr('bgcolor').attr('bgcolor','#FFFFFF'); -->
                  this.select();
                  error = true;
                  break;
            } // end switch

          var next_idx = $('input[type=text]').index(this) + 1;
          var tot_idx = $('body').find('input[type=text]').length;

          if (error == false) {
              $('input[type=text]:eq(' + next_idx + ')').focus();
              $('input[type=text]:eq(' + next_idx + ')').select();
          }

          if(tot_idx == next_idx)
            $('#btnSend').focus().select();
      } // end if

  });

});
