<?php

namespace Croogo\Settings\Controller\Admin;

use Croogo\Core\Event\EventManager;

/**
 * Languages Controller
 *
 * @category Settings.Controller
 * @package  Croogo.Settings
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class LanguagesController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->Crud->config('actions.moveUp', [
            'className' => 'Croogo/Core.Admin/MoveUp'
        ]);
        $this->Crud->config('actions.moveDown', [
            'className' => 'Croogo/Core.Admin/MoveDown'
        ]);
    }

/**
 * Admin select
 *
 * @param int$id
 * @param string $modelAlias
 * @return void
 * @access public
 */
    public function select($id = null, $modelAlias = null)
    {
        if ($id == null ||
            $modelAlias == null) {
            return $this->redirect(['action' => 'index']);
        }

        $this->set('title_for_layout', __d('croogo', 'Select a language'));
        $languages = $this->Languages->find('all', [
            'conditions' => [
                'Language.status' => 1,
            ],
            'order' => 'Language.weight ASC',
        ]);
        $this->set(compact('id', 'modelAlias', 'languages'));
    }
}
