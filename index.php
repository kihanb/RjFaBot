<?php
/*
 * RadioJavan Telegram Bot (Beta version!)
 * Coded By kihanb & kings afg
 * https://one-api.ir => Official Website
 */
ob_start();
error_reporting(0);
date_default_timezone_set('Asia/Tehran');
//-----------------------------------------
define('API_KEY',''); // bot api token ***
//-----------------------------------------
function Bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
       return $res;
    }
}
// All Functions
function SendMessage($chat_id, $text, $mode, $reply, $keyboard = null){
	Bot('SendMessage',['chat_id'=>$chat_id,'text'=>$text,'parse_mode'=>$mode,'reply_to_message_id'=>$reply,'reply_markup'=>$keyboard]);
}
function EditMsg($chatid, $msgid, $text, $mod, $keyboard = null){
    Bot('EditMessageText', ['chat_id'=>$chatid,'message_id'=>$msgid,'text'=>$text,'parse_mode'=>$mod,'reply_markup'=>$keyboard]);
}
function SendPhoto($chat_id, $photo, $keyboard, $caption , $rep){
	Bot('SendPhoto', ['chat_id' => $chat_id, 'photo' => $photo, 'caption' => $caption, 'reply_to_message_id'=>$rep, 'reply_markup' => $keyboard]);
}
function SendDocument($chat_id,$document,$caption,$mode,$keyboard){
    Bot('SendDocument',['chat_id'=>$chat_id,'document'=>$document,'caption'=>$caption,'parse_mode'=>$mode,'reply_markup'=>$keyboard]);
}
function Forward($chat_id,$from_id,$massege_id){
    Bot('ForwardMessage',['chat_id'=>$chat_id,'from_chat_id'=>$from_id,'message_id'=>$massege_id]);
}
function sendaction($chat_id, $action){
	Bot('sendchataction',['chat_id'=>$chat_id,'action'=>$action]);
}
function sendvideo($chat_id, $video, $cap, $mods, $key , $msg){
	Bot('sendvideo',['chat_id'=>$chat_id,'video'=>$video,'caption'=>$cap,'parse_mode'=>$mods,'reply_to_message_id'=>$msg,'reply_markup'=>$key]);
}
function getChatMember($channel, $id = ""){
    $forchannel = json_decode(file_get_contents("https://api.telegram.org/bot".API_KEY."/getChatMember?chat_id=@$channel&user_id=".$id));
    $tch = $forchannel->result->status;

     if($tch == 'member' or $tch == 'creator' or $tch == 'administrator'){
         return true;
     }else{
         return false;
     }
}
$gets = json_decode(file_get_contents('https://api.telegram.org/bot'.API_KEY.'/getMe'),true);
$bot_name = $gets['result']['first_name'];
$usernamebot = $gets['result']['username'];
//-----------------------------//
//متغیر ها :
$admin = 615724046; // put id of admins ***
$channel = "one_apis"; // put username of your channel ***
$token = ""; // put your token from one-api.ir ***

