<?php

namespace core;

class ActionResponser
{
  const ct_TEXT_HTML = 'text/html';
  const ct_TEXT_PLAIN = 'text/plain';
  const ct_APPLICATION_JSON = 'application/json';
  const ct_APPLICATION_JAVASCRIPT = 'application/javascript';
  const ct_APPLICATION_XML = 'application/xml';

  const rc_FORBIDDEN = 403;
  const rc_NOT_FOUND = 404;
  const rc_SUCCESS = 200;
  const rc_INTERNAL_SERVER_ERROR = 500;

  /** @var AAction  */
  private $action = null;
  private $mainTemplatePath = null;
  private $responseContentType = self::ct_TEXT_HTML;

  public $charset = 'UTF-8';

  /**
   * ActionRender constructor.
   */
  public function __construct($mainTemplatePath)
  {
    $this->mainTemplatePath = $mainTemplatePath;
    $this->responseContentType = $this->defineResponseContentType();
  }

  public static function logException(\Throwable $e)
  {
    $now = time();
    $date = date('c', $now); // 2004-02-12T15:19:21+00:00
    $logMsg=$date."\t".'Exception '.$e->getCode().': "'.$e->getMessage().'" at '.
      $e->getFile().':'.$e->getLine()."\n"."Trace:\n".$e->getTraceAsString()."\n";
    error_log($logMsg);
    return $now;
  }

  public function treatException(\Throwable $e)
  {
    $logTime = self::logException($e);
    $result = $e instanceof PublicException ? $e->getMessage() :
      'Произошла ошибка, см. запись в логе от '.date('c', $logTime); // скрываем ошибку в логе

    switch($this->responseContentType){
      case ActionResponser::ct_APPLICATION_JSON:
        $result = json_encode(['error'=>$result]);
        break;
      case ActionResponser::ct_APPLICATION_JAVASCRIPT:
        $result = str_replace("'", '"', $result);
        $result = "throw new Error('{$result}')";
        break;
      case ActionResponser::ct_APPLICATION_XML:
        $result = '<error>'.htmlspecialchars($result).'</error>';
        break;
      case ActionResponser::ct_TEXT_HTML:
        $result = htmlspecialchars($result);
        break;
      case ActionResponser::ct_TEXT_PLAIN:
      default:
        break;
    }
    return $result;
  }

  public function sendResponse($http_response_code=self::rc_SUCCESS, $response=null)
  {
    switch($http_response_code){
      case self::rc_FORBIDDEN:
        header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden', true, 403);
        $response = $this->isHtmlResponse() ? '<h3>Доступ запрещен.</h3><a href="/">На главную...</a>' : null;
        $this->echoResponse($response);
        break;
      case self::rc_NOT_FOUND:
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found', true, self::rc_NOT_FOUND);
        $response = $this->isHtmlResponse() ? '<h3>Страница не найдена.</h3><a href="/">На главную...</a>' : null;
        $this->echoResponse($response);
        break;
      case self::rc_SUCCESS:
        header("Content-Type: {$this->responseContentType}; charset={$this->charset}");
        $this->echoResponse($response);
        break;
    }
  }

  public function echoResponse($response=null)
  {
    if($this->isHtmlResponse()) {
      global $HTML;
      $HTML = $response;
      if(!self::isXMLHttpRequest()){
        $templatePath = $this->action && is_file($this->action->getTemplatePath()) ? $this->action->getTemplatePath() : $this->mainTemplatePath;
        if(is_file($templatePath)){
          include $templatePath;
        }
      }
    }elseif($response!==null){
      echo $response;
    }
    exit();
  }

  public function isHtmlResponse ()
  {
    return $this->responseContentType === self::ct_TEXT_HTML;
  }

  public static function isXMLHttpRequest ()
  {
    return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] === 'XMLHttpRequest';
  }
  /**
   * define response Content-Type from request $_SERVER["HTTP_ACCEPT"]
   * @return string
   */
  public function defineResponseContentType()
  {
    $httpAccept = !empty($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '*/*';
    $httpAcceptArr = preg_split('/,\s?/', $httpAccept);
    $httpAcceptFst = array_shift($httpAcceptArr);

    switch($httpAcceptFst){
      case 'text/plain':
        $res = self::ct_TEXT_PLAIN;
        break;
      case 'application/json':
        $res = self::ct_APPLICATION_JSON;
        break;
      case 'text/javascript':
      case 'application/javascript':
        $res = self::ct_APPLICATION_JAVASCRIPT;
        break;
      case 'application/xml':
      case 'text/xml':
        $res = self::ct_APPLICATION_XML;
        break;
      default:
        $res = self::ct_TEXT_HTML;
        break;
    }

    return $res;
  }

  /**
   * @param AAction $action
   * @return ActionResponser
   */
  public function setAction($action)
  {
    $this->action = $action;
    return $this;
  }

  /**
   * @return string
   */
  public function getResponseContentType()
  {
    return $this->responseContentType;
  }
}