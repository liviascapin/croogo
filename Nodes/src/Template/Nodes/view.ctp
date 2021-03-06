<?php
use Cake\Core\Plugin;

$this->Nodes->set($node);
?>
<div id="node-<?php echo $this->Nodes->field('id'); ?>" class="node node-type-<?php echo $this->Nodes->field('type'); ?>">
	<h2><?php echo $this->Nodes->field('title'); ?></h2>
	<?php
		echo $this->Nodes->info();
		echo $this->Nodes->body();
		echo $this->Nodes->moreInfo();
	?>
</div>

<?php if (Plugin::loaded('Croogo/Comments')): ?>
<div id="comments" class="node-comments">
<?php
	$type = $typesForLayout[$this->Nodes->field('type')];

	if ($type->comment_status > 0 && $this->Nodes->field('comment_status') > 0) {
        echo $this->cell('Croogo/Comments.Comments::node', [$node->id]);
	}

	if ($type->comment_status == 2 && $this->Nodes->field('comment_status') == 2) {
        echo $this->cell('Croogo/Comments.Comments::commentFormNode', [
            'mode' => $node,
            'type' => $type
        ]);
	}
?>
</div>
<?php endif; ?>
