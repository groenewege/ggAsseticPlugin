<?php

class ggAsseticActions extends sfActions
{
  public function executeJavascript($request)
  {
    try
    {
      $name = $this->getRequest()->getParameter('name');
      $config = sfConfig::get('app_gg_assetic_javascript');
            
      $am = $this->createCollection($name, $config, 'js');
      
      $mtime = $this->getResponse()->getDate($am->get('combined')->getLastModified());
      
      $this->getResponse()->addCacheControlHttpHeader('private=True');
      $this->getResponse()->setHttpHeader('Last-Modified', $mtime);
      
      if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $mtime == $_SERVER['HTTP_IF_MODIFIED_SINCE']) { 
        $this->getResponse()->setHeaderOnly(true);
        $this->getResponse()->setStatusCode(304);
        $this->getResponse()->send();
      }
      
      $this->getResponse()->setHttpHeader('Content-Type', 'application/javascript');
      return $this->renderText($am->get('combined')->dump());
    }
    catch (Exception $e)
    {
      throw new sfError404Exception($e->getMessage());
    }
  }
  
  public function executeStylesheet($request)
  {
    try
    {
      $name = $this->getRequest()->getParameter('name');
      $config = sfConfig::get('app_gg_assetic_css');
            
      $am = $this->createCollection($name, $config, 'css');
      
      $mtime = $this->getResponse()->getDate($am->get('combined')->getLastModified());
      
      $this->getResponse()->addCacheControlHttpHeader('private=True');
      $this->getResponse()->setHttpHeader('Last-Modified', $mtime);
      
      if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $mtime == $_SERVER['HTTP_IF_MODIFIED_SINCE']) { 
        $this->getResponse()->setHeaderOnly(true);
        $this->getResponse()->setStatusCode(304);
        $this->getResponse()->send();
      }
      
      $this->getResponse()->setHttpHeader('Content-Type', 'text/css');
      return $this->renderText($am->get('combined')->dump());
    }
    catch (Exception $e)
    {
      throw new sfError404Exception($e->getMessage());
    }
  }
  
  /**
   * createCollection function creates an assetManager 
   * and populates it with the files and partials before returning it
   *
   * @param string $name    The name of the collection 
   * @param array $config   The app.yml configuration
   * @param string $dir     The directory for files : css or js
   * @return AssetManager
   * @author g-design.net
   */
  protected function createCollection($name, $config = array(), $dir = 'css')
  {
    $am = new Assetic\AssetManager();
    $references = array();
    
    if (isset($config[$name]['files'])) {
      foreach ($config[$name]['files'] as $file) {
        $file_ref = str_replace('.', '_', $file); 
        $am->set($file_ref, new Assetic\Asset\FileAsset(sfConfig::get('sf_web_dir').'/'.$dir.'/'.$file));
        $references[] = new Assetic\Asset\AssetReference($am, $file_ref);
      }
    }

    if (isset($config[$name]['partials'])) {
    	foreach ($config[$name]['partials'] as $partial) { 
        list($action, $template) = explode('/', $partial);
        $partial_ref = str_replace('/', '_', $partial); 
        $am->set($partial_ref, new Assetic\Asset\StringAsset($this->getPartial($partial, array('routing' => $this->getContext()->getRouting()))));
        $am->get($partial_ref)->setLastModified(filemtime(sfConfig::get('sf_app_module_dir').'/'.$action.'/templates/_'.$template.'.php'));
        $references[] = new Assetic\Asset\AssetReference($am, $partial_ref);
      }
    }
    
    
    $am->set('combined', new Assetic\Asset\AssetCollection($references));
    
    return $am;
  }

}
