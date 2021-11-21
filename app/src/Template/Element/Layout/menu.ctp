<ul class="nav nav-pills nav-stacked" id="left-menu-content" role="navigation">
    <li data-toggle="tooltip">
        <?= $this->EasyAuditHtml->gliphiconLink('home', __('Home'), ['controller'=>'Main', 'action'=>'index']) ?>
    </li>

    <li data-toggle="tooltip">
        <a href="#" data-toggle="collapse" data-target="#config-entries" data-parent="#left-menu-content" <?=$menu_option==='config'?'aria-expanded="true"':''?>>
            <?= $this->EasyAuditHtml->gliphiconText('cog', __('Config')) ?>    
            <span class="caret caret-right"></span>
        </a>
        <ul id="config-entries" class="nav nav-pills nav-stacked left-submenu collapse <?=$menu_option==='config'?'in':''?>"  <?=$menu_option==='config'?'aria-expanded="true"':''?>>
            <li <?=$submenu_option==='config-form-templates'?'class="active"':''?>>
                <?= $this->Html->link('Form templates', ['controller'=>'FormTemplates', 'action'=>'index']) ?>
            </li>
        </ul>
    </li>
</ul>