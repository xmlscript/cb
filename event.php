<?php namespace cb; // vim: se fdm=marker:

class event{

  final function __call(string $fn, array $args):void{
    error_log(__CLASS__."::$fn");
  }


  /**
   * 已关注粉丝相机触发扫码事件
   * @param string EventKey 事件KEY值，qrscene_为前缀，后面为二维码的参数值,分割。①scene表示场景：scanbarcode为扫码场景，scanimage为扫封面（图像）场景。②keystandard表示商品编码标准：barcode为条码。③keystr表示商品编码内容。④extinfo表示调用“获取商品二维码接口”时传入的extinfo，为标识参数。
   * @param ?string Ticket 二维码的ticket，可用来换取二维码图片
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542 从这个接口生成场景二维码
   */
  function scan(reply $reply):?\DOMDocument{
    return null;
  }


  /**
   * 扫码加新粉儿时，将携带EventKey和Ticket
   * @param int EventKey 事件KEY值，是一个32位无符号整数，即创建二维码时的二维码scene_id
   * @param ?string Ticket 二维码的ticket，可用来换取二维码图片
   */
  function subscribe(reply $reply):?\DOMDocument{
    //TODO 赶紧第一时间调用invoke->w($reply->FromUserName)获取用户信息快照并存入DB
    //用户日后点击View类型的按钮或点击纯URL，经过微信跳转之后得到code并换取access_token然后请求userinfo将得到用户的openid
    //此时的用户肯定已经是粉丝了(因为不点击关注就无法进入会话），这时拿到用户详细信息无须用户授权
    //跳转过后的网页拿到了code，需js请求内部api获取userinfo，或者服务端session存一份userinfo
    //如果是snsapi_userinfo，则需要麻烦用户点击授权，还不如snsapi_base静默授权，拿到code换access_token并获取openid，再去自家DB获取快照
    //FIXME 如何保证内嵌SPA单页面获知当前用户openid？init时存入状态机
    //FIXME 如何保证内嵌WPA多页面获知当前用户openid？必须依靠session，而session过期之后，任意请求均重定向到微信跳转，再次授权
    return $reply->text('Welcome.');
  }


  /**
   * 脱粉事件
   */
  function unsubscribe(reply $reply):void{}


  /**
   * 自动地理上报，可能是刚进入会话时，也可能每5秒一次，也可能是location_select按钮类型触发，或网页触发？？？
   * @param float Latitude
   * @param float Longitude
   * @param float Precision
   */
  function location(reply $reply):void{}


  //{{{ 自定义菜单事件 https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141016


  function click(reply $reply):?\DOMDocument{
    return null;
  }


  function view(reply $reply):void{}


  /**
   * 按钮触发的扫码事件
   * @param EventKey 事件KEY值，由开发者在创建菜单时设定
   * @param ScanCodeInfo->ScanType 一般是qrcode
   * @param ScanCodeInfo->ScanResult qrcode解析到的字符串
   */
  function scancode_push(reply $reply):void{}


  /**
   * 按钮触发的扫码事件，允许回复用户消息
   * @param EventKey 事件KEY值，由开发者在创建菜单时设定
   * @param ScanCodeInfo->ScanType 一般是qrcode
   * @param ScanCodeInfo->ScanResult qrcode解析到的字符串
   */
  function scancode_waitmsg(reply $reply):?\DOMDocument{
    return null;
  }


  /**
   * @param EventKey 事件KEY值，由开发者在创建菜单时设定
   * @param int SendPicsInfo->Count
   * @param string SendPicsInfo->PicList->item->PicMd5Sum
   */
  function pic_sysphoto(reply $reply):void{}


  /**
   * @param EventKey 事件KEY值，由开发者在创建菜单时设定
   * @param int SendPicsInfo->Count
   * @param string SendPicsInfo->PicList->item->PicMd5Sum
   */
  function pic_sysphoto_or_album(reply $reply):void{}


  /**
   * 弹出微信相册发图器时触发
   * @param EventKey 事件KEY值，由开发者在创建菜单时设定
   * @param int SendPicsInfo->Count
   * @param string SendPicsInfo->PicList->item->PicMd5Sum
   */
  function pic_weixin(reply $reply):void{}


