<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/navigation'); ?>
<?php $this->load->view($content);?>
<?php if(!((strtolower($this->uri->segment(2,'')) == 'class_list') || (strtolower($this->uri->segment(2,'')) == 'confirm_grades'))): ?>
<?php $this->load->view('template/footer');?>
<?php endif ?>