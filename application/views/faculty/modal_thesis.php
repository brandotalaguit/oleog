<?php echo form_open(base_url('gradebook/save_thesis_title'), array('class' => 'form-horizontal', 'role' => 'form'), $hidden); ?>
<div class="modal-header text-center">
	THESIS TITLE
</div>
<div class="modal-body">
<span id="helpBlock" class="help-block">Please type the THESIS TITLE below:</span>
  	<textarea class="form-control" rows="3" name="thesis_title"></textarea>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btnSaveThesisTitle">Submit</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<?php echo form_close(); ?>
