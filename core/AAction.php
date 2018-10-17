<?php
/**
 * Created by PhpStorm.
 * User: Денис
 * Date: 17.10.2018
 * Time: 20:17
 */

namespace core;

abstract class AAction implements IAction
{
  private $result;

  public function run()
  {
    $this->result = 'Not overridden method '.__METHOD__;
    return $this->result;
  }

  protected function prepareResponse_TEXT_PLAIN()
  {
    throw new \Exception('Not overridden method '.__METHOD__);
  }
  protected function prepareResponse_APPLICATION_JSON()
  {
    throw new \Exception('Not overridden method '.__METHOD__);
  }
  protected function prepareResponse_APPLICATION_JAVASCRIPT()
  {
    throw new \Exception('Not overridden method '.__METHOD__);
  }
  protected function prepareResponse_APPLICATION_XML()
  {
    throw new \Exception('Not overridden method '.__METHOD__);
  }
  protected function prepareResponse_TEXT_HTML()
  {
    throw new \Exception('Not overridden method '.__METHOD__);
  }

  public function prepareResponse($responseContentType)
  {
    $result = null;
    switch($responseContentType){
      case ActionResponser::ct_TEXT_PLAIN:
        $result = $this->prepareResponse_TEXT_PLAIN();
        break;
      case ActionResponser::ct_APPLICATION_JSON:
        $result = $this->prepareResponse_APPLICATION_JSON();
        break;
      case ActionResponser::ct_APPLICATION_JAVASCRIPT:
        $result = $this->prepareResponse_APPLICATION_JAVASCRIPT();
        break;
      case ActionResponser::ct_APPLICATION_XML:
        $result = $this->prepareResponse_APPLICATION_XML();
        break;
      case ActionResponser::ct_TEXT_HTML:
        $result = $this->prepareResponse_TEXT_HTML();
        break;
      default:
        throw new \Exception('Not defined method for Content-Type '.$responseContentType);
        break;
    }
    return $result;
  }

  public function getTemplatePath()
  {
    return null;
  }

  /**
   * @return mixed
   */
  public function getResult()
  {
    return $this->result;
  }


}