<?php

namespace Croogo\Core\Controller\Admin;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Event\Event;
use Croogo\Core\Croogo;
use Croogo\Core\Controller\AppController as CroogoAppController;
use Crud\Controller\ControllerTrait;

/**
 * Croogo App Controller
 *
 * @category Croogo.Controller
 * @package  Croogo.Croogo.Controller
 * @version  1.5
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 *
 * @property \Crud\Controller\Component\CrudComponent $Crud
 */
class AppController extends CroogoAppController
{
    use ControllerTrait;

/**
 * Load the theme component with the admin theme specified
 *
 * @return void
 */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'index' => [
                    'className' => 'Croogo/Core.Admin/Index'
                ],
                'lookup' => [
                    'className' => 'Crud.Lookup',
                    'findMethod' => 'all'
                ],
                'view' => [
                    'className' => 'Crud.View'
                ],
                'add' => [
                    'className' => 'Croogo/Core.Admin/Add',
                    'messages' => [
                        'success' => [
                            'params' => [
                                'type' => 'success',
                                'class' => ''
                            ]
                        ],
                        'error' => [
                            'params' => [
                                'type' => 'error',
                                'class' => ''
                            ]
                        ]
                    ]
                ],
                'edit' => [
                    'className' => 'Croogo/Core.Admin/Edit',
                    'messages' => [
                        'success' => [
                            'params' => [
                                'type' => 'success',
                                'class' => ''
                            ]
                        ],
                        'error' => [
                            'params' => [
                                'type' => 'error',
                                'class' => ''
                            ]
                        ]
                    ]
                ],
                'toggle' => [
                    'className' => 'Croogo/Core.Admin/Toggle'
                ],
                'delete' => [
                    'className' => 'Crud.Delete'
                ]
            ],
            'listeners' => [
                'Crud.Search',
                'Crud.RelatedModels'
            ]
        ]);

        $this->Theme->config('theme', Configure::read('Site.admin_theme'));
    }

/**
 * beforeFilter
 *
 * @return void
 */
    public function beforeFilter(Event $event)
    {
        $this->viewBuilder()->layout('Croogo/Core.admin');

        parent::beforeFilter($event);

        if (Configure::read('Site.status') == 0 &&
            $this->Auth->user('role_id') != 1
        ) {
            if (!$this->request->is('whitelisted')) {
                $this->viewBuilder()->layout('Croogo/Core.maintenance');
                $this->response->statusCode(503);
                $this->set('title_for_layout', __d('croogo', 'Site down for maintenance'));
                $this->viewBuilder()->templatePath('Maintenance');
                $this->render('Croogo/Core.blank');
            }
        }

        Croogo::dispatchEvent('Croogo.beforeSetupAdminData', $this);
    }

    public function index()
    {
        return $this->Crud->execute();
    }

    public function view($id)
    {
        return $this->Crud->execute();
    }

    public function add()
    {
        return $this->Crud->execute();
    }

    public function edit($id)
    {
        return $this->Crud->execute();
    }

    public function delete($id)
    {
        return $this->Crud->execute();
    }
}
