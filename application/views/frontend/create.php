<?php $this->load->view('includes/header', ['title' => 'Add Employee']); ?>

<main style="max-width: 800px;margin: 0 auto;">
    
    <?php if ($this->session->flashdata('message')): ?>
        <div style="background:#e95757;color:#fff;padding:15px;border-radius:5px">
            <?php 
                echo $this->session->flashdata('message'); 
                $this->session->unset_userdata('message');     
            ?>
        </div>
    <?php endif; ?>

    <h1>Add Employee</h1>

    <a href="<?= base_url('employee') ?>" style="text-align:right;display:block;margin-bottom:20px;text-decoration:underline;color:red">Back</a>

    <?= form_open_multipart('employee/store'); ?>

    <label for="name">Name</label>
    <input type="text" name="name" value="<?php echo set_value('name'); ?>">
    <?php echo form_error('name'); ?>

    <label for="email">Email</label>
    <input type="email" name="email" value="<?php echo set_value('email'); ?>">
    <?php echo form_error('email'); ?>

    <label for="image">Upload Avatar</label>
    <input type="file" name="image" accept="image/*">

    <label for="file">Upload File</label>
    <input type="file" name="file" accept=".xlsx, .xls, .csv">

    <input type="submit" value="Save">

    </form>
</main>
<?php $this->load->view('includes/footer'); ?>