  /**
   * 菜单按钮type=location_select，触发了这个事件，但是有什么用呢？总之一定会收到location消息
   * @param EventKey 事件KEY值，由开发者在创建菜单时设定
   * @param float SendLocationInfo->Location_X
   * @param float SendLocationInfo->Location_Y
   * @param int SendLocationInfo->Scale 精度，可理解为精度或者比例尺、越精细的话 scale越高
   * @param string SendLocationInfo->Label "广州市海珠区客村艺苑路 106号"
   * @param ?string SendLocationInfo->Poiname 朋友圈POI的名字，可能为空
   */
  function location_select(reply $reply):void{}

  //}}}


  //{{{ 认证事件 https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1455785130

  /**
   * @param int ExpiredTime
   */
  function qualification_verify_success(\SimpleXMLElement $xml):void{}//FIXME 没想好形参


  /**
   * @param int FailTime
   * @param string FailReason "by time"
   */
  function qualification_verify_fail(\SimpleXMLElement $xml):void{}


  /**
   * @param int ExpiredTime
   */
  function naming_verify_success(\SimpleXMLElement &$dom):void{}


  /**
   * @param int FailTime
   * @param string FailReason "by time"
   */
  function naming_verify_fail(\SimpleXMLElement &$dom):void{}


  /**
   * @param int ExpiredTime 将于该时间戳认证过期，需尽快年审
   */
  function annual_renew(\SimpleXMLElement &$dom):void{}


  /**
   * @param string FromUserName 此时的发送方是系统账号
   * @param int ExpiredTime 已经于该时间戳认证过期
   */
  function verify_expired(\SimpleXMLElement &$dom):void{}

  //}}}


  //{{{ 卡券事件 https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025274

  function card_pass_check(\SimpleXMLElement &$dom):void{}//卡券审核

  /**
   * @param string CardId
   * @param string RefuseReason
   */
  function card_not_pass_check(\SimpleXMLElement &$dom):void{}//卡券审核

  /**
   * @param string CardId
   * @param int IsGiveByFriend 是否为转赠领取，1代表是，0代表否。
   * @param string FriendUserName 当IsGiveByFriend为1时填入的字段，表示发起转赠用户的openid
   * @param int UserCardCode code序列号。
   * @param OldUserCardCode 为保证安全，微信会在转赠发生后变更该卡券的code号，该字段表示转赠前的code。
   * @param int OutId
   * @param string OuterStr 领取场景值，用于领取渠道数据统计。可在生成二维码接口及添加Addcard接口中自定义该字段的字符串值。
   * @param int IsRestoreMemberCard 用户删除会员卡后可重新找回，当用户本次操作为找回时，该值为1，否则为0
   * @param int IsRecommendByFriend
   */
  function user_get_card(\SimpleXMLElement &$dom):void{}//用户领取卡券


  /**
   * @param string CardId
   * @param UserCardCode
   * @param bool IsReturnBack 是否转赠退回，0代表不是，1代表是。
   * @param string FriendUserName 接收卡券用户的openid
   * @param bool IsChatRoom 是否是群转赠
   */
  function user_gifting_card(\SimpleXMLElement &$dom):void{}//转赠卡券


  /**
   * @param CardId
   * @param UserCardCode code序列号。自定义code及非自定义code的卡券被领取后都支持事件推送。
   */
  function user_del_card(\SimpleXMLElement &$dom):void{}//用户删除卡券


  /**
   * @param CardId
   * @param UserCardCode code序列号。自定义code及非自定义code的卡券被领取后都支持事件推送。
   * @param ConsumeSource 核销来源。支持开发者统计API核销（FROM_API）、公众平台核销（FROM_MP）、卡券商户助手核销（FROM_MOBILE_HELPER）（核销员微信号）
   * @param LocationName 门店名称，当前卡券核销的门店名称（只有通过自助核销和买单核销时才会出现该字段）
   * @param StaffOpenId 核销该卡券核销员的openid（只有通过卡券商户助手核销时才会出现）
   * @param VerifyCode 自助核销时，用户输入的验证码
   * @param RemarkAmount 自助核销时 ，用户输入的备注金额
   * @param OuterStr 开发者发起核销时传入的自定义参数，用于进行核销渠道统计
   */
  function user_consume_card(\SimpleXMLElement &$dom):void{}//卡券被核销


