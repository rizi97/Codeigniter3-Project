<?php $this->load->view('includes/header', ['title' => 'Delete Employee']); ?>

<h1>Delete Employee</h1>

<a href="<?= base_url('employee') ?>" style="text-align:right;display:block;margin-bottom:20px;text-decoration:underline;color:red">Back</a>

<?= form_open('employee/deleteEmployee/' . $id ); ?>

    <p>Are you sure?</p> 

    <input type="submit" value="Yes">

</form>

<?php $this->load->view('includes/footer'); ?>