//-----------------------------//
$update = json_decode(file_get_contents('php://input'));
if(isset($update->message)){
    $message = $update->message;
    $inline = $update->inline_query;
    $inline_text = $update->inline_query->query;
    $membercalls = $update->inline_query->id;
	$text = $message->text;
	$tc = $message->chat->type;
    $chat_id = $message->chat->id;
	$from_id = $message->from->id;
	$message_id = $message->message_id;
    $first_name = $message->from->first_name;
    $last_name = $message->from->last_name;
    $username = $message->from->username;
}
if(isset($update->callback_query)){
    $callback_query = $update->callback_query;
	$data = $callback_query->data;
	$tc = $callback_query->message->chat->type;
    $chatid = $callback_query->message->chat->id;
	$fromid = $callback_query->from->id;
	$messageid = $callback_query->message->message_id;
    $firstname = $callback_query->from->first_name;
    $lastname = $callback_query->from->last_name;
    $cusername = $callback_query->from->username;
    $membercall = $callback_query->id;
}
if(isset($update->inline_query)){
    $inline = $update->inline_query;
    $inline_text = $inline->query;
    $membercalls = $inline->id;
    $id_from = $inline->from->id;
}
//-----------------------------------------
/*$get = Bot('GetChatMember',[
'chat_id'=>"@".$channel,
'user_id'=>$from_id]);
$rank = $get->result->status;*/
//-----------------------------------------
$menu = json_encode(['keyboard'=>[
    [['text'=>"🎵 جستجو آهنگ"],['text'=>"📽 جستجو موزیک ویدیو"]],
    [['text'=>"🔘 برترین ها"],['text'=>"🌟 جدیدترین ها"]],
    [['text'=>"📱 پخش انلاین موزیک"],['text'=>"📹 برترین موزیک ویدیو ها"]],
    ],'resize_keyboard'=>true
    ]);
$back = json_encode(['keyboard'=>[
    [['text'=>"🔙 بازگشت"]],
    ],'resize_keyboard'=>true
    ]);


