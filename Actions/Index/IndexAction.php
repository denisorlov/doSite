<?php

namespace Actions\Index;


use core\ActionRender;

class IndexAction extends AIndexAction
{

  /**
   * @return mixed
   */
  public function getResult()
  {
    include ROOT_PATH.'/index.html.php';
  }

  /**
   * @return string
   */
  public function getResponseContentType()
  {
    return ActionRender::ct_TEXT_HTML;
  }
}