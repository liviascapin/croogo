<?php
$this->Html->script('Croogo/Users.admin', ['block' => true]);

$this->extend('Croogo/Core./Common/admin_edit');

$this->Html->addCrumb(__d('croogo', 'Users'),
        ['plugin' => 'Croogo/Users', 'controller' => 'Users', 'action' => 'index']);

if ($this->request->param('action') == 'edit') {
    $this->Html->addCrumb($user->name);
    $this->assign('title', __d('croogo', 'Edit user %s', $user->username));
} else {
    $this->assign('title', __d('croogo', 'New user'));
    $this->Html->addCrumb(__d('croogo', 'New user'));
}

$this->start('actions');
if ($this->request->param('action') == 'edit'):
    echo $this->Croogo->adminAction(__d('croogo', 'Reset password'), ['action' => 'reset_password', $user->id]);
endif;
$this->end();

$this->append('form-start', $this->Form->create($user, [
    'fieldAccess' => [
        'User.role_id' => 1,
    ],
    'class' => 'protected-form',
]));

$this->append('tab-heading');
echo $this->Croogo->adminTab(__d('croogo', 'User'), '#user-main');
$this->end();

$this->append('tab-content');

echo $this->Html->tabStart('user-main');
echo $this->Form->input('username', [
    'label' => __d('croogo', 'Username'),
]);
echo $this->Form->input('name', [
    'label' => __d('croogo', 'Name'),
]);
echo $this->Form->input('email', [
    'label' => __d('croogo', 'Email'),
]);
echo $this->Form->input('website', [
    'label' => __d('croogo', 'Website'),
]);
echo $this->Form->input('timezone', [
    'type' => 'select',
    'empty' => true,
    'options' => DateTimeZone::listIdentifiers(),
    'label' => __d('croogo', 'Timezone'),
    'class' => 'c-select',
]);
echo $this->Form->input('role_id', ['label' => __d('croogo', 'Role'), 'class' => 'c-select']);
echo $this->Html->tabEnd();
$this->end();

$this->append('panels');
echo $this->Html->beginBox(__d('croogo', 'Publishing'));
echo $this->element('Croogo/Core.admin/buttons', ['type' => 'user']);

if ($this->request->param('action') == 'add'):
    echo $this->Form->input('notification', [
        'label' => __d('croogo', 'Send Activation Email'),
        'type' => 'checkbox',
        'class' => false,
    ]);
endif;

echo $this->Form->input('status', [
    'label' => __d('croogo', 'Active'),
]);

$showPassword = !empty($user->status);
if ($this->request->param('action') == 'add'):
    $out = $this->Form->input('password', [
        'label' => __d('croogo', 'Password'),
        'disabled' => !$showPassword,
    ]);
    $out .= $this->Form->input('verify_password', [
        'label' => __d('croogo', 'Verify Password'),
        'disabled' => !$showPassword,
        'type' => 'password',
    ]);

    $this->Form->unlockField('password');
    $this->Form->unlockField('verify_password');

    echo $this->Html->div(null, $out, [
        'id' => 'passwords',
        'style' => $showPassword ? '' : 'display: none',
    ]);
endif;

echo $this->Html->endBox();

echo $this->Croogo->adminBoxes();
$this->end();

$this->append('form-end', $this->Form->end());
