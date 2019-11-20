<form action="{$url}" method="post" id="loginform">
    <div class="form-group" style="min-height: 300px">
        <label class="label">Session starting...</label>
        <div class="input-group">
            <div class="dot-opacity-loader">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>


    <input type="hidden" name="auth_client_token" value="{$auth_client_token}">
    <input type="hidden" name="auth_signature" value="{$auth_signature}">
    <input type="hidden" name="state" value="{$token_state}">

    <input type="hidden" name="csrf" value="{$token_csrf}">

    {if isset($redirect_uri)}
        <input type="hidden" name="redirect_uri" value="{$redirect_uri}">
    {/if}
</form>


{if isset($continue) AND $continue eq true}
<script>
    $(function() {
        setTimeout(function() {
            $('#loginform').submit();
            }, 616);

    });
</script>
{/if}

