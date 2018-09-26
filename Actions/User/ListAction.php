<?php
namespace Actions\User;

use common\Actions\User\ListAction as CommonListAction;

class ListAction extends CommonListAction{
  public function getResult()
  {
    $parRes = json_decode(parent::getResult(), true);
    return json_encode([$_REQUEST, $parRes]);
  }
}