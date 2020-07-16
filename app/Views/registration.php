<?php

?>
<!-- Scripts -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- Scripts -->

<div class="container register-form">
    <div class="form">
        <div class="note">
            <p>Registration form</p>
        </div>
        <div class="register-errors">
            <?php echo $data ?>
        </div>
        <form method="post" accept-charset="utf-8" action="/registration">
            <div class="form-content">
                <div class="row justify-content-md-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Your Name *" value=""/>
                        </div>
                        <div class="form-group">
                            <input type="text" name="email" class="form-control" placeholder="Your Email *" value=""/>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Your Password *"
                                   value=""/>
                        </div>
                        <div class="g-recaptcha pb-3" data-sitekey="<?php echo getenv("CAPTCHA_KEY") ?>"></div>
                        <button type="submit" class="btnSubmit">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
