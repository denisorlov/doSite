<?php
/**
 * Created by PhpStorm.
 * User: Денис
 * Date: 18.10.2018
 * Time: 20:41
 */

namespace Actions\Index;

use core\AAction;


class IndexAction extends AAction
{
  /**
   * @return string
   */
  public function run()
  {
    $this->result = str_repeat('<br/>Hello from backend, from '.__CLASS__, 77);
    return $this->result;
  }

  /**
   * @throws \Exception
   */
  protected function prepareResponse_TEXT_HTML()
  {
    return '<h3>'.$this->result.'</h3>';
  }

  protected function prepareResponse_APPLICATION_JSON()
  {
    return json_encode(['result'=>$this->prepareResponse_TEXT_HTML()]);
  }

}