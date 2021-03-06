<?php

namespace Croogo\Menus\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Croogo\Core\Model\Table\CroogoTable;

/**
 * Link
 *
 * @category Model
 * @package  Croogo.Menus.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class LinksTable extends CroogoTable
{

    /**
     * Validation
     *
     * @var array
     * @access public
     */
    public $validate = [
        'title' => [
            'rule' => ['minLength', 1],
            'message' => 'Title cannot be empty.',
        ],
        'link' => [
            'rule' => ['minLength', 1],
            'message' => 'Link cannot be empty.',
        ],
    ];

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->addBehavior('Tree');
        $this->addBehavior('Croogo/Core.Cached', [
            'groups' => ['menus']
        ]);
        $this->belongsTo('Menus', [
            'className' => 'Croogo/Menus.Menus',
        ]);
        $this->addBehavior('CounterCache', [
            'Menus' => ['link_count'],
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'updated' => 'always',
                ],
            ],
        ]);

        $this->addBehavior('Croogo/Core.Publishable');
        $this->addBehavior('Croogo/Core.Visibility');
        $this->addBehavior('Search.Search');

        $this->searchManager()
            ->add('menu_id', 'Search.Value', [
                'field' => 'menu_id'
            ]);
    }

    protected function _initializeSchema(Schema $table)
    {
        $table->columnType('visibility_roles', 'encoded');
        $table->columnType('link', 'link');

        return parent::_initializeSchema($table);
    }

    /**
     * Allow to change Tree scope to a specific menu
     *
     * @param int $menuId menu id
     * @return void
     */
    public function setTreeScope($menuId)
    {
        $settings = [
            'scope' => ['menu_id' => $menuId],
        ];
        if ($this->hasBehavior('Tree')) {
            $this->behaviors()
                ->get('Tree')
                ->config($settings);
        } else {
            $this->addBehavior('Tree', $settings);
        }
    }

    /**
     * Calls TreeBehavior::recover when we are changing scope
     */
    public function afterSave(Event $event, Entity $entity, $options = [])
    {
        if ($entity->isNew()) {
            return;
        }
        if ($entity->dirty('menu_id')) {
            $this->setTreeScope($entity->menu_id);
            $this->recover();
            $this->setTreeScope($entity->getOriginal('menu_id'));
            $this->recover();
        }
    }
}
