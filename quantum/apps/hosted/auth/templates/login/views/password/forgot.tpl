
<form action="" method="post">
    <div class="form-group">
        <label class="label">Your email</label>
        <div class="input-group">
            <input type="text" class="form-control" placeholder="" name="email">
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

        <a href="#" class="text-small forgot-password text-black" onclick="window.history.go(-1); return false;">< Back</a>
    </div>

    {$csrf}

</form>

