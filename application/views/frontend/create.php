<?php $this->load->view('includes/header', ['title' => 'Add Employee']); ?>

<main style="max-width: 800px;margin: 0 auto;">
    <h1>Add Employee</h1>

    <a href="<?= base_url('employee') ?>" style="text-align:right;display:block;margin-bottom:20px;text-decoration:underline;color:red">Back</a>

    <?= form_open_multipart('employee/store'); ?>

    <label for="name">Name</label>
    <input type="text" name="name" value="<?php echo set_value('name'); ?>">
    <?php echo form_error('name'); ?>

    <label for="email">Email</label>
    <input type="email" name="email" value="<?php echo set_value('email'); ?>">
    <?php echo form_error('email'); ?>

    <label for="file">Upload File</label>
    <input type="file" name="file" accept=".xlsx, .xls, .csv">

    <input type="submit" value="Save">

    </form>
</main>
<?php $this->load->view('includes/footer'); ?>