  /**
   * @param CardId
   * @param UserCardCode code序列号。自定义code及非自定义code的卡券被领取后都支持事件推送。
   * @param TransId 微信支付交易订单号（只有使用买单功能核销的卡券才会出现）
   * @param LocationId 门店ID，当前卡券核销的门店ID（只有通过卡券商户助手和买单核销时才会出现）
   * @param Fee 实付金额，单位为分
   * @param OriginalFee 应付金额，单位为分
   */
  function user_pay_from_pay_cell(\SimpleXMLElement &$dom):void{}//买单完成时


  /**
   * 用户在进入会员卡时，微信会把这个事件推送到开发者填写的URL。
   * 需要开发者在创建会员卡时填入need_push_on_view 字段并设置为true。开发者须综合考虑领卡人数和服务器压力，决定是否接收该事件。
   * @param CardId
   * @param UserCardCode 商户自定义code值。非自定code推送为空串。
   * @param OuterStr 商户自定义二维码渠道参数，用于标识本次扫码打开会员卡来源来自于某个渠道值的二维码
   */
  function user_view_card(\SimpleXMLElement &$dom):void{}//用户进入会员卡Activity


  /**
   * 用户在卡券里点击查看公众号进入会话时（需要用户已经关注公众号）触发。
   * 开发者可识别从卡券进入公众号的用户身份。
   * @param CardId
   * @param UserCardCode 商户自定义code值。非自定code推送为空串。
   */
  function user_enter_session_from_card(\SimpleXMLElement &$dom):void{}//从卡券进入公众号session时


  /**
   * 当用户的会员卡积分余额发生变动时
   * @param CardId
   * @param UserCardCode 商户自定义code值。非自定code推送为空串。
   * @param int ModifyBonus 变动的积分值
   * @param int ModifyBalance 变动的余额值
   */
  function update_member_card(\SimpleXMLElement &$dom):void{}//会员卡积分余额变动时


  /**
   * 当某个card_id的初始库存数大于200且当前库存小于等于100时，用户尝试领券会触发发送事件给商户，事件每隔12h发送一次。
   * @param CardId
   * @param string Detail 报警详细信息 "the card's quantity is equal to 0"
   */
  function card_sku_remind(\SimpleXMLElement &$dom):void{}//某个card_id初始库存大于200，而当前不足100时，用户领券触发；触发频率12小时一次


  /**
   * 当商户朋友的券券点发生变动时触发
   * @param OrderId 本次推送对应的订单号
   * @param Status 本次订单号的状态,ORDER_STATUS_WAITING 等待支付 ORDER_STATUS_SUCC 支付成功 ORDER_STATUS_FINANCE_SUCC 加代币成功 ORDER_STATUS_QUANTITY_SUCC 加库存成功 ORDER_STATUS_HAS_REFUND 已退币 ORDER_STATUS_REFUND_WAITING 等待退币确认 ORDER_STATUS_ROLLBACK 已回退,系统失败 ORDER_STATUS_HAS_RECEIPT 已开发票
   * @param CreateOrderTime 购买券点时，支付二维码的生成时间
   * @param PayFinishTime 购买券点时，实际支付成功的时间
   * @param Desc 支付方式，一般为微信支付充值
   * @param FreeCoinCount 剩余免费券点数量
   * @param PayCoinCount 剩余付费券点数量
   * @param RefundFreeCoinCount 本次变动的免费券点数量
   * @param RefundPayCoinCount 本次变动的付费券点数量
   * @param OrderType 所要拉取的订单类型 ORDER_TYPE_SYS_ADD 平台赠送券点 ORDER_TYPE_WXPAY 充值券点 ORDER_TYPE_REFUND 库存未使用回退券点 ORDER_TYPE_REDUCE 券点兑换库存 ORDER_TYPE_SYS_REDUCE 平台扣减
   * @param Memo 系统备注，说明此次变动的缘由，如开通账户奖励、门店奖励、核销奖励以及充值、扣减。
   * @param ReceiptInfo 所开发票的详情
   */
  function card_pay_order(\SimpleXMLElement &$dom):void{}


  /**
   * 当用户通过一键激活的方式提交信息并点击激活或者用户修改会员卡信息后触发
   * @param CardId
   * @param UserCardCode 商户自定义code值。非自定code推送为空串。
   */
  function submit_membercard_user_info(\SimpleXMLElement &$dom):void{}//会员卡激活或修改

  //}}}


  //{{{ 模板消息事件 https://mp.weixin.qq.com/wiki?action=doc&id=mp1433751277#6

