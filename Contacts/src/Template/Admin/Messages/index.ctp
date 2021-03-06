<?php

$this->Croogo->adminScript('Croogo/Contacts.admin');

$this->extend('Croogo/Core./Common/admin_index');

$this->Html->addCrumb(__d('croogo', 'Contacts'), ['controller' => 'contacts', 'action' => 'index']);

if (isset($criteria['Message.status'])) {
    $this->Html->addCrumb(__d('croogo', 'Messages'), ['action' => 'index']);
    if ($criteria['Message.status'] == '1') {
        $this->Html->addCrumb(__d('croogo', 'Read'));
        $this->assign('title', __d('croogo', 'Messages: Read'));
    } else {
        $this->Html->addCrumb(__d('croogo', 'Unread'));
        $this->assign('title', __d('croogo', 'Messages: Unread'));
    }
} else {
    $this->Html->addCrumb(__d('croogo', 'Messages'));
}

$this->append('table-footer', $this->element('admin/modal', [
        'id' => 'comment-modal',
    ]));

$this->append('actions');
echo $this->Croogo->adminAction(__d('croogo', 'Unread'), [
    'action' => 'index',
    '?' => [
        'status' => '0',
    ],
]);
echo $this->Croogo->adminAction(__d('croogo', 'Read'), [
    'action' => 'index',
    '?' => [
        'status' => '1',
    ],
]);
$this->end();

$this->append('form-start', $this->Form->create(null, [
    'url' => ['action' => 'process'],
    'align' => 'inline',
]));

$this->start('table-heading');
$tableHeaders = $this->Html->tableHeaders([
    $this->Form->checkbox('checkAll'),
    $this->Paginator->sort('contact_id', __d('croogo', 'Contact')),
    $this->Paginator->sort('name', __d('croogo', 'Name')),
    $this->Paginator->sort('email', __d('croogo', 'Email')),
    $this->Paginator->sort('title', __d('croogo', 'Title')),
    __d('croogo', 'Actions'),
]);
echo $this->Html->tag('thead', $tableHeaders);
$this->end();

$this->append('table-body');
$commentIcon = $this->Html->icon($this->Theme->getIcon('comment'));
$rows = [];
foreach ($messages as $message) {
    $actions = [];

    $actions[] = $this->Croogo->adminRowAction('', ['action' => 'edit', $message->id],
        ['icon' => $this->Theme->getIcon('update'), 'tooltip' => __d('croogo', 'Edit this item')]);
    $actions[] = $this->Croogo->adminRowAction('', '#Message' . $message->id . 'Id', [
        'icon' => $this->Theme->getIcon('delete'),
        'class' => 'delete',
        'tooltip' => __d('croogo', 'Remove this item'),
        'rowAction' => 'delete',
    ], __d('croogo', 'Are you sure?'));
    $actions[] = $this->Croogo->adminRowActions($message->id);

    $actions = $this->Html->div('item-actions', implode(' ', $actions));

    $rows[] = [
        $this->Form->checkbox('Message.' . $message->id . '.id', ['class' => 'row-select']),
        $message->contact->title,
        $message->name,
        $message->email,
        $commentIcon . ' ' . $this->Html->link($message->title, '#', [
            'class' => 'comment-view',
            'data-target' => '#comment-modal',
            'data-title' => $message->title,
            'data-content' => $message->body,
        ]),
        $actions,
    ];
}
echo $this->Html->tableCells($rows);
$this->end();

$this->start('bulk-action');
echo $this->Form->input('action', [
    'label' => __d('croogo', 'Bulk action'),
    'class' => 'c-select',
    'options' => [
        'read' => __d('croogo', 'Mark as read'),
        'unread' => __d('croogo', 'Mark as unread'),
        'delete' => __d('croogo', 'Delete'),
    ],
    'empty' => __d('croogo', 'Bulk action'),
]);
echo $this->Form->button(__d('croogo', 'Apply'), [
    'type' => 'submit',
    'value' => 'submit',
    'class' => 'bulk-process btn-primary-outline',
]);
$this->end();
$this->append('form-end', $this->Form->end());
