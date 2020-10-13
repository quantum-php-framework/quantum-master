
<!-- Main Container -->
<main id="main-container">
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Plugins <a href="/plugins/add" class="btn-success btn-sm">Add New</a>
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Qubes</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Plugins</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Search -->
    <div class="content">
        <form action="be_pages_generic_search.html" method="POST">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search..">
                <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-fw fa-search"></i>
                                </span>
                </div>
            </div>
        </form>
    </div>
    <!-- END Search -->

    <!-- Page Content -->
    <div class="content">
        <!-- Results -->
        <div class="block block-rounded overflow-hidden">
            <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#search-projects">Installed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#search-users">Active</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#search-classic">Inactive</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#search-photos">Updates</a>
                </li>
            </ul>
            <div class="block-content tab-content overflow-hidden">
                <!-- Projects -->
                <div class="tab-pane fade fade-up show active" id="search-projects" role="tabpanel">
                    <div class="font-size-h4 font-w600 p-2 mb-4 border-left border-4x border-primary bg-body-light">
                        <span class="text-primary font-w700">{$plugins_count}</span> installed plugins
                    </div>
                    <table class="table table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th style="width: 50%;">Plugin</th>
                            <th class="d-none d-lg-table-cell text-center" style="width: 10%;">Scope</th>
                            <th class="d-none d-lg-table-cell text-center" style="width: 10%;">Loaded By</th>
                            <th class="d-none d-lg-table-cell text-center" style="width: 10%;">Vendor</th>
                            <th class="d-none d-lg-table-cell text-center" style="width: 10%;">Version</th>
                            <th class="text-center" style="width: 20%;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$plugins item=plugin}
                        <tr>
                            <td>
                                <h4 class="h5 mt-3 mb-2">
                                    <a href="javascript:void(0)">{$plugin->getPluginEntryHeader('name')}</a>
                                </h4>
                                <p class="d-none d-sm-block text-muted">
                                    {$plugin->getPluginEntryHeader('description')}
                                </p>

                            <td class="d-none d-lg-table-cell text-center">
                                <span class="badge badge-success">{$plugin->getScope()}</span>
                            </td>

                            <td class="d-none d-lg-table-cell text-center">
                                <span class="badge badge-success">{$plugin->getLoadedByStatus()}</span>
                            </td>
                            <td class="d-none d-lg-table-cell font-size-xl text-center font-w600">{$plugin->getPluginEntryHeader('author')}</td>
                            <td class="font-size-xl text-center font-w600">{$plugin->getPluginEntryHeader('version')}</td>
                            <td class="font-size-xl text-center font-w600">$ 35,287</td>
                        </tr>
                        {/foreach}

                        </tbody>
                    </table>
                    <nav aria-label="Projects Search Navigation">
                        <ul class="pagination pagination-sm">
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)" tabindex="-1" aria-label="Previous">
                                    Prev
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0)">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)" aria-label="Next">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- END Projects -->

                <!-- Users -->
                <div class="tab-pane fade fade-up" id="search-users" role="tabpanel">
                    <div class="font-size-h4 font-w600 p-2 mb-4 border-left border-4x border-primary bg-body-light">
                        <span class="text-primary font-w700">192</span> results found for <mark class="text-danger">client</mark>
                    </div>
                    <table class="table table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="d-none d-sm-table-cell text-center" style="width: 40px;">#</th>
                            <th class="text-center" style="width: 70px;"><i class="si si-user"></i></th>
                            <th>Name</th>
                            <th class="d-none d-sm-table-cell">Email</th>
                            <th class="d-none d-lg-table-cell" style="width: 15%;">Access</th>
                            <th class="text-center" style="width: 80px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="d-none d-sm-table-cell text-center">
                                <span class="badge badge-pill badge-primary">1</span>
                            </td>
                            <td class="text-center">
                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar15.jpg" alt="">
                            </td>
                            <td class="font-w600">
                                <a href="javascript:void(0)">Ryan Flores</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                client1@example.com
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge badge-danger">Disabled</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit Client">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete Client">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="d-none d-sm-table-cell text-center">
                                <span class="badge badge-pill badge-primary">2</span>
                            </td>
                            <td class="text-center">
                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar5.jpg" alt="">
                            </td>
                            <td class="font-w600">
                                <a href="javascript:void(0)">Barbara Scott</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                client2@example.com
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge badge-primary">Personal</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit Client">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete Client">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="d-none d-sm-table-cell text-center">
                                <span class="badge badge-pill badge-primary">3</span>
                            </td>
                            <td class="text-center">
                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar2.jpg" alt="">
                            </td>
                            <td class="font-w600">
                                <a href="javascript:void(0)">Megan Fuller</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                client3@example.com
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge badge-info">Business</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit Client">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete Client">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="d-none d-sm-table-cell text-center">
                                <span class="badge badge-pill badge-primary">4</span>
                            </td>
                            <td class="text-center">
                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar14.jpg" alt="">
                            </td>
                            <td class="font-w600">
                                <a href="javascript:void(0)">Jack Greene</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                client4@example.com
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge badge-info">Business</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit Client">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete Client">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="d-none d-sm-table-cell text-center">
                                <span class="badge badge-pill badge-primary">5</span>
                            </td>
                            <td class="text-center">
                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar11.jpg" alt="">
                            </td>
                            <td class="font-w600">
                                <a href="javascript:void(0)">Henry Harrison</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                client5@example.com
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge badge-warning">Trial</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit Client">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete Client">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="d-none d-sm-table-cell text-center">
                                <span class="badge badge-pill badge-primary">6</span>
                            </td>
                            <td class="text-center">
                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar2.jpg" alt="">
                            </td>
                            <td class="font-w600">
                                <a href="javascript:void(0)">Megan Fuller</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                client6@example.com
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge badge-success">VIP</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit Client">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete Client">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="d-none d-sm-table-cell text-center">
                                <span class="badge badge-pill badge-primary">7</span>
                            </td>
                            <td class="text-center">
                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar10.jpg" alt="">
                            </td>
                            <td class="font-w600">
                                <a href="javascript:void(0)">Thomas Riley</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                client7@example.com
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge badge-info">Business</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit Client">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete Client">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="d-none d-sm-table-cell text-center">
                                <span class="badge badge-pill badge-primary">8</span>
                            </td>
                            <td class="text-center">
                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar5.jpg" alt="">
                            </td>
                            <td class="font-w600">
                                <a href="javascript:void(0)">Megan Fuller</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                client8@example.com
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge badge-primary">Personal</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit Client">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete Client">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="d-none d-sm-table-cell text-center">
                                <span class="badge badge-pill badge-primary">9</span>
                            </td>
                            <td class="text-center">
                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar6.jpg" alt="">
                            </td>
                            <td class="font-w600">
                                <a href="javascript:void(0)">Helen Jacobs</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                client9@example.com
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge badge-success">VIP</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit Client">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete Client">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="d-none d-sm-table-cell text-center">
                                <span class="badge badge-pill badge-primary">10</span>
                            </td>
                            <td class="text-center">
                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar16.jpg" alt="">
                            </td>
                            <td class="font-w600">
                                <a href="javascript:void(0)">Jeffrey Shaw</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                client10@example.com
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge badge-success">VIP</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit Client">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete Client">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <nav aria-label="Users Search Navigation">
                        <ul class="pagination pagination-sm">
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)" tabindex="-1" aria-label="Previous">
                                    Prev
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0)">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)" aria-label="Next">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- END Users -->

                <!-- Classic -->
                <div class="tab-pane fade fade-up" id="search-classic" role="tabpanel">
                    <div class="font-size-h4 font-w600 p-2 mb-4 border-left border-4x border-primary bg-body-light">
                        <span class="text-primary font-w700">280</span> websites found for <mark class="text-danger">OneUI</mark>
                    </div>
                    <div class="row py-3">
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="h5 mb-1">
                                <a href="javascript:void(0)">OneUI - Bootstrap 4 UI Framework</a>
                            </h4>
                            <div class="font-size-sm text-success mb-1">https://pixelcave.com/</div>
                            <p class="font-size-sm text-muted">Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit.</p>
                        </div>
                    </div>
                    <nav aria-label="Classic Search Navigation">
                        <ul class="pagination pagination-sm">
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)" tabindex="-1" aria-label="Previous">
                                    Prev
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0)">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)" aria-label="Next">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- END Classic -->

                <!-- Photos -->
                <div class="tab-pane fade fade-up" id="search-photos" role="tabpanel">
                    <div class="font-size-h4 font-w600 p-2 mb-4 border-left border-4x border-primary bg-body-light">
                        <span class="text-primary font-w700">85</span> photos found for <mark class="text-danger">wallpaper</mark>
                    </div>
                    <div class="row gutters-tiny push">
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo1.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo2.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo6.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo21.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo22.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo9.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo23.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo24.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo25.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo27.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo28.jpg" alt="">
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 push">
                            <img class="img-fluid" src="assets/media/photos/photo30.jpg" alt="">
                        </div>
                    </div>
                    <nav aria-label="Photos Search Navigation">
                        <ul class="pagination pagination-sm">
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)" tabindex="-1" aria-label="Previous">
                                    Prev
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0)">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)" aria-label="Next">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- END Photos -->
            </div>
        </div>
        <!-- END Results -->
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->