<?php $this->load->view('includes/header', ['title' => 'Edit Employee']); ?>

<h1>Edit Employee</h1>

<a href="<?= base_url('employee') ?>" style="text-align:right;display:block;margin-bottom:20px;text-decoration:underline;color:red">Back</a>

<?= form_open('employee/update/' . $data->id); ?>

    <label for="name">Name</label>
    <input type="text" name="name" value="<?= $data->name ?>">
    <?php echo form_error('name'); ?>

    <label for="email">Email</label>
    <input type="email" name="email" value="<?= $data->email ?>">
    <?php echo form_error('email'); ?>

    <input type="submit" value="Update">

</form>

<?php $this->load->view('includes/footer'); ?>