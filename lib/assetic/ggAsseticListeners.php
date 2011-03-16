<?php

 /**
  * ggAsseticListeners is Assetic listeners manager for symfony.
  *
  * @package    ggAsseticPlugin
  * @subpackage lib
  * @author     Gunther Groenewege <gunther@groenewege.com>
  * @version    1.0.0
  */
class ggAsseticListeners
{
  /**
   * Listens to the routing.load_configuration event. redirects asset link to plugin module
   *
   * @param   sfEvent $event  an sfEvent instance
   */
  static public function loadAsset(sfEvent $event)
  {
    $routing = $event->getSubject();
    $routing->prependRoute('asset_js', new sfRoute('/js/:name.js', array(
          'module' => 'ggAssetic',
          'action' => 'javascript')
        ));
    $routing->prependRoute('asset_css', new sfRoute('/css/:name.css', array(
          'module' => 'ggAssetic',
          'action' => 'stylesheet')
        ));

  }
}
