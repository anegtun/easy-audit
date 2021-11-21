<?php $this->assign('title', __('Dashboard')); ?>

<div class="container-full gray-bg">
    <div class="row page-header">
        <div class="col-xs-12 m-b-15">
            <h1><?= __('Dashboard') ?></h1>
            <ol class="breadcrumb">
                <li>
                    <?= $this->Html->link(
                        '<i class="glyphicon glyphicon-home"><span class="sr-only">Dashboard</span></i>',
                        array('controller'=>'Main', 'action'=>'index'),
                        array('escape'=>false)) ?>    
                </li>
            </ol>
        </div>
    </div>
</div>



<div class="container-full" style="margin-top:2em;">
    <div class="row">
        <p><?= __('Dashboard') ?></p>
    </div>
</div>