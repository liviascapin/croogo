<?php

namespace Croogo\Example\Model\Behavior;

use Cake\ORM\Behavior;

/**
 * Example Behavior
 *
 * @category Behavior
 * @package  Croogo
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class ExampleBehavior extends Behavior
{

/**
 * Setup
 *
 * @param Model $model
 * @param array $config
 * @return void
 */
    public function setup(Model $model, $config = [])
    {
        if (is_string($config)) {
            $config = [$config];
        }

        $this->settings[$model->alias] = $config;
    }

/**
 * afterFind callback
 *
 * @param Model $model
 * @param array $results
 * @param bool$primary
 * @return array
 */
    public function afterFind(Model $model, $results, $primary = false)
    {
        if ($primary && isset($results[0][$model->alias])) {
            foreach ($results as $i => $result) {
                if (isset($results[$i][$model->alias]['body'])) {
                    $results[$i][$model->alias]['body'] .= '<p>[Modified by ExampleBehavior]</p>';
                }
            }
        } elseif (isset($results[$model->alias])) {
            if (isset($results[$model->alias]['body'])) {
                $results[$model->alias]['body'] .= '<p>[Modified by ExampleBehavior]</p>';
            }
        }

        return $results;
    }
}
