<?php

$this->extend('Croogo/Core./Common/admin_edit');

$this->Html->addCrumb(__d('croogo', 'Settings'),
    ['plugin' => 'Croogo/Settings', 'controller' => 'settings', 'action' => 'prefix', 'Site'])
    ->addCrumb(__d('croogo', 'Language'),
        ['plugin' => 'Croogo/Settings', 'controller' => 'languages', 'action' => 'index']);

if ($this->request->params['action'] == 'admin_edit') {
    $this->Html->addCrumb($language->title);
}

if ($this->request->params['action'] == 'admin_add') {
    $this->Html->addCrumb(__d('croogo', 'Add'));
}

$this->append('form-start', $this->Form->create($language));

$this->start('tab-heading');
echo $this->Croogo->adminTab(__d('croogo', 'Language'), '#language-main');
$this->end();

$this->start('tab-content');
echo $this->Html->tabStart('language-main');
echo $this->Form->input('title', [
    'label' => __d('croogo', 'Title'),
]);
echo $this->Form->input('native', [
    'label' => __d('croogo', 'Native'),
]);
echo $this->Form->input('alias', [
    'label' => __d('croogo', 'Alias'),
]);
echo $this->Html->tabEnd();
$this->end();

$this->start('panels');
echo $this->Html->beginBox(__d('croogo', 'Publishing'));
echo $this->element('Croogo/Core.admin/buttons', ['type' => 'language']);
echo $this->Form->input('status', [
    'label' => __d('croogo', 'Status'),
]);
echo $this->Html->endBox();
$this->end();
$this->append('form-end', $this->Form->end());
