<?php namespace cb;

class news{

  private $Title, $Description, $PicUrl, $Url;

  /**
   * @todo 能否从素材解析？至少菜单的view_limited类型可以指定media_id从而返回news
   */
  function __construct(string $Title, string $Description, string $PicUrl, string $Url){
    $this->Title = $Title;
    $this->Description = $Description;
    $this->PicUrl = $PicUrl;
    $this->Url = $Url;
  }

  static media(string $id):self{
    //TODO 从media_id获取news，然后转换成xml风格
    return new self();
  }

  function __toString():string{
    return sprintf('<item><Title>%s</Title><Description>%s</Description><PicUrl>%s</PicUrl><Url>%s</Url></item>',$this->Title, $this->Description, $this->PicUrl, $this->Url);
  }
}