  /**
   * 模板消息发送完毕后触发，可能是成功、用户设置绝收公众号消息、或其他原因
   * @param MsgID
   * @param string Status "success" "failed:user block" "failed: system failed"
   */
  function TemplateSendJobFinish(\SimpleXMLElement &$dom):void{}


  /**
   * 群发任务如果被服务器接受并开始执行，将会在群发完毕后，触发事件，获得报告
   * @param FromUserName
   * @param CreateTime
   * @param MsgType
   * @param Event
   * @param int MsgID
   * @param string Status 群发的结构，为“send success”或“send fail”或“err(num)”。但send success时，也有可能因用户拒收公众号的消息、系统错误等原因造成少量用户接收失败。err(num)是审核失败的具体原因，可能的情况如下： err(10001), //涉嫌广告 err(20001), //涉嫌政治 err(20004), //涉嫌社会 err(20002), //涉嫌色情 err(20006), //涉嫌违法犯罪 err(20008), //涉嫌欺诈 err(20013), //涉嫌版权 err(22000), //涉嫌互推(互相宣传) err(21000), //涉嫌其他 err(30001) // 原创校验出现系统错误且用户选择了被判为转载就不群发 err(30002) // 原创校验被判定为不能群发 err(30003) // 原创校验被判定为转载文且用户选择了被判为转载就不群发
   * @param int TotalCount tag_id下粉丝数；或者openid_list中的粉丝数
   * @param int FilterCount 过滤（过滤是指特定地区、性别的过滤、用户设置拒收的过滤，用户接收已超4条的过滤）后，准备发送的粉丝数，原则上，FilterCount = SentCount + ErrorCount
   * @param int SentCount 发送成功的粉丝数
   * @param int ErrorCount
   *
   * @param int CopyrightCheckResult->ResultList->item->ArticleIdx 群发文章的序号，从1开始
   * @param int CopyrightCheckResult->ResultList->item->UserDeclareState 用户声明文章的状态
   * @param int CopyrightCheckResult->ResultList->item->AuditState 系统校验的状态
   * @param string CopyrightCheckResult->ResultList->item->OriginalArticleUrl 相似原创文的url
   * @param int CopyrightCheckResult->ResultList->item->OriginalArticleType
   * @param int CopyrightCheckResult->ResultList->item->CanReprint 是否能转载
   * @param int CopyrightCheckResult->ResultList->item->NeedReplaceContent 是否需要替换成原创文内容
   * @param int CopyrightCheckResult->ResultList->item->NeedShowReprintSource 是否需要注明转载来源
   * @param int CopyrightCheckResult->CheckState 整体校验结果 1-未被判为转载，可以群发，2-被判为转载，可以群发，3-被判为转载，不能群发
   */
  function MASSSENDJOBFINISH(\SimpleXMLElement &$dom):void{}

  //}}}


  //{{{ 门店审核事件 https://mp.weixin.qq.com/wiki?action=doc&id=mp1444378120#8

  /**
   * @param UniqId 商户自己内部ID，即字段中的sid
   * @param PoiId 微信的门店ID，微信内门店唯一标示ID
   * @param string Result 审核结果，成功succ 或失败fail
   * @param string msg 成功的通知信息，或审核失败的驳回理由
   */
  function poi_check_notify(\SimpleXMLElement $xml):void{

  }

  //}}}


  //{{{ 摇一摇事件 https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443448066


  /**
   * 用户进入摇一摇界面，在“周边”页卡下摇一摇时，微信会把这个事件推送到开发者填写的URL（登录公众平台进入开发者中心设置）。推送内容包含摇一摇时“周边”页卡展示出来的页面所对应的设备信息，以及附近最多五个属于该公众账号的设备的信息。
   * 当摇出列表时，此事件不推送。
   * @param ChosenBeacon->Uuid
   * @param ChosenBeacon->Major
   * @param ChosenBeacon->Minor
   * @param float ChosenBeacon->Distance 米
   * @param AroundBeacons->AroundBeacon->Uuid
   * @param AroundBeacons->AroundBeacon->Major
   * @param AroundBeacons->AroundBeacon->Minor
   * @param float AroundBeacons->AroundBeacon->Distance 米
   */
  function ShakearoundUserShake(\SimpleXMLElement $xml):void{

  }

  //}}}


  //{{{ 红包事件 

