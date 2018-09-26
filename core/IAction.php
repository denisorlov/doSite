<?php

namespace core;

interface IAction
{

  /**
   * Define Content-Type of response
   * @return string
   */
  public function getResponseContentType();

  public function getResult();
}