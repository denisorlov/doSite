<?php

namespace core;


class ActionRouter
{

  private $rootPath;
  private $actionsNameSpace;

  private $module;
  private $action;

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

    $this->module = !empty($arrRUri[1])? $arrRUri[1] : 'index';
    $this->action = !empty($arrRUri[2])? $arrRUri[2] : 'index';

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
    // app->run();
    // app->checkAuthority();
    // return app->checkAccessToAction($this->module, $this->action);
    return true; // dumb
  }

  public function routeToAction(){
    $actionClassName = $this->actionsNameSpace.ucfirst($this->module).'\\'.ucfirst($this->action).'Action';
    $responseContentType = ActionRender::defineResponseContentType();

    if($this->checkActionClassPath($actionClassName)){
      /** @var IAction $action */
      $action = new $actionClassName();
      if($action->getResponseContentType()!== $responseContentType){
        ActionRender::postForbidden();
      }

      if($this->checkAccessToAction()){
        $render = new ActionRender($action);
        $render->output();
      }else{
        ActionRender::postForbidden();
      }
    }else{
      header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
      if($responseContentType === ActionRender::ct_TEXT_HTML){
        // @TODO 404 page
        echo '<h3>404: Такой страницы не найдено...</h3><a href="/">Вернуться на Главную...</a>';
      }
      die();
    }
  }
}