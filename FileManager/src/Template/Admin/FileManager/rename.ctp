<?php

$this->extend('Croogo/Core./Common/admin_edit');

$this->Html->addCrumb(__d('croogo', 'File Manager'),
    ['plugin' => 'Croogo/FileManager', 'controller' => 'fileManager', 'action' => 'browse'])
    ->addCrumb(__d('croogo', 'Rename'));

$this->start('page-heading');
echo $this->element('Croogo/FileManager.admin/breadcrumbs');
$this->end();

$this->append('form-start', $this->Form->create(null));

$this->append('tab-heading');
echo $this->Croogo->adminTab(__d('croogo', 'File'), '#filemanager-rename');
$this->end();

$this->append('tab-content');
echo $this->Html->tabStart('filemanager-rename');
echo $this->Form->input('name', [
    'type' => 'text',
    'label' => __d('croogo', 'New name'),
]);
echo $this->Html->tabEnd();

echo $this->Croogo->adminTabs();
$this->end();

$this->append('panels');
echo $this->Html->beginBox(__d('croogo', 'Publishing'));
echo $this->element('Croogo/Core.admin/buttons', ['saveText' => __d('croogo', 'Rename file')]);
echo $this->Html->endBox();
$this->end();

$this->append('form-end', $this->Form->end());
