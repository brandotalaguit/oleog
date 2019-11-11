$(function () {
  var btnid = 0;

  $(document).on('click', '.btnModalThesisTitle', function () {
    
    var $btn = $(this);
    var uri = $(this).data('uri');
    var $tr = $(this).closest('tr');
    var sched_id = $('input:hidden[name=sched_id]').val();
    var studno = $tr.find('td:eq(4) input:hidden.StudNo').val();

    btnid = $btn.attr('id');
    $btn.removeClass('btn-default');
    
    var form_data = {'sched_id': sched_id, 'StudNo': studno};

    var request = $.ajax({
      type: "POST",
      url: uri,
      data: form_data,
      success: function(data){

        // reset btn class
        $btn.removeClass('btn-default');
        $btn.removeClass('btn-primary');
        $btn.addClass(data.colorcode);

        // store variables to control-form
        $('textarea[name=thesis_title]').val(data.thesis);
        $('input:hidden[name=title_id]').val(data.thesis_id);
        $('input:hidden[name=thesis_sched_id]').val(sched_id);
        $('input:hidden[name=thesis_studno]').val(studno);

        console.log(uri);
        console.log(data.thesis);
        console.log(data.thesis_id);
        console.log(sched_id);
        console.log(studno);

      },
      dataType: 'json'
    });
    //  end ajax request  

    request.fail(function (jqXHR, textStatus) {
      console.log(uri);
      console.log(textStatus);
      $('.modal').modal('hide');
      $("#errMsg .modal-body").empty().append("The server has failed to received the data that you've sent. Please try to log-out and login again.");
      $("#errMsg").modal('show');
    })

  });


  $(document).on('click', '#btnSaveThesisTitle', function () {
    
    var $tr, thesis_id, thesis, studno, sched_id, uri, form_data;
    var $btn = $('#'+btnid);

    thesis_id = $('input:hidden[name=thesis_id]').val();
    thesis = $('textarea[name=thesis_title]').val();
    sched_id = $('input:hidden[name=thesis_sched_id]').val();
    studno = $('input:hidden[name=thesis_studno]').val();
    uri = $('input:hidden[name=thesis_uri]').val();

    form_data = {
        'thesis_id': thesis_id,
        'thesis_sched_id': sched_id,
        'thesis_title': thesis,
        'thesis_studno': studno,
      };

    var request = $.ajax({
      type: "POST",
      url: uri,
      data: form_data,
      success: function(data){

        // reset to default values
        $btn.removeClass('btn-default');
        $btn.removeClass('btn-primary');
        $btn.addClass(data.colorcode);

        console.log(uri);
        console.log($btn);
        console.log(data.colorcor);

        $('.modalThesisTitle').modal('hide');
        $("#errMsg .modal-body").empty().append("Record has been successfully saved.");
        $("#errMsg").modal('show');

      },
      dataType: 'json'
    });
    //  end ajax request  

    request.fail(function (jqXHR, textStatus) {
      console.log(uri);
      console.log(textStatus);
      $('.modal').modal('hide');
      $("#errMsg .modal-body").empty().append("The server has failed to received the data that you've sent. Please re-encode the data again.");
      $("#errMsg").modal('show');
    })


  });


  
}); // end jquery
