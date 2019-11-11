<article class="teaching-load">
  <div class="professor-information">
    <div class="heading">
      <h3>Professor Information</h3>
      <hr align="left">
    </div>
    <div class="row">
      <div class="col-md-2">

        <?php

        $file = 'https://umak.edu.ph/olfe/prof_img/'.$this->session->userdata('faculty_id').'.JPG';
        $file_headers = @get_headers($file);

        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $image_dir = 'https://umak.edu.ph/olfe/components/images/misc/user-default.gif';
        }
        else {
            $image_dir = $file;
        }

        echo img(array('src' => $image_dir, 'class' => 'thumbnail img-responsive'));
         ?>

      </div><!-- col-md-2 -->
      <div class="col-md-10">

        <div class="col-md-6">
          <div class="table-responsive">
            <table class="table">
              <tbody>
              <tr>
                <td><i class="fa fa-user"></i> <small>Name</small></td>
                <td class="text-right"><?php echo $this->session->userdata('lastname'); ?>, <?php echo $this->session->userdata('firstname'); ?> <?php echo $this->session->userdata('middlename'); ?></td>
              </tr>
              <tr>
                <td><i class="fa fa-tags"></i> <small>Username</small></td>
                <td class="text-right"><?php  echo $this->session->userdata('username'); ?></td>
              </tr>
              </tbody>
            </table><!-- table -->
          </div><!-- table-responsive -->
        </div><!-- col-md-7 -->
        <div class="col-md-6">
          <div class="table-responsive">
            <table class="table">
            <tbody>
              <tr>
                <td><i class="fa fa-home"></i> <small>Period</small></td>
                <td class="text-right">A.Y. <?php echo $this->session->userdata('sy_desc'); ?>, <?php echo $this->session->userdata('sem_desc'); ?></td>
              </tr>
            </tbody>
            </table><!-- table -->
          </div><!-- table-responsive -->
        </div><!-- col-md-5 -->
        <div class="clearfix"></div>
        <?php if ($this->studgrade_m->get_pe_subjects() == TRUE): ?>
          <div class="alert alert-success">
            <h3 style="margin:0">NOTE:</h3>
                <p style="margin-left:28px; ">Students with Non-board Program who has <b>PE 1</b> subject will have the same grade on <b>PE 2</b>, Two separate grade sheet will have be provided for this case.</p>
          </div>
        <?php endif ?>
      </div>

    </div><!-- row -->
  </div><!-- professor-information -->

  <div class="courses">

    <?php if ( ! empty($teach_load)): ?>

        <div class="heading">
          <h3>Courses</h3>
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
                  <?php foreach ($teach_load as $load): ?>
                  <tr <?php echo is_graded($load->is_graded) ?> >
                    <td><?php echo $load->cfn ?></td>
                    <td><?php echo $load->CourseCode ?></td>
                    <td><?php echo $load->CourseDesc ?></td>
                    <td><?php echo $load->Units ?></td>
                    <td><?php echo $load->Year . '-' . $load->Section ?></td>
                    <td>
                      <?php
                        if ($load->is_graded == 0)
                        {
                          if ($load->IsGradSection)
                          {
                            echo '<span class="label label-default">Done Encoding for Graduating Students</span>';
                          }
                          else
                          {
                            echo '<span class="label label-success">Encode Grades</span>';
                          }
                        }
                        else
                        {
                          $last_encode_date = substr($load->submitted_at, 0, 10);
                          if ($load->is_graded == 1 && $load->is_printed == 0 && $load->uds > 0 && (($last_encode_date >= $grad_date_start && $last_encode_date <= $grad_date_end) OR $load->leclab == 6))
                          {
                            echo '<span class="label label-success">Encode Grades</span>';
                          }
                          else
                          {
                            if ($load->uds > 0 && $load->is_printed == 0 && $last_encode_date < $under_grad_date)
                            echo '<span class="label label-success">Encode Grades</span>';
                            else
                            {
                              if ($last_encode_date < $under_grad_date)
                              {
                                echo '<span class="label label-success">Encode Grades</span>';
                              }
                              else
                              {
                                echo '<span class="label label-default">Done</span>';
                              }
                            }
                          }
                        }
                        // dump($load->is_printed);
                        // dump($load->uds);
                      ?>
                    </td>
                    <td>
                    <?php

                        if ($date_now >= $grad_date_start && $date_now <= $grad_date_end)
                        {
                            if ($load->is_graded == 1)
                            {
                              echo anchor(base_url('gradebook/' . $load->sched_id . '/print_gradesheet'), '<i class="fa fa-print"></i> Draft Copy', array('class' => 'btn btn-primary', 'target' => '_blank'));
                            }
                            if ( ! ($load->is_graded == 1 AND $load->uds == 0))
                            {
                              echo anchor(base_url('gradebook/' . $load->sched_id . '/gradesheet'), '<i class="fa fa-pencil"></i> Encode Graduating Students', array('class' => 'btn btn-primary'));
                            }
                        }
                        else
                        {
                          // dump($load);
                          // $last_encode_date = substr($load->updated_at, 0, 10);
                          $last_encode_date = substr($load->submitted_at, 0, 10);
                          /*dump($last_encode_date);
                          dump($grad_date_start);
                          dump($grad_date_end);*/

                          // echo $last_encode_date;
                          // echo $grad_date_end;
                          if ($load->is_graded == 1)
                          {
                            if ($load->is_printed == 0)
                            {
                              // if ($load->uds > 0 && (($last_encode_date >= $grad_date_start && $last_encode_date <= $grad_date_end) OR $load->leclab == 6));
                              if (($load->uds > 0 OR $load->leclab == 6) && $last_encode_date < $under_grad_date)
                              {
                                echo anchor(base_url('gradebook/' . $load->sched_id . '/gradesheet'), '<i class="fa fa-pencil"></i> Re-Encode', array('class' => 'btn btn-primary'));
                              }
                              else
                              {
                                if ($last_encode_date < $under_grad_date)
                                {
                                  echo anchor(base_url('gradebook/' . $load->sched_id . '/gradesheet'), '<i class="fa fa-pencil"></i> Re-Encode', array('class' => 'btn btn-primary'));
                                }
                                else
                                {
                                  echo anchor(base_url('gradebook/' . $load->sched_id . '/print_gradesheet'), '<i class="fa fa-print"></i> Draft Copy', array('class' => 'btn btn-primary', 'target' => '_blank'));
                                }
                              }
                            }
                            else
                            {
                                  if ($last_encode_date < $under_grad_date)
                                  {
                                    echo anchor(base_url('gradebook/' . $load->sched_id . '/gradesheet'), '<i class="fa fa-pencil"></i> Re-Encode', array('class' => 'btn btn-primary'));
                                  }
                                  else
                                  {
                                    echo anchor(base_url('gradebook/' . $load->sched_id . '/print_gradesheet'), '<i class="fa fa-print"></i> Draft Copy', array('class' => 'btn btn-primary', 'target' => '_blank'));
                                  }
                            }
                          }
                          else
                          {
                            if ( !$load->IsGradSection)
                            echo anchor(base_url('gradebook/' . $load->sched_id . '/gradesheet'), '<i class="fa fa-pencil"></i> Encode', array('class' => 'btn btn-primary'));
                          	else
                          	{
                          		if ($load->is_printed == 0)
  	                            {
  	                              // if ( $last_encode_date >= $grad_date_start && $last_encode_date <= $grad_date_end)
  	                              // {
  	                                echo anchor(base_url('gradebook/' . $load->sched_id . '/gradesheet'), '<i class="fa fa-pencil"></i> Re-Encode', array('class' => 'btn btn-primary'));
  	                              // }
  	                            }
                          	}
                          }
                        }

                      ?>
                    </td>
                  </tr>
                  <?php endforeach ?>
                </tbody>
              </table><!-- table -->
            </div><!-- table-responsive -->
          </div><!-- panel-body -->

        </div><!-- panel-default -->
        <div class="oval-shadow"></div>

    <?php endif ?>


    <?php if( ! empty($teach_load_hsu)): ?>

      <?php $this->load->view('faculty/teach_load_hsu'); ?>

    <?php endif ?>
  </div><!-- courses -->


</article><!-- teaching-load -->