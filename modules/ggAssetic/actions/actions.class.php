<?php

class ggAsseticActions extends sfActions
{
  public function executeJavascript($request)
  {
    try
    {
      $name = $this->getRequest()->getParameter('name');
      $config = sfConfig::get('app_gg_assetic_javascript');
            
      $am = new Assetic\AssetManager();
      $references = array();
      
      foreach ($config[$name]['files'] as $file) {
        $file_ref = str_replace('.', '_', $file); 
      	$am->set($file_ref, new Assetic\Asset\FileAsset(sfConfig::get('sf_web_dir').'/js/'.$file));
      	$references[] = new Assetic\Asset\AssetReference($am, $file_ref);
      }
      
      foreach ($config[$name]['partials'] as $partial) { 
        list($action, $template) = explode('/', $partial);
        $partial_ref = str_replace('/', '_', $partial); 
      	$am->set($partial_ref, new Assetic\Asset\StringAsset($this->getPartial($partial, array('routing' => $this->getContext()->getRouting()))));
        $am->get($partial_ref)->setLastModified(filemtime(sfConfig::get('sf_app_module_dir').'/'.$action.'/templates/_'.$template.'.php'));
        $references[] = new Assetic\Asset\AssetReference($am, $partial_ref);
      }
      
      $am->set('combined', new Assetic\Asset\AssetCollection($references));
      
      $mtime = $this->getResponse()->getDate($am->get('combined')->getLastModified());
      
      if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $mtime == $_SERVER['HTTP_IF_MODIFIED_SINCE']) { 
        header('HTTP/1.0 304 Not Modified');
        exit();
      }
      
      $this->getResponse()->setHttpHeader('Last-Modified', $mtime);
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
            
      $am = new Assetic\AssetManager();
      $references = array();
      
      foreach ($config[$name]['files'] as $file) {
        $file_ref = str_replace('.', '_', $file); 
      	$am->set($file_ref, new Assetic\Asset\FileAsset(sfConfig::get('sf_web_dir').'/css/'.$file));
      	$references[] = new Assetic\Asset\AssetReference($am, $file_ref);
      }

      $am->set('combined', new Assetic\Asset\AssetCollection($references));
      
      $mtime = $this->getResponse()->getDate($am->get('combined')->getLastModified());
      
      if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $mtime == $_SERVER['HTTP_IF_MODIFIED_SINCE']) { 
        header('HTTP/1.0 304 Not Modified');
        exit();
      }
      
      $this->getResponse()->setHttpHeader('Last-Modified', $mtime);
      $this->getResponse()->setHttpHeader('Content-Type', 'text/css');
      return $this->renderText($am->get('combined')->dump());
    }
    catch (Exception $e)
    {
      throw new sfError404Exception($e->getMessage());
    }
  }

}
