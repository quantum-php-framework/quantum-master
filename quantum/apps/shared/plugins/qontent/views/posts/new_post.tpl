<!-- Main Container -->
<main id="main-container">

    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Block Forms <small class="d-block d-sm-inline-block mt-2 mt-sm-0 font-size-base font-w400 text-muted">Easily integrated in your blocks.</small>
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Blocks</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Forms</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Form Submission in Options -->
        <div class="row">
            <div class="col-md-8">
                <form action="be_blocks_forms.html" method="POST">
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Block Form</h3>
                            <div class="block-options">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-sm btn-alt-primary">
                                    Reset
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="row justify-content-center py-sm-3 py-md-5">
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="block-form1-username">Username</label>
                                        <input type="text" class="form-control form-control-alt" id="block-form1-username" name="block-form1-username" placeholder="Enter your username..">
                                    </div>
                                    <div class="form-group">
                                        <label for="block-form1-password">Password</label>
                                        <input type="password" class="form-control form-control-alt" id="block-form1-password" name="block-form1-password" placeholder="Enter your password..">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="block-form1-remember-me" name="block-form1-remember-me">
                                            <label class="custom-control-label" for="block-form1-remember-me">Remember Me?</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form action="be_blocks_forms.html" method="POST">
                    <div class="block block-rounded">
                        <div class="block-header block-header-default block-header-rtl">
                            <h3 class="block-title">Block Form</h3>
                            <div class="block-options">
                                <button type="reset" class="btn btn-sm btn-alt-primary">
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="row justify-content-center py-sm-3 py-md-5">
                                <div class="col-sm-10 col-md-8">
                                    <div class="form-group">
                                        <label for="block-form2-username">Username</label>
                                        <input type="text" class="form-control form-control-alt" id="block-form2-username" name="block-form2-username" placeholder="Enter your username..">
                                    </div>
                                    <div class="form-group">
                                        <label for="block-form2-password">Password</label>
                                        <input type="password" class="form-control form-control-alt" id="block-form2-password" name="block-form2-password" placeholder="Enter your password..">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="block-form2-remember-me" name="block-form2-remember-me">
                                            <label class="custom-control-label" for="block-form2-remember-me">Remember Me?</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Form Submission in Options -->
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->