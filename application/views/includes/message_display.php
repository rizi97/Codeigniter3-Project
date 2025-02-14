<?php

    if ($this->session->flashdata('message')) :

?>
        <div style="background:<?= $this->session->flashdata('type') == 'success' ? "#20780c" : "#e95757" ?>;color:#fff;padding:15px;border-radius:5px">
            <?php 
                echo $this->session->flashdata('message'); 

                $this->session->unset_userdata('message');     
                $this->session->unset_userdata('type');     
            ?>
        </div>
<?php 

    endif; 

?>