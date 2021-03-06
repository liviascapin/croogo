<?php
$this->extend('Croogo/Core./Common/admin_edit');
$this->Html
    ->addCrumb(__d('croogo', 'Users'), ['plugin' => 'Croogo/Users', 'controller' => 'Users', 'action' => 'index'])
    ->addCrumb(__d('croogo', 'Roles'), ['plugin' => 'Croogo/Users', 'controller' => 'Roles', 'action' => 'index']);

if ($this->request->param('action') == 'edit') {
    $this->Html->addCrumb($role->title);
}

if ($this->request->param('action') == 'add') {
    $this->Html->addCrumb(__d('croogo', 'Add'));
}

$this->assign('form-start', $this->Form->create($role));

$this->start('tab-heading');
echo $this->Croogo->adminTab(__d('croogo', 'Role'), '#role-main');
$this->end();

$this->start('tab-content');
echo $this->Html->tabStart('role-main');
echo $this->Form->input('title', [
    'label' => __d('croogo', 'Title'),
    'data-slug' => '#alias'
]);
echo $this->Form->input('alias', [
    'label' => __d('croogo', 'Alias'),
]);
echo $this->Html->tabEnd();
$this->end();

$this->assign('form-end', $this->Form->end());
