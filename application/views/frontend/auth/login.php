<?php $this->load->view('includes/header', ['title' => 'Login']); ?>

<main style="max-width: 800px;margin: 0 auto;">
    
<?php $this->load->view('includes/message_display'); ?>

    <h1>Login</h1>

    <?= form_open('login_verify'); ?>
        <label for="username">Name</label>
        <input type="text" name="username" value="<?php echo set_value('username'); ?>">
        <?php echo form_error('username'); ?>

        <label for="password">Password</label>
        <input type="password" name="password" value="<?php echo set_value('password'); ?>">
        <?php echo form_error('password'); ?>

        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="<?php echo site_url('/'); ?>">Register</a></p>
</main>

<?php $this->load->view('includes/footer'); ?>