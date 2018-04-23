<?php namespace cb; // vim: se fdm=marker:

final class reply{

  private $intent, $tpl;

  function __construct(\SimpleXMLElement &$intent){
    $this->intent = $intent;
    $this->tpl = "<xml><ToUserName><![CDATA[{$intent->FromUserName}]]></ToUserName><FromUserName><![CDATA[{$intent->ToUserName}]]></FromUserName><CreateTime>".time()."</CreateTime>%s</xml>";
  }


  function __get(string $key):\SimpleXMLElement{
    return $this->intent->$key;
  }


  function success():void{
    return;
  }


  function text(string $Content):\DOMDocument{
    $doc = new \DOMDocument;
    $doc->loadXML(sprintf($this->tpl,
      "<MsgType><![CDATA[text]]></MsgType><Content><![CDATA[$Content]]></Content>"
    ),LIBXML_COMPACT|LIBXML_NOBLANKS|LIBXML_NOXMLDECL);
    return $doc;
  }


  function transfer_customer_service(string $w=null):\DOMDocument{
    $doc = new \DOMDocument;
    $doc->loadXML(sprintf($this->tpl,
      "<MsgType><![CDATA[transfer_customer_service]]></MsgType>".($w?"<TransInfo><KfAccount><![CDATA[$w]]></KfAccount></TransInfo>":'')
    ),LIBXML_COMPACT|LIBXML_NOBLANKS|LIBXML_NOXMLDECL);
error_log($doc->saveXML());
    return $doc;
  }


  function image(string $MediaId):?\DOMDocument{
    if(empty($MediaId)) return null;
    $doc = new \DOMDocument;
    $doc->loadXML(sprintf($this->tpl,
      "<MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[$MediaId]]></MediaId></Image>"
    ),LIBXML_COMPACT|LIBXML_NOBLANKS|LIBXML_NOXMLDECL);
    return $doc;
  }


  function voice(string $MediaId):?\DOMDocument{
    if(empty($MediaId)) return null;
    $doc = new \DOMDocument;
    $doc->loadXML(sprintf($this->tpl,
      "<MsgType><![CDATA[voice]]></MsgType><Voice><MediaId><![CDATA[$MediaId]]></MediaId></Voice>"
    ),LIBXML_COMPACT|LIBXML_NOBLANKS|LIBXML_NOXMLDECL);
    return $doc;
  }


  function video(
    string $MediaId,
    string $Title=null,
    string $Description=null
  ):?\DOMDocument{
    if(empty($MediaId)) return null;
    $Title = empty($Title)?'':"<Title><![CDATA[$Title]]></Title>";
    $Description = empty($Description)?'':"<Title><![CDATA[$Description]]></Title>";
    $doc = new \DOMDocument;
    $doc->loadXML(sprintf($this->tpl,
      "<MsgType><![CDATA[video]]></MsgType><Video><MediaId><![CDATA[$MediaId]]></MediaId>{$Title}{$Description}</Video>"
    ),LIBXML_COMPACT|LIBXML_NOBLANKS|LIBXML_NOXMLDECL);
    return $doc;
  }


  function music(
    string $ThumbMediaId,
    string $Title=null,
    string $Description=null,
    string $MusicUrl=null,
    string $HQMusicUrl=null
  ):?\DOMDocument{
    if(empty($ThumbMediaId)) return null;
    $doc = new \DOMDocument;
    $doc->loadXML(sprintf($this->tpl,
      "<MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[$Title]]></Title><Description><![CDATA[$Description]]></Description><MusicUrl><![CDATA[$MusicUrl]]></MusicUrl><HQMusicUrl><![CDATA[$HQMusicUrl]]></HQMusicUrl><ThumbMediaId><![CDATA[$ThumbMediaId]]></ThumbMediaId></Music>"
    ),LIBXML_COMPACT|LIBXML_NOBLANKS|LIBXML_NOXMLDECL);
    return $doc;
  }


  function news(\DOMDocument $article, \DOMDocument ...$articles):\DOMDocument{
    $articles = array_slice($articles,0,7);
    $count = count($articles)+1;
    $doc = new \DOMDocument;
    $doc->loadXML(sprintf($this->tpl,
      "<MsgType><![CDATA[news]]></MsgType><ArticleCount>$count</ArticleCount><Articles></Articles>"
    ),LIBXML_COMPACT|LIBXML_NOBLANKS|LIBXML_NOXMLDECL);
    $container = $doc->getElementsByTagName('Articles')->item(0);
    $container->appendChild($doc->importNode($article->getElementsByTagName('item')->item(0), true));
    foreach($articles as $node)
      $container->appendChild($doc->importNode($node->getElementsByTagName('item')->item(0), true));
    return $doc;
  }


  function article(string $Title=null, string $Description=null, string $Url=null, ?string $PicUrl='about:blank'):?\DOMDocument{
    if(isset($Url)&&!filter_var($Url,FILTER_VALIDATE_URL,FILTER_FLAG_SCHEME_REQUIRED|FILTER_FLAG_HOST_REQUIRED)){
      error_log("Url $Url");
      return null;
    }
    $doc = new \DOMDocument;
    $doc->loadXML(sprintf($this->tpl,
      "<MsgType><![CDATA[news]]></MsgType><ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[$Title]]></Title><Description><![CDATA[$Description]]></Description><PicUrl><![CDATA[$PicUrl]]></PicUrl><Url><![CDATA[$Url]]></Url></item></Articles>"
    ),LIBXML_COMPACT|LIBXML_NOBLANKS|LIBXML_NOXMLDECL);
    return $doc;
  }

}
