<?php
$this->extend('template');
$this->set('cabeceiraTitulo', 'Detalle');
$this->set('cabeceiraMigas', [
    ['label'=>'Configuración'],
    ['label'=>'Árbitros', 'url'=>['controller'=>'Arbitros', 'action'=>'index']],
    ['label'=>'Detalle']
]);
?>

<div class="container-full" style="margin-top:2em;">
    <div class="row">
        <?= $this->Form->create($arbitro, ['type'=>'post', 'url'=>['action'=>'gardar']]) ?>
            <?= $this->Form->hidden('id') ?>
            <fieldset>
                <legend>Árbitro</legend>
                <?= $this->Form->control('alcume', ['label'=>'Alcume']) ?>
                <?= $this->Form->control('nif', ['label'=>'NIF']) ?>
                <?= $this->Form->control('nome', ['label'=>'Nome completo']) ?>
                <?= $this->Form->button('Gardar', ['class'=>'btn btn-primary']); ?>
            </fieldset>
        <?= $this->Form->end() ?>
    </div>
</div>