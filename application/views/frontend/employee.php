<?php $this->load->view('includes/header', ['title' => 'Employee Data']); ?>

    <?php if( $this->session->flashdata('message') ) : ?>
        <div style="background:#c8f1c8;padding:15px;border-radius:5px">
            <?= $this->session->flashdata('message') ?>
            <?php $this->session->unset_userdata('message'); ?>
        </div>
    <?php endif; ?>

    <h1>Employee Data</h1>

    <a href="<?= base_url('employee/create') ?>" style="text-align:right;display:block;margin-bottom:20px;text-decoration:underline">Add Employee</a>

    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>

        <?php foreach($data as $employee): ?>   
            <tr>
                <td><?= $employee->name ?></td>
                <td><?= $employee->email ?></td>
                <td>
                    <a href="<?= base_url('employee/edit/' . $employee->id ) ?>">Edit</a> | 
                    <a href="<?= base_url('employee/delete/' . $employee->id ) ?>" style="color:red">Delete</a></td>
            </tr>
        <?php endforeach; ?>

    </table>

<?php $this->load->view('includes/footer'); ?>


