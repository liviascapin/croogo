<?php

namespace Croogo\Comments\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\Mailer\MailerAwareTrait;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\ORM\ResultSet;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Croogo\Comments\Model\Entity\Comment;
use Croogo\Core\Model\Table\CroogoTable;
use Croogo\Core\Status;
use UnexpectedValueException;

/**
 * Comment
 *
 * @category Model
 * @package  Croogo.Comments.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class CommentsTable extends CroogoTable
{
    use MailerAwareTrait;

/**
 * @deprecated
 */
    const STATUS_APPROVED = 1;

/**
 * @deprecated
 */
    const STATUS_PENDING = 0;

/**
 * Validation
 *
 * @var array
 * @access public
 */
    public $validate = [
        'body' => [
            'rule' => 'notEmpty',
            'message' => 'This field cannot be left blank.',
        ],
        'name' => [
            'rule' => 'notEmpty',
            'message' => 'This field cannot be left blank.',
        ],
        'email' => [
            'rule' => 'email',
            'required' => true,
            'message' => 'Please enter a valid email address.',
        ],
    ];

/**
 * Model associations: belongsTo
 *
 * @var array
 * @access public
 */
    public $belongsTo = [
        'User' => [
            'className' => 'Users.User',
        ],
    ];

/**
 * Filter fields
 *
 * @var array
 */
    public $filterArgs = [
        'status' => ['type' => 'value'],
    ];

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->entityClass('Croogo/Comments.Comment');

        $this->belongsTo('Users', [
            'className' => 'Croogo/Users.Users',
            'foreignKey' => 'user_id',
        ]);
        $this->addBehavior('Croogo/Core.Publishable');
        $this->addBehavior('Croogo/Core.Trackable');
        $this->addBehavior('Croogo/Core.LinkedModel');
        $this->addBehavior('Search.Search');
        $this->addBehavior('Tree');
        $this->addBehavior('Croogo/Core.Cached', [
            'groups' => ['comments', 'nodes']
        ]);
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'updated' => 'always'
                ]
            ]
        ]);

        $this->searchManager()
            ->add('status', 'Search.Value', [
                'field' => 'status'
            ]);

        $this->eventManager()->on($this->getMailer('Croogo/Comments.Comment'));
    }

/**
 * Add a new Comment
 *
 * Options:
 * - parentId id of parent comment (if it is a reply)
 * - userData author data (User data (if logged in) / Author fields from Comment form)
 *
 * @param array $data Comment data (Usually POSTed data from Comment form)
 * @param string $model Model alias
 * @param int $foreignKey Foreign Key (Node Id from where comment was posted).
 * @param array $options Options
 * @return bool true if comment was added, false otherwise.
 * @throws NotFoundException
 */
    public function add(Comment $comment, $model, $foreignKey, $options = [])
    {
        $options = Hash::merge([
            'parentId' => null,
            'userData' => [],
        ], $options);

        $foreignKey = (int)$foreignKey;
        $parentId = is_null($options['parentId']) ? null : (int)$options['parentId'];
        $userData = $options['userData'];

        if (empty($this->{$model})) {
            throw new UnexpectedValueException(sprintf('%s not configured for Comments', $model));
        }

        $entity = $this->{$model}->findById($foreignKey)->firstOrFail();

        if (!is_null($parentId)) {
            if ($this->isValidLevel($parentId) &&
                $this->isApproved($parentId, $model, $foreignKey)
            ) {
                $comment->parent_id = $parentId;
            } else {
                return false;
            }
        }

        if (is_array($userData)) {
            $comment->user_id = $userData['User']['id'];
            $comment->name = $userData['User']['name'];
            $comment->email = $userData['User']['email'];
            $comment->website = $userData['User']['website'];
        }

        $comment->model = $model;
        $comment->foreign_key = $entity->id;

        if ($entity->has('type')) {
            $comment['type'] = $entity->type;
        }

        if ($comment->status === null) {
            $comment->status = Status::PENDING;
        }

        return (bool)$this->save($comment);
    }

/**
 * Checks wether comment has been approved
 *
 * @param int$commentIdcomment id
 * @param int$nodeIdnode id
 * @return boolean true if comment is approved
 */
    public function isApproved($commentId, $model, $foreignKey)
    {
        return $this->exists([
            $this->aliasField('id') => $commentId,
            $this->aliasField('model') => $model,
            $this->aliasField('foreign_key') => $foreignKey,
            $this->aliasField('status') => 1,
        ]);
    }

/**
 * Checks wether comment is within valid level range
 *
 * @return boolean
 * @throws NotFoundException
 */
    public function isValidLevel($commentId)
    {
        if (!$this->exists($commentId)) {
            throw new NotFoundException(__d('croogo', 'Invalid Comment id'));
        }

        $path = $this->getPath($commentId, [$this->escapeField()]);
        $level = count($path);

        return Configure::read('Comment.level') > $level;
    }

/**
 * Change status of given Comment Ids
 *
 * @param array $ids array of Comment Ids
 * @param bool
 * @return mixed
 * @see Model::saveMany()
 */
    public function changeStatus($ids, $status)
    {
        return $this->updateAll([
            'status' => $status,
        ], [
            $this->primaryKey() . ' IN' => $ids
        ]);
    }

/**
 * Provide our own bulkPublish since BulkProcessBehavior::bulkPublish is incompatible with boolean status
 */
    public function bulkPublish($ids)
    {
        return $this->changeStatus($ids, true);
    }

/**
 * Provide our own bulkUnpublish since BulkProcessBehavior::bulkUnpublish is incompatible with boolean status
 */
    public function bulkUnpublish($ids)
    {
        return $this->changeStatus($ids, false);
    }
}
