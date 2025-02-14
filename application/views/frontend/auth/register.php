<?php $this->load->view('includes/header', ['title' => 'Registration']); ?>

<main style="max-width: 800px;margin: 0 auto;">
    
    <?php $this->load->view('includes/message_display'); ?>

    <h1>Registration</h1>

    <?= form_open('auth/register'); ?>
        <label for="username">Name</label>
        <input type="text" name="username" value="<?php echo set_value('username'); ?>">
        <?php echo form_error('username'); ?>

        <label for="email">Email</label>
        <input type="email" name="email" value="<?php echo set_value('email'); ?>">
        <?php echo form_error('email'); ?>

        <label for="password">Password</label>
        <input type="password" name="password" value="<?php echo set_value('password'); ?>">
        <?php echo form_error('password'); ?>

        <label for="role">Select Role</label>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <input type="submit" value="Register">
    </form>

    <p>Already have an account? <a href="<?php echo site_url('login'); ?>">Login</a></p>
</main>

<?php $this->load->view('includes/footer'); ?>