
<form action="" method="post">
    <div class="form-group">
        <label class="label">User Code</label>
        <div class="input-group">
            <input type="text" class="form-control" placeholder="" name="username">
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

        <a href="#" class="text-small forgot-password text-black">Forgot Password</a>
    </div>
    {$csrf}


</form>
<div class="form-group">
    <button class="btn btn-block g-login" onclick="window.location.href='{$regularlogin_url}'">Log in with Username and Password</button>
</div>

