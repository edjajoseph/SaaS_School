@extends('layouts.app_back')

@section('content')

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tableau de bord du CFA</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">CFA</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- product profit start -->
            <div class="col-xl-3 col-md-6">
                <div class="card prod-p-card bg-c-red">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Anné en cours</h6>
                                <h3 class="m-b-0 text-white">$1,783</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-alt text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card prod-p-card bg-c-blue">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Etat de la caisse</h6>
                                <h3 class="m-b-0 text-white">15,830</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-database text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card prod-p-card bg-c-green">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Etat de la banque</h6>
                                <h3 class="m-b-0 text-white">$6,780</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card prod-p-card bg-c-yellow">
                    <div class="card-body">
                        <div class="row align-items-center m-b-0">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Etat des charges</h6>
                                <h3 class="m-b-0 text-white">6,784</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tags text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- product profit end -->

            <!-- account-section start -->
            <div class="col-xl-7">
                <div class="card">
                    <div class="card-header">
                        <h5>Department wise annual recurring and profit</h5>
                    </div>
                    <div class="card-body">
                        <div id="account-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="card feed-card">
                    <div class="card-header">
                        <h5>Feeds</h5>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal"></i>
                                </button>
                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                    <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                    <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="feed-scroll" style="height:385px;position:relative;">
                        <div class="card-body">
                            <div class="row m-b-25 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="feather icon-bell badge-light-primary feed-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6 class="m-b-5">You have 3 pending tasks. <span class="text-muted float-right f-14">Just Now</span></h6>
                                    </a>
                                </div>
                            </div>
                            <div class="row m-b-25 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="feather icon-shopping-cart badge-light-danger feed-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6 class="m-b-5">New order received <span class="text-muted float-right f-14">30 min ago</span></h6>
                                    </a>
                                </div>
                            </div>
                            <div class="row m-b-25 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="feather icon-file-text badge-light-success feed-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6 class="m-b-5">You have 3 pending tasks. <span class="text-muted float-right f-14">Just Now</span></h6>
                                    </a>
                                </div>
                            </div>
                            <div class="row m-b-25 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="feather icon-bell badge-light-primary feed-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6 class="m-b-5">You have 4 tasks Done. <span class="text-muted float-right f-14">1 hours ago</span></h6>
                                    </a>
                                </div>
                            </div>
                            <div class="row m-b-25 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="feather icon-file-text badge-light-success feed-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6 class="m-b-5">You have 2 pending tasks. <span class="text-muted float-right f-14">Just Now</span></h6>
                                    </a>
                                </div>
                            </div>
                            <div class="row m-b-25 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="feather icon-shopping-cart badge-light-danger feed-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6 class="m-b-5">New order received <span class="text-muted float-right f-14">4 hours ago</span></h6>
                                    </a>
                                </div>
                            </div>
                            <div class="row m-b-25 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="feather icon-shopping-cart badge-light-danger feed-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6 class="m-b-5">New order Done <span class="text-muted float-right f-14">Just Now</span></h6>
                                    </a>
                                </div>
                            </div>
                            <div class="row m-b-25 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="feather icon-file-text badge-light-success feed-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6 class="m-b-5">You have 5 pending tasks. <span class="text-muted float-right f-14">5 hours ago</span></h6>
                                    </a>
                                </div>
                            </div>
                            <div class="row m-b-0 align-items-center">
                                <div class="col-auto p-r-0">
                                    <i class="feather icon-bell badge-light-primary feed-icon"></i>
                                </div>
                                <div class="col">
                                    <a href="#!">
                                        <h6 class="m-b-5">You have 4 tasks Done. <span class="text-muted float-right f-14">2 hours ago</span></h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- account-section end -->
            <div class="col-xl-6 col-md-12">
                <div class="card table-card">
                    <div class="card-header">
                        <h5>New Products</h5>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal"></i>
                                </button>
                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                    <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                    <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pro-scroll" style="height:345px;position:relative;">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover m-b-0">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>HeadPhone</td>
                                            <td><img src="assets/back/assets/images/widget/p1.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-warning">Pending</label></div>
                                            </td>
                                            <td>$10</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16  text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Iphone 6</td>
                                            <td><img src="assets/back/assets/images/widget/p2.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-danger">Cancel</label></div>
                                            </td>
                                            <td>$20</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16  text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Jacket</td>
                                            <td><img src="assets/back/assets/images/widget/p3.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-success">Success</label></div>
                                            </td>
                                            <td>$35</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16 text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Sofa</td>
                                            <td><img src="assets/back/assets/images/widget/p4.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-danger">Cancel</label></div>
                                            </td>
                                            <td>$85</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16 text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Iphone 6</td>
                                            <td><img src="assets/back/assets/images/widget/p2.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-success">Success</label></div>
                                            </td>
                                            <td>$20</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16 text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>HeadPhone</td>
                                            <td><img src="assets/back/assets/images/widget/p1.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-warning">Pending</label></div>
                                            </td>
                                            <td>$50</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16 text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Iphone 6</td>
                                            <td><img src="assets/back/assets/images/widget/p2.jpg" alt="" class="img-20"></td>
                                            <td>
                                                <div><label class="badge badge-light-danger">Cancel</label></div>
                                            </td>
                                            <td>$30</td>
                                            <td><a href="#!"><i class="icon feather icon-edit f-16 text-c-green"></i></a><a href="#!"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- customer-section start -->
            <div class="col-xl-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Customer Satisfaction</h6>
                        <span>It takes continuous effort to maintain high customer satisfaction levels.Internal and external quality measures are often tied together.,as the opinion...</span>
                        <span class="text-c-blue d-block">Learn more..</span>
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col">
                                <div id="satisfaction-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- customer-section end -->

        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
@endsection
