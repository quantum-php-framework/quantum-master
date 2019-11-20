
<form action="" method="post">
    <div class="form-group">
        <label class="label">Your new password</label>
        <div class="input-group">
            <input type="password" class="form-control" placeholder="" name="password1">
            <div class="input-group-append">
                <span class="input-group-text"><i class="icon-check"></i></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="label">Confirm your new password</label>
        <div class="input-group">
            <input type="password" class="form-control" placeholder="" name="password2">
            <div class="input-group-append">
                <span class="input-group-text"><i class="icon-check"></i></span>
            </div>
        </div>
    </div>

    <input type="hidden" name="token" value="{$token}">
    <div class="form-group">
        <button class="btn btn-primary submit-btn btn-block">Reset</button>
    </div>

    {$csrf}
</form>

