<?php

namespace core;

class ActionRender
{
  const ct_TEXT_HTML = 'text/html';
  const ct_TEXT_PLAIN = 'text/plain';
  const ct_APPLICATION_JSON = 'application/json';
  const ct_APPLICATION_JAVASCRIPT = 'application/javascript';
  const ct_APPLICATION_XML = 'application/xml';

  private $action;
  public $charset = 'UTF-8';

  /**
   * ActionRender constructor.
   * @param IAction $action
   */
  public function __construct(IAction $action)
  {
    $this->action = $action;
  }

  public function output(){
    header("Content-Type: {$this->action->getResponseContentType()}; charset={$this->charset}");
    die ($this->action->getResult());
  }

  public static function postForbidden(){
    header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
    die();
  }

  /**
   * define response Content-Type from request $_SERVER["HTTP_ACCEPT"]
   * @return string
   */
  public static function defineResponseContentType(){
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
}