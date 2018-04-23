<?php namespace cb; // vim: se fdm=marker:

class message{

  final function __call(string $fn, array $args):void{
    error_log(__CLASS__."::$fn");
  }


  function text(reply $reply):?\DOMDocument{
    return null;
  }


  function image(reply $reply):?\DOMDocument{
    return null;
  }


  function voice(reply $reply):?\DOMDocument{
    return null;
  }


  function video(reply $reply):?\DOMDocument{
    return null;
  }


  function shortvideo(reply $reply):?\DOMDocument{
    return null;
  }


  function location(reply $reply):?\DOMDocument{
    return null;
  }


  function link(reply $reply):?\DOMDocument{
    return null;
  }

}
