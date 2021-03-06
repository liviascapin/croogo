<?php

$this->assign('title', __d('croogo', 'Vocabulary: %s', $vocabulary->title));

$this->extend('Croogo/Core./Common/admin_index');

$this->Html->addCrumb(__d('croogo', 'Content'),
        ['plugin' => 'Croogo/Nodes', 'controller' => 'Nodes', 'action' => 'index'])
    ->addCrumb(__d('croogo', 'Vocabularies'),
        ['plugin' => 'Croogo/Taxonomy', 'controller' => 'Vocabularies', 'action' => 'index'])
    ->addCrumb($vocabulary->title);

$this->append('actions');
echo $this->Croogo->adminAction(__d('croogo', 'Create term'), ['action' => 'add', $vocabulary->id], ['class' => 'btn btn-success']);
$this->end();

$this->start('table-heading');
$tableHeaders = $this->Html->tableHeaders([
    __d('croogo', 'Title'),
    __d('croogo', 'Slug'),
    __d('croogo', 'Actions'),
]);
echo $this->Html->tag('thead', $tableHeaders);
$this->end();

$this->append('table-body');
$rows = [];

foreach ($terms as $term):
    $actions = [];
    $actions[] = $this->Croogo->adminRowActions($term->id);
    $actions[] = $this->Croogo->adminRowAction('', ['action' => 'moveup', $term->id, $vocabulary->id],
        ['icon' => $this->Theme->getIcon('move-up'), 'tooltip' => __d('croogo', 'Move up')]);
    $actions[] = $this->Croogo->adminRowAction('', ['action' => 'movedown', $term->id, $vocabulary->id],
        ['icon' => $this->Theme->getIcon('move-down'), 'tooltip' => __d('croogo', 'Move down')]);
    $actions[] = $this->Croogo->adminRowAction('', ['action' => 'edit', $term->id, $vocabulary->id],
        ['icon' => $this->Theme->getIcon('update'), 'tooltip' => __d('croogo', 'Edit this item')]);
    $actions[] = $this->Croogo->adminRowAction('', ['action' => 'delete', $term->id, $vocabulary->id],
        ['icon' => $this->Theme->getIcon('delete'), 'tooltip' => __d('croogo', 'Remove this item')],
        __d('croogo', 'Are you sure?'));
    $actions = $this->Html->div('item-actions', implode(' ', $actions));

    // Title Column
    $titleCol = $term->title;
    if (isset($defaultType['alias'])) {
        $titleCol = $this->Html->link($term->title, [
            'prefix' => false,
            'plugin' => 'Croogo/Nodes',
            'controller' => 'Nodes',
            'action' => 'term',
            'type' => $defaultType->alias,
            'slug' => $term->slug,
        ], [
            'target' => '_blank',
        ]);
    }

    if (!empty($term['Term']['indent'])):
        $titleCol = str_repeat('&emsp;', $term['Term']['indent']) . $titleCol;
    endif;

    // Build link list
    $typeLinks = $this->Taxonomies->generateTypeLinks($vocabulary->types, $term);
    if (!empty($typeLinks)) {
        $titleCol .= $this->Html->tag('small', $typeLinks);
    }

    $rows[] = [
        $titleCol,
        $term->slug,
        $actions,
    ];
endforeach;
echo $this->Html->tableCells($rows);
$this->end();
