<?php
$imgs = empty($field_images[$template->id][$field->id]) ? [] : $field_images[$template->id][$field->id];
?>

<div class="audit-field-photos">
    <div class="audit-img-display" data-template-id="<?= $template->id ?>" data-field-id="<?= $field->id ?>">
        <?php foreach($imgs as $img) : ?>
            <?= $this->Html->image("/$img") ?>
        <?php endforeach ?>
    </div>
    <div class="audit-img-input">
        <?php $fieldId = "field_photo_{$field->id}"; ?>
        <input
            id="<?=$fieldId?>"
            type="file"
            name="field_photo"
            data-post-url="<?= $this->Url->build(['action'=>'addPhoto', $audit->id, $template->id, $field->id]) ?>"
            data-target-name="<?="field_photo[{$template->id}][{$field->id}][]"?>"
            accept="image/*"
            capture="capture"
        />
        <label for="<?=$fieldId?>">
            <?= $this->EasyAuditHtml->gliphiconText('camera', __('Take photo')) ?>
        </label>
    </div>
    <div class="audit-img-aux-container" style="display:none">
        <span class="audit-img-loader">
            <?= $this->Html->image('/images/components/loading-dots.gif') ?>
        </span>
        <span class="audit-img-photo-error">
            <?= $this->Html->image('/images/components/photo-error.png', [
                'data-post-url' => $this->Url->build(['action'=>'addPhoto', $audit->id, $template->id, $field->id])
            ]) ?>
        </span>
    </div>
</div>