<?php
namespace common\Actions\User;

class ListAction extends AUserAction{

  public function getResult()
  {
    return json_encode($_SERVER);
  }

  /**
   * @return string
   */
  public function getResponseContentType()
  {
    return \core\ActionRender::ct_APPLICATION_JSON;
  }
}