
<?php
include_once('../includes/session.php');

function drawMessages(){?>
    <div class="messages">
    <?php 
        $messages = getMessages();
        foreach ($messages as $message) { ?>
        <div class="<?=$message['type']?>"><?=$message['content']?></div>
    <?php } 
    clearMessages(); ?>
    </div>
<?php } ?>

<?php 
function drawInactiveSession($text) { ?>
    <span class="error">
    Please <a href="../pages/login.php">Sign In</a> or <a href="../pages/register.php">Register</a> to <?= $text ?></span>
<?php } ?>
