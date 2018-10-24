<?php

namespace core;


class ActionRouter
{

  private $rootPath;
  private $actionsNameSpace;

  private $module;
  private $action;

  const DEFAULT_MODULE = 'index';
  const DEFAULT_ACTION = 'index';

  /**
   * ActionRouter constructor.
   * @param $rootPath
   * @param $actionsNameSpace
   */
  public function __construct($rootPath, $actionsNameSpace)
  {
    $this->rootPath = $rootPath;
    $this->actionsNameSpace = $actionsNameSpace;

    // define module & action from REQUEST_URI
    $noQS = explode('?', $_SERVER['REQUEST_URI']);
    $sRUri = array_shift($noQS); // отскекаем QUERY_STRING
    $arrRUri = explode('/', $sRUri);

    $this->module = !empty($arrRUri[1])? $arrRUri[1] : self::DEFAULT_MODULE;
    $this->action = !empty($arrRUri[2])? $arrRUri[2] : self::DEFAULT_ACTION;

    spl_autoload_register(function($className) {
      $path = $this->checkActionClassPath($className);
      if($path){
        include $path;
      }else{
        trigger_error($className.' class not found  <pre>'.
          (new \Exception())->getTraceAsString());// print trace
      }
    });
  }

  /**
   * @param $className
   * @return bool|string file path or false
   */
  private function checkActionClassPath($className){
    $file_path = $this->rootPath . '/'. str_replace('\\',  '/', $className) . '.php';
    return file_exists($file_path) ? $file_path : false;
  }

  /**
   * @param $module
   * @param $action
   * @return bool
   */
  private function checkAccessToAction(){
    // @TODO this method should be implemented!
    // for example:
    // session_start();
    // checkAuthority();
    // return checkAccessToAction($this->module, $this->action);
    return true; // dumb
  }

  public function buildClassName($module, $action){
    return $this->actionsNameSpace.ucfirst($module).'\\'.ucfirst($action).'Action';
  }

  public function routeToAction(){
    $actionClassName = $this->buildClassName($this->module, $this->action);
    $responser = new ActionResponser(ROOT_PATH.'/index.html.php');

    if(!$this->checkActionClassPath($actionClassName)) { // class not exists
      $responser->setHttpResponseCode(ActionResponser::rc_NOT_FOUND);
    }else
    if($this->checkAccessToAction()){
      try {
        /** @var AAction $action */
        $action = new $actionClassName();
        $responser->setAction($action);
        $action->run();
        $response = $action->prepareResponse($responser->getResponseContentType());
        $http_response_code = ActionResponser::rc_SUCCESS;
      } catch (\Exception $e) {
        $response = $responser->treatException($e);
        $http_response_code = ActionResponser::rc_INTERNAL_SERVER_ERROR;
      }
      $responser->setHttpResponseCode($http_response_code);
      $responser->setResponse($response);
    }else{
      $responser->setHttpResponseCode(ActionResponser::rc_FORBIDDEN);
    }
    $responser->sendResponse();
  }
}