  /**
   * @param LotteryId 红包活动id
   * @param Ticket 红包ticket
   * @param float Money
   * @param int BindTime
   */
  function ShakearoundLotteryBind(\SimpleXMLElement $xml):void{

  }

  //}}}


  /**
   * @param int ConnectTime 连网时间（整型）
   * @param int ExpireTime 系统保留字段，固定值
   * @param VendorId 系统保留字段，固定值
   * @param ShopId 门店ID，即shop_id
   * @param DeviceNo 连网的设备无线mac地址，对应bssid
   */
  function WifiConnected(replay $replay):?\DOMDocument{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444894131

  }


  //{{{ 扫一扫事件 https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1455872179

  /**
   * 当用户打开商品主页，无论是通过扫码，还是从其他场景（会话、收藏或朋友圈）打开，微信均会推送该事件
   * @param KeyStandard 商品编码标准
   * @param KeyStr 商品编码内容
   * @param Country
   * @param Province
   * @param City
   * @param int Sex 1为男性，2为女性，0代表未知。
   * @param int Scene 打开商品主页的场景，1为扫码，2为其他打开场景（如会话、收藏或朋友圈）。
   * @param ExtInfo 调用“获取商品二维码接口”时传入的extinfo，为标识参数。
   */
  function user_scan_product(){

  }


  /**
   * 当用户从商品主页进入公众号会话时，微信会推送该事件到商户填写的URL。
   * 推送的内容包括用户的基本信息、时间、关注场景及对应的条码信息。
   * @param KeyStandard
   * @param KeyStr
   * @param ExtInfo 调用“获取商品二维码接口”时传入的extinfo，为标识参数。
   */
  function user_scan_product_enter_session(){

  }


  /**
   * 当用户打开商品主页，微信会将该用户实时的地理位置信息以异步事件的形式推送到商户填写的URL。
   * 商户可利用该信息做数据分析，形成差异化运营方案或指导生产。
   * 推送的地理位置信息为“省”一级，如广东省。由于用户的网速影响，异步推送的响应速度可能较慢。
   * @param KeyStandard
   * @param KeyStr
   * @param ExtInfo 调用“获取商品二维码接口”时传入的extinfo，为标识参数。
   * @param RegionCode 用户的实时地理位置信息（目前只精确到省一级），可在国家统计局网站查到对应明细： http://www.stats.gov.cn/tjsj/tjbz/xzqhdm/201504/t20150415_712722.html
   */
  function user_scan_product_async(){

  }


  /**
   * 提交审核的商品，完成审核后，微信会将审核结果以事件的形式推送到商户填写的URL。
   * @param KeyStandard
   * @param KeyStr
   * @param Result 审核结果。verify_ok表示审核通过，verify_not_pass表示审核未通过。
   * @param ReasonMsg 审核未通过的原因
   */
  function user_scan_product_verify_action(){

  }

  //}}}


  /**
   * 在用户授权同意发票存入自己微信账户后，商户可以收到授权完成的状态推送。
   * 可以将order_id连同开票信息一并发送给开票平台，以便开票平台在开票成功后将电子发票插入用户卡包。
   * @param string SuccOrderId 授权成功的订单号，与失败订单号两者必显示其一
   * @param string FailOrderId 授权失败的订单号，与成功订单号两者必显示其一
   * @param string AuthorizeAppId 获取授权页链接的AppId
   * @param string Source 授权来源，web：公众号开票，app：app开票，wxa：小程序开票，wap：h5开票
   */
  function user_authorize_invoice(){

  }


  /**
   * 用户将发票提交企业报销后，会将发票状态的变更情况推送给发票平台及商户，以便确保各方的发票状态同步
   * 发票平台或自建平台商户通过配置时间接收URL，解析推送事件类型，获得状态变更消息。
   * @param string Status 发票报销状态
   * @param string CardId 发票id
   * @param string Code 发票code
   */
  function update_invoice_status(){

  }


  /**
   * 用户提交发票抬头后，商户会收到用户提交的事件。
   * @param string title 发票抬头
   * @param string phone
   * @param string tax_no 税号
   * @param string addr
   * @param string bank_type 银行类型
   * @param string bank_no 银行号码？？
   * @param string attach 附加字段
   * @param string title_type "InvoiceUserTitlePersonType" "InvoiceUserTitleBusinessType"
   */
  function submit_invoice_title(){

  }
}