if(isset($chatid)){
    $chat = $chatid;
}elseif(isset($chat_id)){
    $chat = $chat_id;
}
$stats = file_get_contents("data/$chat/stats.txt");
//-----------------------------------------
if(getChatMember($channel,$chat) == false){
    bot('SendMessage',[
        'chat_id'=>$chat,
        'text'=>"📣کاربر گرامی
جهت استفاده از خدمات این ربات، ابتدا در کانال ما عضو شوید:
        
@$channel

@$channel

سپس دستور /start را مجددا ارسال نمایید!",
        	 ]);
}elseif($text == "/start" or $text == "🔙" or $text == "🔙 بازگشت"){
    if(!file_exists("data/$chat_id/stats.txt")){
        mkdir("data/$chat_id");
        $myfile2 = fopen("data/users.txt", "a") or die("Unable to open file!");
        fwrite($myfile2, "$chat_id\n");
        fclose($myfile2);
    }
    file_put_contents("data/$chat_id/stats.txt","none");
        SendMessage($chat_id,"🎙سلام [$first_name] به ربات `$bot_name` خوش اومدی!

 با من میتونی هر آهنگ یا موزیک ویدئویی که میخوای جستجو و دانلود کنی، من کلی امکانات منحصر بفرد دارم که دانلود هرآهنگی رو برات آسون تر میکنه کافیه یکبار امتحانم کنی😍

🤖 @$usernamebot",'MarkDown',$message_id,$menu);

}elseif(strpos($text , '/start music_') !== false){
	if(!file_exists("data/$chat_id/stats.txt")){
        mkdir("data/$chat_id");
        $myfile2 = fopen("data/users.txt", "a") or die("Unable to open file!");
        fwrite($myfile2, "$chat_id\n");
        fclose($myfile2);
    }
    file_put_contents("data/$chat_id/stats.txt","none");
    $text = str_replace("/start music_","",$text);
    $musicapi = file_get_contents("https://one-api.ir/radiojavan/?token=$token&action=get_song&id=".urlencode($text));
    $d = json_decode($musicapi,true);
    $title = $d['result']['title'];
    $link = $d['result']['link'];
    $photo = $d['result']['photo'];
    $plays = $d['result']['plays'];
    $lyric = $d['result']['lyric'];
    $like = $d['result']['likes'];
    $dislike = $d['result']['dislikes'];
    $downloads = $d['result']['downloads'];
    SendPhoto($chat_id,$photo,NULL,"
🎧 $title\n📯 Plays : $downloads\n📥 Downloads : $downloads\n👍 $like / 👎 $dislike\n\n @$usernamebot
 ");
    SendDocument($chat_id,$link,"🎧 [جستجو موزیک](https://telegram.me/$usernamebot)",'MarkDown',$menu);
    if(!empty($lyric))
     bot('sendMessage',[
 'chat_id'=>$chatid,
 'text'=>"$lyric
 
 @RimonRobot",
 'parse_mode'=>"HTML",
	 ]);
}elseif($text == "📱 پخش انلاین موزیک"){

    Bot('SendMessage',['chat_id'=>$chat_id,'text'=>"🔘 شما میتوانید با استفاده از لینک پایین انلاین موزیک گوش کنید",'reply_to_message_id'=>$message_id,
    'reply_markup'=>json_encode(['inline_keyboard'=>[[['text'=>"🔸 پخش انلاین موزیک",'url'=>'http://208.85.241.142/']],]]) ]);
}

//-----------------------------//
// The Best
elseif($text == "🌟 جدیدترین ها"){
    sendaction($chat_id, typing);
    $rj = file_get_contents("https://one-api.ir/radiojavan/?token=$token&action=new_songs");
    $dj = json_decode($rj,true);
    for($i=0;$i<count($dj['result']);$i++){
        $a[$i] = $dj['result'][$i]['title'];
        $b = $dj['result'][$i]['id'];
        $keyboard[]= [['text'=>"🎧 "."$a[$i]"." 🎧",'callback_data'=>"id_".$b]];
    }
    Bot('SendMessage',['chat_id'=>$chat_id,'text'=>"🔻 جهت دانلود کردن هر اهنگ روی ان کلیک کنید",'reply_markup'=>json_encode(['inline_keyboard'=>$keyboard])]);
}
elseif($text == "🔘 برترین ها"){
    sendaction($chat_id, typing);
    $rj = file_get_contents("https://one-api.ir/radiojavan/?token=$token&action=hot_songs");
    $dj = json_decode($rj,true);
    for($i=0;$i<count($dj['result']);$i++){
        $a[$i] = $dj['result'][$i]['title'];
        $b = $dj['result'][$i]['id'];
        $keyboard[]= [['text'=>"🎧 "."$a[$i]"." 🎧",'callback_data'=>"id_".$b]];
    }
    Bot('SendMessage',['chat_id'=>$chat_id,'text'=>"🔻 جهت دانلود کردن هر اهنگ روی ان کلیک کنید",'reply_markup'=>json_encode(['inline_keyboard'=>$keyboard])]);
}
elseif($text == "📹 برترین موزیک ویدیو ها"){
    sendaction($chat_id, typing);
    $rj = file_get_contents("https://one-api.ir/radiojavan/?token=$token&action=hot_videos");
    $dj = json_decode($rj,true);
    for($i=0;$i<count($dj['result']);$i++){
        $a[$i] = $dj['result'][$i]['title'];
        $b = $dj['result'][$i]['id'];
        $keyboard[]= [['text'=>"🎧 "."$a[$i]"." 🎧",'callback_data'=>"idvideo_".$b]];
    }
    Bot('SendMessage',['chat_id'=>$chat_id,'text'=>"🔻 جهت دانلود کردن هر اهنگ روی ان کلیک کنید",'reply_markup'=>json_encode(['inline_keyboard'=>$keyboard])]);
}elseif($text == "🎵 جستجو آهنگ"){
    sendaction($chat_id, typing);
    SendMessage($chat_id,"🔎به بخش جستجو آهنگ خوش آمدید، شما میتوانید به یکی از دو روش زیر آهنگ موردنظر خود را جستجو و دریافت نمایید:

🖌 جستجوی متنی:
اسم آهنگ یا خواننده مورد نظرتون رو بصورت لاتین(انگلیسی) بفرستید.

@$usernamebot",'MarkDown',$message_id,$back);
    
    file_put_contents("data/$chat_id/stats.txt","search");
}elseif($stats == "search"){
    if(isset($text)){
        $rj = file_get_contents("https://one-api.ir/radiojavan/?token=$token&action=search&q=".urlencode($text));
        $dj = json_decode($rj,true);
        for($i=0;$i<count($dj['result']['mp3s']);$i++){
        $a[$i] = $dj['result']['mp3s'][$i]['title'];
        $b = $dj['result']['mp3s'][$i]['id'];
        $keyboard[]= [['text'=>"🎧 "."$a[$i]"." 🎧",'callback_data'=>"id_".$b]];
        }
        if($a[0] != null){
            sendaction($chat_id, typing);
            Bot('SendMessage',['chat_id'=>$chat_id,'text'=>"🔎نتایج جستجو برای $text :",'reply_markup'=>json_encode(['inline_keyboard'=>$keyboard])]);
        }else{
            sendaction($chat_id, typing);
            SendMessage($chat_id,"🔎 متاسفانه هیچ نتیجه ای برای $text درسایت رادیو جوان پیدا نشد!",null,$message_id,$back);
        }
    }
            unlink("data/$chat_id/$rand.ogg");
}elseif($text == "📽 جستجو موزیک ویدیو"){
    sendaction($chat_id, typing);
    SendMessage($chat_id,"🔘 لطفا نام خواننده یا موزیک رو به انگلیسی بفرستید :",'MarkDown',$message_id,$back);
    file_put_contents("data/$chat_id/stats.txt","search_video");
}
elseif($stats == "search_video"){
    $rj = file_get_contents("https://one-api.ir/radiojavan/?token=$token&action=search&q=".urlencode($text));
    $dj = json_decode($rj,true);
    for($i=0;$i<count($dj['result']['videos']);$i++){
        $a[$i] = $dj['result']['videos'][$i]['title'];
        $b = $dj['result']['videos'][$i]['id'];
        $keyboard[]= [['text'=>"🎧 "."$a[$i]"." 🎧",'callback_data'=>"idvideo_".$b]];
    }
    if($a[0] != null){
        sendaction($chat_id, typing);
        Bot('SendMessage',['chat_id'=>$chat_id,'text'=>"🔻 جهت دانلود کردن هر موزیک ویدیو روی ان کلیک کنید",'reply_markup'=>json_encode(['inline_keyboard'=>$keyboard])]);
    }else{
       sendaction($chat_id, typing);
       SendMessage($chat_id,"🔎 متاسفانه هیچ نتیجه ای پیدا نشد.",null,$message_id,$back);
    }
}

//-----------------------------//
// Downloads
if(strpos($data,"id_") !== false){
    file_put_contents("data/$chatid/stats.txt","none");
    $id = str_replace('id_',null,$data);
    sendaction($chatid, typing);
    $musicapi = file_get_contents("https://one-api.ir/radiojavan/?token=$token&action=get_song&id=".$id);
    $d = json_decode($musicapi,true);
    $title = $d['result']['title'];
    $link = $d['result']['link'];
    $photo = $d['result']['photo'];
    $plays = $d['result']['plays'];
    $lyric = $d['result']['lyric'];
    $like = $d['result']['likes'];
    $dislike = $d['result']['dislikes'];
    $downloads = $d['result']['downloads'];
    EditMsg($chatid,$messageid,"🔘 درحال ارسال موزیک  ...",null);
    SendPhoto($chatid,$photo,$bishtar,"
🎧 $title\n📯 Plays : $downloads\n📥 Downloads : $downloads\n👍 $like / 👎 $dislike\n\n @$usernamebot
 ",$message_id);
    SendDocument($chatid,$link,"🎧 [جستجو موزیک](https://telegram.me/$usernamebot)",'MarkDown',$menu);
    file_put_contents("data/$chat_id/stats.txt","none");
     if(!empty($lyric))
     bot('sendMessage',[
 'chat_id'=>$chatid,
 'text'=>"$lyric
 
 @RimonRobot",
 'parse_mode'=>"HTML",
	 ]);
}
if(strpos($data,"idvideo_") !== false){
    file_put_contents("data/$chatid/stats.txt","none");
    $id = str_replace('idvideo_',"",$data);
    sendaction($chatid, typing);
    $d = json_decode(file_get_contents("https://one-api.ir/radiojavan/?token=$token&action=get_video&id=".$id),true);
    $title = $d['result']['title'];
    $photo = $d['result']['photo'];
    $like = $d['result']['likes'];
    $dislike = $d['result']['dislikes'];
    $view = $d['result']['views'];
    $create = $d['result']['created_at'];
    $dates = explode('T',$create)[0];
    $link = $d['result']['low'];
    $attach = $d['result']['high'];
    EditMsg($chatid,$messageid,"🔘 درحال ارسال موزیک ویدیو  ...",null);
    Bot('sendphoto',['chat_id'=>$chatid,'photo'=>$photo,'caption'=>"
🎧 $title\n📯 Plays : $view\n👍 $like / 👎 $dislike\n\n @$usernamebot",'parse_mode'=>"MarkDown"]);
    Bot('sendvideo',['chat_id'=>$chatid,'video'=>$link,'caption'=>"🎧 [جستجو موزیک و موزیک ویدیو](https://telegram.me/$usernamebot)",'parse_mode'=>"MarkDown",'reply_markup'=>$menu]);
	
    file_put_contents("data/$chat_id/stats.txt","none");

}
if($chat_id == $admin){
if($text=="/panel"){
    file_put_contents("data/$chat_id/stats.txt","admin");
    bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"ادمین عزیز به پنل مدیریت ربات خوش آمدید💎",
  'reply_markup'=>json_encode([
                      'keyboard'=>[
  [['text'=>"آمار ربات"]],
  [['text'=>"️💠فروارد همگانی💠"],['text'=>"💠ارسال همگانی💠"]],
  [['text'=>"🔙"]],
	],
		"resize_keyboard"=>true,
	 ])
	 ]);
}
elseif($text=="آمار ربات" ){
   $txtt = file_get_contents('data/users.txt');
    $member_id = explode("\n",$txtt);
    $amar = count($member_id) -1;
    $tc= file_get_contents('data/channels.txt');
    $mc = explode("\n",$tc);
    $amarc = count($mc) -1;
     bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>"Users: <code>$amar</code>",
 'parse_mode'=>"HTML",
  ]);
}

if($text=="💠ارسال همگانی💠" ){     
     file_put_contents("data/$chat_id/stats.txt","send2all");
      bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>"برای ارسال به همه اعضا لطفا پیام خود را ارسال کنید💣",
 'parse_mode'=>"MarkDown",
  ]);
}  
elseif($stats == "send2all" ){  
    file_put_contents("data/$chat_id/stats.txt","none");
    $text1 = $message->text;
    $all_member = fopen( "data/users.txt", 'r');
		while( !feof( $all_member)) {
 			$user = fgets( $all_member);
 	}
  bot('sendMessage',[
 'chat_id'=>$user,
 'text'=>$text1,
 'parse_mode'=>"MarkDown",
  ]);
  bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>"ok",
 'parse_mode'=>"MarkDown",
  ]);
  }
elseif($text=="️💠فروارد همگانی💠" ){           
     file_put_contents("data/$chat_id/stats.txt","sef2all");
      bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>"برای فروارد به همه اعضا لطفا پیام خود را فروارد کنید💣",
 'parse_mode'=>"MarkDown",
  ]);
}  
elseif($stats == "sef2all"){  
    file_put_contents("data/$chat_id/stats.txt","none");
    $all_member = fopen( "data/users.txt", 'r');
		while( !feof( $all_member)) {
 			$user = fgets( $all_member);
Bot('ForwardMessage',['chat_id'=>$user,'from_chat_id'=>$chat_id,'message_id'=>$message->message_id]);
		}
		bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>"ok",
 'parse_mode'=>"MarkDown",
  ]);
		}
}    
?>
