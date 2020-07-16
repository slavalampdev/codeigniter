<?php
?>
<div class="container register-form">
    <div class="form">
        <div class="note">
            <p>Welcome to our home page</p>
        </div>
        <ul class="list-group">
            <?php foreach ($data as $user): ?>
            <li class="list-group-item"><?php echo $user->name ?> - <span class="badge badge-primary badge-pill" ><?php echo $user->email ?></span></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>