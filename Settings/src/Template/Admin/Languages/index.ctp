<?php

$this->extend('Croogo/Core./Common/admin_index');

$this->Html->addCrumb(__d('croogo', 'Settings'),
    ['plugin' => 'Croogo/Settings', 'controller' => 'Settings', 'action' => 'prefix', 'Site'])
    ->addCrumb(__d('croogo', 'Languages'));

$tableHeaders = $this->Html->tableHeaders([
    $this->Paginator->sort('title', __d('croogo', 'Title')),
    $this->Paginator->sort('native', __d('croogo', 'Native')),
    $this->Paginator->sort('alias', __d('croogo', 'Alias')),
    $this->Paginator->sort('status', __d('croogo', 'Status')),
    __d('croogo', 'Actions'),
]);
$this->append('table-heading', $tableHeaders);

$rows = [];
foreach ($languages as $language) {
    $actions = [];
    $actions[] = $this->Croogo->adminRowActions($language->id);
    $actions[] = $this->Croogo->adminRowAction('', ['action' => 'moveUp', $language->id],
        ['icon' => $this->Theme->getIcon('move-up'), 'tooltip' => __d('croogo', 'Move up')]);
    $actions[] = $this->Croogo->adminRowAction('', ['action' => 'moveDown', $language->id],
        ['icon' => $this->Theme->getIcon('move-down'), 'tooltip' => __d('croogo', 'Move down')]);
    $actions[] = $this->Croogo->adminRowAction('', ['action' => 'edit', $language->id],
        ['icon' => $this->Theme->getIcon('update'), 'tooltip' => __d('croogo', 'Edit this item')]);
    $actions[] = $this->Croogo->adminRowAction('', ['action' => 'delete', $language->id],
        ['icon' => $this->Theme->getIcon('delete'), 'tooltip' => __d('croogo', 'Remove this item')],
        __d('croogo', 'Are you sure?'));

    $actions = $this->Html->div('item-actions', implode(' ', $actions));

    $rows[] = [
        $language->title,
        $language->native,
        $language->alias,
        $this->Html->status($language->status),
        $actions,
    ];
}

$this->append('table-body', $this->Html->tableCells($rows));
