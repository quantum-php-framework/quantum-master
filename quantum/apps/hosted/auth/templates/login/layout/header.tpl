<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{$title_for_layout}</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="/static/templates/flare/vendors/iconfonts/simple-line-icon/css/simple-line-icons.css">
    <link rel="stylesheet" href="/static/templates/flare/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/static/templates/flare/vendors/css/vendor.bundle.addons.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="/static/templates/flare/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="/static/templates/flare/images/favicon.png" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="/static/templates/flare/vendors/js/vendor.bundle.base.js"></script>
    <script src="/static/templates/flare/vendors/js/vendor.bundle.addons.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="/static/templates/flare/js/template.js"></script>
</head>

<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
            <div class="row w-100 mx-auto">
                <div class="col-lg-4 mx-auto">
                    <div class="auto-form-wrapper">
                        {if isset($error)}
                        <div class="alert alert-fill-danger" role="alert">
                            <i class="icon-exclamation"></i>
                            {$error}
                        </div>
                        {/if}

                        {if isset($success)}
                            <div class="alert alert-fill-success" role="alert">
                                <i class="icon-exclamation"></i>
                                {$success}
                            </div>
                        {/if}
                        {if isset($client)}
                            <p>{$client->name} Login</p>
                        {/if}

                        {if isset($user)}
                            <p>Welcome {$user->getFullName()}</p>
                        {/if}
