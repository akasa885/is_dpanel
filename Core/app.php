<?php

class app
{
  protected $controller = 'home';
  protected $method = 'index';
  protected $params = [];

  public function __construct($param)
  {
    $this->param = $param;
    $url = $this->parseURL();

    //class
    if($param == 'dpanel')
    {
      if(file_exists('Controller/'.$url[0].'.php'))
      {
        $this->controller = $url[0];
        unset($url[0]);
      }
      require_once'Controller/'.$this->controller.'.php';
      $this->controller = new $this->controller;
      unset ($url[0]);
      //method
      if(isset($url[1]))
      {
        if(method_exists($this->controller, $url[1]))
        {
          $this->method = $url[1];
          unset ($url[1]);
        }
      }
      //params
      if(!empty($url))
      {
        $this->params = array_values($url);
      }
      call_user_func_array([$this->controller, $this->method],$this->params);
    }
    else
    {
      if(file_exists('App/Controller/'.$url[0].'.php'))
      {
          $this->controller = $url[0];
          unset ($url[0]);
      }
      elseif ($url[0] == 'Core' or $url[0] == 'Controller' or $url[0] == 'Model' or $url[0] == 'View')
      {
        $this->controller = 'forbidden';
        unset ($url[0]);
      }
      else if(!$url[0] == '' )
      {
        $this->controller = 'nofound';
        unset ($url[0]);
      }
      // unset($url[0]);
      require_once'App/Controller/'.$this->controller.'.php';
      $this->controller = new $this->controller;
      //method
      if(isset($url[1]))
      {
        if(method_exists($this->controller, $url[1]))
        {
          $this->method = $url[1];
          unset ($url[1]);
        }
      }
      //params
      if(!empty($url))
      {
        $this->params = array_values($url);
      }
      call_user_func_array([$this->controller, $this->method],$this->params);
    }
  }


  public function parseURL()
  {
    if(isset($_GET['url']))
    {
      $url=rtrim($_GET['url'],'/');//menghilangkan slash diakhir url
      $url=filter_var($url, FILTER_SANITIZE_URL);
      $url=explode('/',$url);//untuk memisahkan kata2 diantara slash
      return $url;
    }
  }
}
