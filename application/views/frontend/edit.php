<?php $this->load->view('includes/header', ['title' => 'Edit Employee']); ?>

<?php if ($this->session->flashdata('message')): ?>
    <div style="background:#e95757;color:#fff;padding:15px;border-radius:5px">
        <?php 
            echo $this->session->flashdata('message'); 
            $this->session->unset_userdata('message');     
        ?>
    </div>
<?php endif; ?>

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