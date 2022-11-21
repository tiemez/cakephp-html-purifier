<?php
/**
 * Purifier
 *
 * @author    Florian Krämer
 * @copyright 2012 - 2018 Florian Krämer
 * @license   MIT
 */
namespace Tiemez\HtmlPurifier\Model\Behavior;

use ArrayObject;
use Tiemez\HtmlPurifier\Lib\PurifierTrait;
use Cake\Event\Event;
use Cake\ORM\Behavior;

/**
 * HtmlPurifier Behavior
 *
 * Sanitize a set of given fields automatically
 */
class HtmlPurifierBehavior extends Behavior
{

    use PurifierTrait;

    /**
     * Default config
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [],
        'purifierConfig' => 'default',
        'implementedEvents' => [
            'Model.beforeMarshal' => 'beforeMarshal',
        ],
        'implementedMethods' => [
            'purifyHtml' => 'purifyHtml'
        ]
    ];

    /**
     * Before marshal callaback
     *
     * @param  \Cake\Event\Event $event The Model.beforeMarshal event.
     * @param  \ArrayObject      $data  Data.
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data)
    {
        foreach ($this->getConfig('fields') as $key => $field) {
            if (is_int($key) && isset($data[$field])) {
                $data[$field] = $this->purifyHtml($data[$field], $this->getConfig('purifierConfig'));
            }

            if (is_string($key) && is_string($field)) {
                $data[$key] = $this->purifyHtml($data[$key], $this->getConfig($field));
            }
        }
    }
}
