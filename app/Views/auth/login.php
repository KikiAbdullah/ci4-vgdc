<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 4.5.4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>GDC Kiosk Dashboard | Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/template/assets/global/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/template/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/template/assets/global/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/template/assets/global/plugins/uniform/css/uniform.default.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/template/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="<?= base_url('assets/template/assets/global/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/template/assets/global/plugins/select2/css/select2-bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?= base_url('assets/template/assets/global/css/components.min.css') ?>" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?= base_url('assets/template/assets/global/css/plugins.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="<?= base_url('assets/template/assets/pages/css/login-5.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="<?= base_url('assets/favicon.png') ?>" />

    <style>
        ::placeholder {
            /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: #00b14f !important;
            opacity: 1;
            /* Firefox */
            font-size: 18px;
        }

        :-ms-input-placeholder {
            /* Internet Explorer 10-11 */
            color: #00b14f !important;
        }

        ::-ms-input-placeholder {
            /* Microsoft Edge */
            color: #00b14f !important;
        }

        .user-login-5 .login-container>.login-content>.login-form input {
            width: 100%;
            padding: 10px;
            border: none #00b14f;
            color: #d9fcde;
            font-size: 18px;
            margin-bottom: 30px;
            border-bottom: #d3f035 1px solid;
            background-color: #00b14f;

        }
    </style>
</head>
<!-- END HEAD -->

<body class=" login" style="background-color: #00b14f;">
    <!-- BEGIN : LOGIN PAGE 5-1 -->
    <div class="user-login-5">
        <div class="row bs-reset">
            <div class="col-md-6 bs-reset">
                <div class="login-bg" style="background-image:url(<?= base_url('assets/logo.png') ?>)">
                    <img class="login-logo" src="<?= base_url('assets/logo.png') ?>" style="width:100px;" />
                </div>
            </div>
            <div class="col-md-6 login-container bs-reset">
                <div class="login-content">
                    <br>
                    <h1 style="color:#d3f035;font-weight: 900;">GDC Kiosk | Dashboard</h1>
                    <p style="color:#d9fcde;"> Silahkan masukkan <b>Username/Email</b> beserta <b>Password</b> anda. </p>
                    <form action="<?= site_url('login/proses') ?>" class="login-form" method="post">

                        <?php if (session()->getFlashdata('msg')) : ?>
                            <div class="alert alert-danger">
                                <button class="close" data-close="alert"></button>
                                <span><?= session()->getFlashdata('msg') ?></span>
                                <!-- <span><?= session()->getFlashdata('msg') ?></span> -->
                            </div>
                        <?php endif ?>

                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <div class="row">
                            <div class="col-xs-6">
                                <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Username/Email" name="username" required />
                            </div>
                            <div class="col-xs-6">
                                <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="password" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                            </div>
                            <div class="col-sm-8 text-right">
                                <button class="btn" type="submit" style="color: #009b3d;background-color: #cddb3d; width:100px;"><b>Login</b></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="login-footer">
                    <div class="row bs-reset">
                        <div class="col-xs-5 bs-reset">

                        </div>
                        <div class="col-xs-7 bs-reset">
                            <div class="login-copyright text-right">
                                <p style="color:white;">Copyright &copy; Nakula Sadewa, CV | 2020</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END : LOGIN PAGE 5-1 -->
    <!--[if lt IE 9]>
<script src="<?= base_url('assets/template/assets/global/plugins/respond.min.js') ?>"></script>
<script src="<?= base_url('assets/template/assets/global/plugins/excanvas.min.js') ?>"></script> 
<![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="<?= base_url('assets/template/assets/global/plugins/jquery.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/template/assets/global/plugins/bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/template/assets/global/plugins/js.cookie.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/template/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/template/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/template/assets/global/plugins/jquery.blockui.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/template/assets/global/plugins/uniform/jquery.uniform.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/template/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="<?= base_url('assets/template/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/template/assets/global/plugins/jquery-validation/js/additional-methods.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/template/assets/global/plugins/select2/js/select2.full.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/template/assets/global/plugins/backstretch/jquery.backstretch.min.js') ?>" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="<?= base_url('assets/template/assets/global/scripts/app.min.js') ?>" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?= base_url('assets/template/assets/pages/scripts/login-5.min.js') ?>" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <!-- END THEME LAYOUT SCRIPTS -->
    <script type="text/javascript">
        $('.login-bg').backstretch([
            "<?= base_url('assets/bg/1.jpg') ?>",
            "<?= base_url('assets/bg/2.jpg') ?>",
            "<?= base_url('assets/bg/3.jpg') ?>",
            "<?= base_url('assets/bg/4.jpg') ?>"
        ], {
            fade: 1000,
            duration: 5000
        });
    </script>
</body>

</html>