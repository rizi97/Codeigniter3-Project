<?php $this->load->view('includes/header', ['title' => 'Add Employee']); ?>

<h1>Add Employee</h1>

<a href="<?= base_url('employee') ?>" style="text-align:right;display:block;margin-bottom:20px;text-decoration:underline;color:red">Back</a>

<?= form_open('employee/store'); ?>

    <label for="name">Name</label>
    <input type="text" name="name" value="<?php echo set_value('name'); ?>">
    <?php echo form_error('name'); ?>

    <label for="email">Email</label>
    <input type="email" name="email" value="<?php echo set_value('email'); ?>">
    <?php echo form_error('email'); ?>

    <input type="submit" value="Save">

</form>

<?php $this->load->view('includes/footer'); ?>