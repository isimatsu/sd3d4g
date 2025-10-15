<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];
    }

    
?>
<div>
</div>
<a href="https://aso2301128.boy.jp/sd3d4g/account/" class="header-account">
    <span class="material-symbols-rounded">account_circle</span>
    <p><?php echo $user_name ?></p>
</a>