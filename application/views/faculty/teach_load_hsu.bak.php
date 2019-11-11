<div class="heading">
  <h3>HSU Courses</h3>
  <hr align="left">
</div>
<div class="panel-default panel">
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th style="width: 5%">CFN</th>
            <th style="width: 10%">Course Code</th>
            <th style="width: 25%">Course Description</th>
            <th style="width: 5%">Units</th>
            <th style="width: 10%">Section</th>
            <th style="width: 5%">Status</th>
            <th style="width: 5%">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($teach_load_hsu as $load): ?>
          <tr <?php echo is_graded($load->is_graded) ?> >
            <td><?php echo $load->cfn ?></td>
            <td><?php echo $load->CourseCode ?></td>
            <td><?php echo $load->CourseDesc ?></td>
            <td><?php echo $load->Units ?></td>
            <td><?php echo $load->year_section ?></td>
            <td>
              <?php if ($load->is_graded == 0): ?>
              <span class="label label-success"><?php echo "Encode Grade".$load->is_graded; ?></span>
              <?php else: ?>
              <span class="label label-default"><?php echo "Done".$load->is_graded; ?></span>
              <?php endif ?>

            </td>
            <td>
              <?php if ($load->is_graded == 1 ): ?>
                <?php if ($load->CourseCode != 'RHGP'): ?>
                  <?php echo anchor(base_url('hsu/' . $load->sched_id . '/print_gradesheet'), '<i class="fa fa-print"></i> Draft Copy', array('class' => 'btn btn-default', 'target' => '_blank')); ?>
                <?php endif ?>
              <?php else: ?>
                <?php if ($load->CourseCode == 'RHGP'): ?>
                  <!-- ENCODING OF RHGP GRADE WILL BE DONE ON A SEPERATE DATE. PLEASE BE ADVISED BY THE HSU OFFICE. -->
                  <?php echo anchor(base_url('hsu/' . $load->sched_id . '/gradesheet'), '<i class="fa fa-pencil"></i> Encode', array('class' => 'btn btn-primary')); ?>
                <?php else: ?>
              <?php echo anchor(base_url('hsu/' . $load->sched_id . '/gradesheet'), '<i class="fa fa-pencil"></i> Encode', array('class' => 'btn btn-primary')); ?>
                <?php endif ?>
              <?php endif ?>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table><!-- table -->
    </div><!-- table-responsive -->
  </div><!-- panel-body -->

</div><!-- panel-default -->
<div class="oval-shadow"></div>
