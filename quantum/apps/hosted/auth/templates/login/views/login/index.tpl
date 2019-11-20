

<form action="" method="post">
    <div class="form-group">
        <label class="label">Email</label>
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Username" name="username" value="{if isset($username)}{$username}{/if}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="icon-check"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="label">Password</label>
        <div class="input-group">
            <input type="password" class="form-control" placeholder="*********" name="password">
            <div class="input-group-append">
                <span class="input-group-text"><i class="icon-check"></i></span>
            </div>
        </div>
    </div>

    {if isset($recaptcha_pub_key)}
        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="{$recaptcha_pub_key}"></div>
        </div>
    {/if}

    <div class="form-group">
        <button class="btn btn-primary submit-btn btn-block">Login</button>
    </div>
    <div class="form-group d-flex justify-content-between">
        <div class="form-check form-check-flat mt-0">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="remember_me" checked value="1">
                Remember my email address
            </label>
        </div>
        <a href="/password/forgot" class="text-small forgot-password text-black">Forgot Password</a>
    </div>
    {$csrf}


</form>
    <div class="form-group">
        <button class="btn btn-block g-login" onclick="window.location.href='{$codelogin_url}'">Log in with Employee Code</button>
    </div>

    <!--
    <div class="text-block text-center my-3">
        <span class="text-small font-weight-semibold">Not a member ?</span>
        <a href="register.html" class="text-black text-small">Create new account</a>
    </div> -->

