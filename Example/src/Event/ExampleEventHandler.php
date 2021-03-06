<?php

namespace Croogo\Example\Event;

use Cake\Event\EventListener;

/**
 * Example Event Handler
 *
 * @category Event
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class ExampleEventHandler extends Object implements EventListener
{

/**
 * implementedEvents
 *
 * @return array
 */
    public function implementedEvents()
    {
        return [
            'Controller.Users.adminLoginSuccessful' => [
                'callable' => 'onAdminLoginSuccessful',
            ],
            'Helper.Layout.beforeFilter' => [
                'callable' => 'onLayoutBeforeFilter',
            ],
            'Helper.Layout.afterFilter' => [
                'callable' => 'onLayoutAfterFilter',
            ],
        ];
    }

/**
 * onAdminLoginSuccessful
 *
 * @param Event $event
 * @return void
 */
    public function onAdminLoginSuccessful($event)
    {
        $Controller = $event->subject();
        $message = sprintf('Welcome %s.  Have a nice day', $Controller->Auth->user('name'));
        $Controller->Flash->success($message);
        $Controller->redirect([
            'admin' => true,
            'plugin' => 'example',
            'controller' => 'example',
            'action' => 'index',
        ]);
    }

/**
 * onLayoutBeforeFilter
 *
 * @param Event $event
 * @return void
 */
    public function onLayoutBeforeFilter($event)
    {
        $search = 'This is the content of your block.';
        $event->data['content'] = str_replace(
            $search,
            '<p style="font-size: 16px; color: green">' . $search . '</p>',
            $event->data['content']
        );
    }

/**
 * onLayoutAfterFilter
 *
 * @param Event $event
 * @return void
 */
    public function onLayoutAfterFilter($event)
    {
        if (strpos($event->data['content'], 'This is') !== false) {
            $event->data['content'] .= '<blockquote>This is added by ExampleEventHandler::onLayoutAfterFilter()</blockquote>';
        }
    }
}
