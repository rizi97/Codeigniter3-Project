<?php $this->load->view('includes/header', ['title' => 'Employee Data']); ?>

    <?php if( $this->session->flashdata('message') ) : ?>
        <div style="background:#c8f1c8;padding:15px;border-radius:5px">
            <?php 
                echo $this->session->flashdata('message'); 
                $this->session->unset_userdata('message');     
            ?>
        </div>
    <?php endif; ?>

    <h1>Employee Data</h1>

    <div style="display: flex; justify-content: end; gap: 20px">
        <a href="<?= base_url('employee/create') ?>" style="text-align:right;display:block;margin-bottom:20px;text-decoration:underline">Add Employee</a>
        <a href="<?= base_url('logout') ?>" style="text-align:right;display:block;margin-bottom:20px;text-decoration:underline">Logout</a>
    </div>

    <table border="1">
        <tr>
            <th>Avatar</th>
            <th>Name</th>
            <th>Email</th>
            <th>PDF Link</th>
            <th>Download</th>
            <th>Actions</th>
        </tr>

        <?php foreach($data as $employee): ?>  
            <tr>
                <td>
                    <img src="<?= base_url("uploads/{$employee->id}/{$employee->avatar}") ?>">
                </td>
                <td><?= esc_html($employee->name) ?></td>
                <td><?= esc_html( $employee->email ) ?></td>
                <td><a href="<?= base_url("uploads/{$employee->id}/form_data_{$employee->id}.pdf") ?>" target="_blank">Link</a></td>
                <td><a href="<?= base_url("employee/downloadFiles/{$employee->id}") ?>">Zip</a></td>
                <td>
                    <a href="<?= base_url('employee/edit/' . $employee->id ) ?>">Edit</a> | 
                    <a href="<?= base_url('employee/delete/' . $employee->id ) ?>" style="color:red">Delete</a></td>
            </tr>
        <?php endforeach; ?>

    </table>

<?php $this->load->view('includes/footer'); ?>


