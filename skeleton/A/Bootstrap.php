<?php

include 'A/DL.php';
include 'A/Locator.php';
include 'A/Http/Request.php';
include 'A/Http/Response.php';
include 'A/DataContainer.php';
include 'A/Http/PathInfo.php';
include 'A/Controller/Front.php';
include 'A/Controller/Mapper.php';
include 'A/Config/Ini.php';
#include 'A/Template/Include.php';

class A_Bootstrap
{
   protected $session;
   protected $mapper;
   protected $locator;
   protected $response;
   protected $request;
   protected $pathinfo;
   protected $template;
   protected $front;
   protected $config;
   protected $configPath = 'config.ini';

   public function run() {
      if (!$this->config)    $this->initConfiguration();
      if (!$this->pathinfo)  $this->initPathinfo();
      if (!$this->mapper)    $this->initMapper();
      if (!$this->request)   $this->initRequest();
      if (!$this->response)  $this->initResponse();
      if (!$this->session)   $this->initSession();
      if (!$this->locator)   $this->initLocator();
      if (!$this->front)     $this->initFront();
     //if (!$this->cache)    $this->initCache();
     //if (!$this->database) $this->initDatabase();
     //if (!$this->template) $this->initTemplate();

      $this->pathinfo->run($this->request);

      $this->locator->set('Request', $this->request);
      $this->locator->set('Response', $this->response);
      $this->locator->set('Config', $this->config);
      if (!$this->session) {
         $this->locator->set('Session', $this->session);
      }

      $this->front->run($this->locator);
      if (!$this->response->hasRenderer()) {
         $this->response->setRenderer($this->template);
      }
      echo $this->response->render();
   }

	public function initLocator($locator=null) {
      if (is_object($locator)) {
         $this->locator = $locator;
      } else {
         $this->locator = new A_Locator();         
      }
      return $this->locator;
   }

	public function initSession($session=null) {
		if (is_object($session)) {
			$this->session = $session;
      	} elseif (isset($this->config->session)) {
			include 'A/Session.php';
			$this->session = new A_Session('A');
			if (isset($this->config->sessionHandler)) {
				switch (strtolower($this->config->sessionHandler)) {
	            case 'filesystem' :
	               $this->session->setHandler(new A_Session_Handler_Filesystem($this->config->sessionPath));
	            	break;
	            case 'database' :
	               #$this->session->setHandler(new A_Session_Handler_Database(... some magic config stuff here ...));
	            	break;
				}     
			}
		}
		return $this->session;
	}

   public function initConfiguration($config=null) {
      if (is_object($config)) {
         $this->config = $config;
      } else {
         $config = new A_Config_Ini($this->configPath, 'skeleton');
         $this->config = $config->loadFile();
      }
      return $this->config;
   }
   
   public function initFront($front=null) {
      if (is_object($front)) {
         $this->front = $front;
      } else {
         if (!$this->mapper) $this->initMapper();
         $this->front = new A_Controller_Front($this->mapper, new A_DL('', $this->config->errorController, $this->config->errorAction));
      }
      return $this->front;
   }
   
   public function initMapper($mapper=null) {
      if (is_object($mapper)) {
         $this->mapper = $mapper;
      } else {
         $this->mapper = new A_Controller_Mapper($this->config->applicationPath, new A_DL('', $this->config->defaultController, $this->config->defaultAction));
      }
      return $this->mapper;
   }

   public function initPathinfo($pathinfo=null) {
      if (is_object($pathinfo)) {
         $this->pathinfo = $pathinfo;
      } else {
         $this->pathinfo = new A_Http_PathInfo();
      }
      return $this->pathinfo;
   }

   public function initRequest($request=null) {
      if (is_object($request)) {
         $this->request = $request;
      } else {
         $this->request = new A_Http_Request();
      }
      return $this->request;
   }
   
   public function initResponse($response=null) {
      if (is_object($response)) {
         $this->response = $response;
      } else {
         $this->response = new A_Http_Response();
      }
      return $this->response;
   }

   public function initTemplate($template=null) {
      if (is_object($template)) {
         $this->template = $template;
      } else {
      #   $this->template = new A_Template_Include($this->config->template);
      }
      return $this->template;
   }
}
