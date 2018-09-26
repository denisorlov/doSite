<?php

namespace Actions\User;


use core\ActionRender;
use core\IAction;

class IndexAction implements IAction
{

  /**
   * @return mixed
   */
  public function getResult()
  {
    return '<h1>Привет на странице пользователей!</h1>';
  }

  /**
   * @return string
   */
  public function getResponseContentType()
  {
    return ActionRender::ct_TEXT_HTML;
  }
}