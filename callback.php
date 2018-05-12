<?php namespace cb; // vim: se fdm=marker:

final class callback extends \srv\api{

  private $token;

  private $event, $message;

  function __construct(string $token, message $message, event $event){
    $this->token = $token;
    $this->event = $event;
    $this->message = $message;
  }
  

  function &GET(string $signature, int $timestamp, string $nonce, string $echostr):string{
    $arr = [$this->token, $timestamp, $nonce];
    sort($arr, SORT_STRING);
    if($signature === sha1(implode($arr)))
      return $echostr;
    else
      throw new \Error('Forbidden',403);
  }


  function POST():string{

    libxml_use_internal_errors(true);

    if($dom = simplexml_load_string(file_get_contents('php://input'))){

      if($dom->MsgType=='event')
        $xml = $this->event->{(string)$dom->Event}(new reply($dom));
      else
        $xml = $this->message->{(string)$dom->MsgType}(new reply($dom));

      return ($xml instanceof \DOMDocument)?$xml->saveXML():'success';

    }else throw new \Error(chop(libxml_get_errors()[0]->message??'Unprocessable Entity'),422);

  }

}
