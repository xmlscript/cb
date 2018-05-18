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


  function text(string $Content=''):\DOMDocument{
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
    $doc = new \DOMDocument;
    $doc->loadXML(sprintf($this->tpl,
      "<MsgType><![CDATA[news]]></MsgType><ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[$Title]]></Title><Description><![CDATA[$Description]]></Description><PicUrl><![CDATA[$PicUrl]]></PicUrl><Url><![CDATA[$Url]]></Url></item></Articles>"
    ),LIBXML_COMPACT|LIBXML_NOBLANKS|LIBXML_NOXMLDECL);
    return $doc;
  }


  /**
   * 如果是图文素材{news_item:[{title,thumb_media_id,show_cover_pic,author,digest,content,url,...]}
   * 如果是视频素材{title,description,down_url}
   * 如果是其他素材，则一律直接返回文件 voice? thumb? image?
   * 自定义菜单是可以指定两种类型：media_id(!text)和view_limited(news)
   */
  function media(string $id):?\DOMDocument{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738730
    $response = request::url(token::HOST.'/cgi-bin/material/get_material')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['media_id'=>$media_id]));

    switch(strstr($resource->header('content-type'),'/',true)){
      case 'application':
        $json = $response->json();
        if(isset($json->news_item)){
          $arr = [];
          foreach($json->news_item as $v){
            $json->thumb_media_id;//FIXME 提取并转换成图片地址
            $PicUrl = '转换图片地址';
            $arr[] = $this->article($json->title, $json->digest, $json->url, $json->show_cover_pic?$PicUrl:null);
          }
          return $this->news(...$arr);
        }else
          return $this->video($id,$json->title, $json->description);
      case 'audio':
        return $this->voice($id);
      case 'image':
        return $this->image($id);
      default:
        return $this->text($type);
        return null;
    }
  }

}
