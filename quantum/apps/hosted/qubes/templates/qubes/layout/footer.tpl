<!-- Footer -->
<footer id="page-footer" class="bg-body-light">
    <div class="content py-3">
        <div class="row font-size-sm">
            <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
                The Quantum Framework created by Carlos Barbosa</a>
            </div>
            <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
                <a class="font-w600" href="https://quantumframework.info/releases" target="_blank">Quantum Kernel {$qm_version}</a> </span> | Qubes {$qubes_version}
            </div>
        </div>
    </div>
</footer>
<!-- END Footer -->

<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Apps</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row gutters-tiny">
                        <div class="col-6">
                            <!-- CRM -->
                            <a class="block block-rounded block-link-shadow bg-body" href="javascript:void(0)">
                                <div class="block-content text-center">
                                    <i class="si si-speedometer fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        CRM
                                    </p>
                                </div>
                            </a>
                            <!-- END CRM -->
                        </div>
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="javascript:void(0)">
                                <div class="block-content text-center">
                                    <i class="si si-rocket fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Products
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="javascript:void(0)">
                                <div class="block-content text-center">
                                    <i class="si si-plane fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Sales
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="javascript:void(0)">
                                <div class="block-content text-center">
                                    <i class="si si-wallet fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Payments
                                    </p>
                                </div>
                            </a>
                            <!-- END Payments -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Apps Modal -->
</div>
<!-- END Page Container -->

<!--
    OneUI JS Core

    Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
    to handle those dependencies through webpack. Please check out /static/templates/qubes/assets/_es6/main/bootstrap.js for more info.

    If you like, you could also include them separately directly from the /static/templates/qubes/assets/js/core folder in the following
    order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

    /static/templates/qubes/assets/js/core/jquery.min.js
    /static/templates/qubes/assets/js/core/bootstrap.bundle.min.js
    /static/templates/qubes/assets/js/core/simplebar.min.js
    /static/templates/qubes/assets/js/core/jquery-scrollLock.min.js
    /static/templates/qubes/assets/js/core/jquery.appear.min.js
    /static/templates/qubes/assets/js/core/js.cookie.min.js
-->
<script src="/static/templates/qubes/assets/js/oneui.core.min.js"></script>

<!--
    OneUI JS

    Custom functionality including Blocks/Layout API as well as other vital and optional helpers
    webpack is putting everything together at /static/templates/qubes/assets/_es6/main/app.js
-->
<script src="/static/templates/qubes/assets/js/oneui.app.min.js"></script>

<!-- Page JS Plugins -->
<script src="/static/templates/qubes/assets/js/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="/static/templates/qubes/assets/js/plugins/chart.js/Chart.bundle.min.js"></script>

<!-- Page JS Code -->
<script src="/static/templates/qubes/assets/js/pages/be_pages_dashboard.min.js"></script>

<!-- Page JS Helpers (jQuery Sparkline Plugins) -->
<script>jQuery(function () { One.helpers(['sparkline']); });</script>
</body>
</html>
