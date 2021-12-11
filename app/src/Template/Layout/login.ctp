<!DOCTYPE html>
<html lang="en">
    <head>
        <?= $this->Html->charset() ?>
        <title><?= $this->EasyAuditConfig->siteTitle() ?> - <?= $this->fetch('title') ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="theme-color" content="#ffffff">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        <!-- Favicon -->
        <?= $this->Html->meta('favicon.ico', '/images/favicon/favicon.ico', array('type' => 'icon')) ?>
        <!-- custom:css -->
        <?= $this->Html->css(array("/maqint/maqint", "login-page", "custom")) ?>
        <!-- libs:js -->
        <?= $this->Html->script("/libs/ckeditor/ckeditor") ?>
        <!-- custom:js -->
        <?= $this->Html->script(array("/maqint/maqint-config", "/maqint/maqint", "/maqint/support")) ?>
    </head>

    <body>
        <!-- Loader -->
        <div id="page-loader">
            <div class="dot"></div>
            <div class="dot"></div>
        </div>

        <div id="main-content" class="row" role="main"><section id="login-page" class="page no-header no-footer">
            <div class="login-bg"></div>
                <div class="page-content">
                    <div class="container">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-md-6 login-left">
                                        <div class="col-xs-7 col-md-12 row">
                                            <!-- Login logo -->
                                            <div class="text-center login-logo">
                                                <?= $this->Html->image("/images/logo/login.png", array('alt'=>$this->EasyAuditConfig->siteTitle())); ?>
                                            </div>

                                            <!-- Content -->
                                            <?= $this->Flash->render(); ?>
                                            <?= $this->fetch('content'); ?>

                                            <!-- Footer toolbar -->
                                            <div class="login-toolbar text-center col-xs-12" style="min-width:500px"></div>
                                        </div>
                                    </div>
                                    <div class="hidden-xs hidden-sm col-md-6 col-sm-8 login-right">
                                        <!-- Main logo -->
                                        <div class="col-xs-12 text-center login-right-content">
                                            <?= $this->Html->image("/images/logo/main.png", array('alt'=>$this->EasyAuditConfig->siteTitle(), 'style'=>'width:200px; margin-bottom:200px')); ?>
                                        </div>

                                        <!-- Disclaimer -->
                                        <div class="xunta-disclaimer">
                                            <div class="xunta-disclaimer-text">
                                                <p><?= __('Easy Audit system') ?></p>
                                            </div>
                                            <div class="version">
                                                Ver. <span><?= $this->EasyAuditConfig->version() ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>