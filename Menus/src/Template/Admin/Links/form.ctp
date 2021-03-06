<?php

use Croogo\Core\Status;

$this->extend('Croogo/Core./Common/admin_edit');
$this->Croogo->adminScript('Croogo/Menus.admin');

$this->Html->addCrumb(__d('croogo', 'Menus'), ['controller' => 'Menus', 'action' => 'index']);

if ($this->request->params['action'] == 'add') {
    $this->Html->addCrumb($menu->title, [
                'action' => 'index',
                '?' => ['menu_id' => $menu->id],
            ])
        ->addCrumb(__d('croogo', 'Add'));
    $formUrl = [
        'action' => 'add',
        $menu->id,
    ];
}

if ($this->request->params['action'] == 'edit') {
    $this->Html->addCrumb($menu->title, [
            'action' => 'index',
            '?' => ['menu_id' => $menu->id],
        ])
        ->addCrumb($link->title);
    $formUrl = [
        'action' => 'edit',
        '?' => [
            'menu_id' => $menu->id,
        ],
    ];
}

$this->append('form-start', $this->Form->create($link, [
    'url' => $formUrl,
    'class' => 'protected-form',
]));

$inputDefaults = $this->Form->templates();
$inputClass = isset($inputDefaults['class']) ? $inputDefaults['class'] : null;

$this->append('tab-heading');
echo $this->Croogo->adminTab(__d('croogo', 'Link'), '#link-basic');
echo $this->Croogo->adminTab(__d('croogo', 'Misc.'), '#link-misc');
$this->end();

$this->append('tab-content');

echo $this->Html->tabStart('link-basic');
echo $this->Form->input('menu_id', [
    'selected' => $menu->id,
    'class' => 'c-select'
]);
echo $this->Form->input('parent_id', [
    'title' => __d('croogo', 'Parent'),
    'options' => $parentLinks,
    'empty' => __d('croogo', '(no parent)'),
    'class' => 'c-select'
]);
echo $this->Form->input('title', [
    'label' => __d('croogo', 'Title'),
]);

echo $this->Form->input('link', [
    'label' => __d('croogo', 'Link'),
    'linkChooser' => true,
]);
echo $this->Html->tabEnd();

echo $this->Html->tabStart('link-misc');
echo $this->Form->input('description', [
    'label' => __d('croogo', 'Description'),
]);
echo $this->Form->input('class', [
    'label' => __d('croogo', 'Class'),
]);
echo $this->Form->input('rel', [
    'label' => __d('croogo', 'Rel'),
]);
echo $this->Form->input('target', [
    'label' => __d('croogo', 'Target'),
]);
echo $this->Form->input('params', [
    'label' => __d('croogo', 'Params'),
]);
echo $this->Html->tabEnd();

echo $this->Croogo->adminTabs();

$this->end();

$this->start('panels');
echo $this->Html->beginBox(__d('croogo', 'Publishing'));
echo $this->element('Croogo/Core.admin/buttons', ['type' => 'link']);
echo $this->element('Croogo/Core.admin/publishable');
echo $this->Html->endBox();

echo $this->Html->beginBox(__d('croogo', 'Access control'));
echo $this->Form->input('visibility_roles', [
    'class' => 'c-select',
    'options' => $roles,
    'multiple' => true,
    'label' => false,
]);
echo $this->Html->endBox();
$this->end();

$this->append('form-end', $this->Form->end());
