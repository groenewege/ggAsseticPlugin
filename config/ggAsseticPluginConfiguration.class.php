<?php

require_once dirname(__FILE__).'/../lib/vendor/Doctrine/Common/ClassLoader.php';

/**
 * ggAsseticPluginConfiguration configures application to use Assetic.
 *
 * @package    ggAsseticPlugin
 * @subpackage configuration
 * @author     Gunther Groenewege <gunther@groenewege.com>
 * @version    1.0.0
 */
class ggAsseticPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $classLoader = new Doctrine\Common\ClassLoader('Assetic', __DIR__ . '/../lib/vendor/assetic_library/src');
    $classLoader->register();
    
    $this->dispatcher->connect('routing.load_configuration', array('ggAsseticListeners', 'loadAsset'));
 
  }
}
