<?php
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

if (empty($modelClass)) {
    $modelClass = $this->name;
}
if (!empty($searchFields)):
    echo $this->Form->create(null, [
        'align' => 'inline',
        'novalidate' => true,
        'url' => [
            'plugin' => $this->request->params['plugin'],
            'controller' => $this->request->params['controller'],
            'action' => $this->request->params['action'],
        ],
    ]);
    $this->Form->templates([
        'label' => false,
        'submitContainer' => '{{content}}',
    ]);
    if (isset($this->request->query['chooser'])):
        echo $this->Form->input('chooser', [
            'type' => 'hidden',
            'value' => isset($this->request->query['chooser']),
        ]);
    endif;
    foreach ($searchFields as $field => $fieldOptions) {
        $options = ['empty' => true, 'required' => false];
        if (is_numeric($field) && is_string($fieldOptions)) {
            $field = $fieldOptions;
            $fieldOptions = [];
        }
        if (!empty($fieldOptions)) {
            $options = Hash::merge($fieldOptions, $options);
        }
        $label = $field;
        if (substr($label, -3) === '_id') {
            $label = substr($label, 0, -3);
        }
        $label = __(Inflector::humanize(Inflector::underscore($label)));
        $options['label'] = __d('croogo', $label);
        $this->Form->unlockField($field);
        echo $this->Form->input($field, $options);
    }

    echo $this->Form->submit(__d('croogo', 'Filter'), ['type' => 'submit', 'class' => 'btn-success-outline']);
    echo $this->Form->end();
endif;
