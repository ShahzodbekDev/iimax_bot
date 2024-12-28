<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ini_set('max_execution_time', '300'); // 300 soniya
// ini_set('memory_limit', '256M'); // 256 MB


#================================================

define('API_KEY', '7322994237:AAH_W8ftU8-PiRSUbQm4kngyQCAlu-uq-mE');

// $userbot = 'Kinolar_Olami_3bot';
// $shahzoddev = 7456616215;
// $owners = array($shahzoddev);
// $user = "HusniddinLatipov";

$userbot = 'maxfilmuz_bot';
$shahzoddev = 1052735875;
$owners = array($shahzoddev);
$user = "freelance_sh";

define('DB_HOST', 'localhost');
define('DB_USER', 'shahzodbekdev_imaxkino');
define('DB_PASS', 'imaxkino');
define('DB_NAME', 'shahzodbekdev_imaxkino');


$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($connect, 'utf8mb4');

function bot($method, $datas = [])
{
	$url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
	$res = curl_exec($ch);
	if (curl_error($ch))
		var_dump(curl_error($ch));
	else
		return json_decode($res);
}






#================================================

function sendMessage($id, $text, $key = null)
{
	return bot('sendMessage', [
		'chat_id' => $id,
		'text' => $text,
		'parse_mode' => 'html',
		'disable_web_page_preview' => true,
		'reply_markup' => $key
	]);
}

function editMessageText($cid, $mid, $text, $key = null)
{
	return bot('editMessageText', [
		'chat_id' => $cid,
		'message_id' => $mid,
		'text' => $text,
		'parse_mode' => 'html',
		'disable_web_page_preview' => true,
		'reply_markup' => $key
	]);
}

function sendVideo($cid, $f_id, $text, $key = null)
{
	return bot('sendVideo', [
		'chat_id' => $cid,
		'video' => $f_id,
		'caption' => $text,
		'parse_mode' => 'html',
		'reply_markup' => $key
	]);
}

function sendPhoto($cid, $f_id, $text, $key = null)
{
	return bot('sendPhoto', [
		'chat_id' => $cid,
		'photo' => $f_id,
		'caption' => $text,
		'parse_mode' => 'html',
		'reply_markup' => $key
	]);
}

function copyMessage($id, $from_chat_id, $message_id)
{
	return bot('copyMessage', [
		'chat_id' => $id,
		'from_chat_id' => $from_chat_id,
		'message_id' => $message_id
	]);
}

function forwardMessage($id, $cid, $mid)
{
	return bot('forwardMessage', [
		'from_chat_id' => $id,
		'chat_id' => $cid,
		'message_id' => $mid
	]);
}

function deleteMessage($cid, $mid)
{
	return bot('deleteMessage', [
		'chat_id' => $cid,
		'message_id' => $mid
	]);
}

function getChatMember($cid, $userid)
{
	return bot('getChatMember', [
		'chat_id' => $cid,
		'users' => $userid
	]);
}

function replyKeyboard($key)
{
	return json_encode(['keyboard' => $key, 'resize_keyboard' => true]);
}

function getName($id)
{
	$getname = bot('getchat', ['chat_id' => $id])->result->first_name;
	if (!empty($getname)) {
		return $getname;
	} else {
		return bot('getchat', ['chat_id' => $id])->result->title;
	}
}

function joinchat($id)
{
	$array = array("inline_keyboard");
	$kanallar = file_get_contents("admin/kanal.txt");
	if ($kanallar == null) {
		return true;
	} else {
		$ex = explode("\n", $kanallar);
		for ($i = 0; $i <= count($ex) - 1; $i++) {
			$first_line = $ex[$i];
			$first_ex = explode("@", $first_line);
			$url = $first_ex[1];
			$ism = bot('getChat', ['chat_id' => "@" . $url])->result->title;
			$ret = bot("getChatMember", [
				"chat_id" => "@$url",
				"users" => $id,
			]);
			$stat = $ret->result->status;
			if ((($stat == "creator" or $stat == "administrator" or $stat == "member"))) {
				$array['inline_keyboard']["$i"][0]['text'] = "✅ " . $ism;
				$array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$url";
			} else {
				$array['inline_keyboard']["$i"][0]['text'] = "❌ " . $ism;
				$array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$url";
				$uns = true;
			}
		}
		$array['inline_keyboard']["$i"][0]['text'] = "⏭️ Tekshirish";
		$array['inline_keyboard']["$i"][0]['callback_data'] = "check";
		if ($uns == true) {
			sendMessage($id, "🚀 <b>Botdan to'liq foydalanish uchun quyidagi kanallarimizga obuna bo'ling!</b>", json_encode($array));
			return false;
		} else {
			return true;
		}
	}
}

#================================================

date_Default_timezone_set('Asia/Tashkent');
$soat = date('H:i');
$sana = date("d.m.Y");

#================================================

$update = json_decode(file_get_contents('php://input'));

$message = $update->message;
$callback = $update->callback_query;

if (isset($message)) {
	$cid = $message->chat->id;
	$Tc = $message->chat->type;

	$text = $message->text;
	$mid = $message->message_id;

	$from_id = $message->from->id;
	$name = $message->from->first_name;
	$last = $message->from->last_name;

	$photo = $message->photo[count($message->photo) - 1]->file_id;

	$video = $message->video;
	$file_id = $video->file_id;
	$file_name = $video->file_name;
	$file_size = $video->file_size;
	$size = $file_size / 1000;
	$dtype = $video->mime_type;

	$audio = $message->audio->file_id;
	$voice = $message->voice->file_id;
	$sticker = $message->sticker->file_id;
	$video_note = $message->video_note->file_id;
	$animation = $message->animation->file_id;

	$caption = $message->caption;
}

if (isset($callback)) {
	$data = $callback->data;
	$qid = $callback->id;

	$cid = $callback->message->chat->id;
	$Tc = $callback->message->chat->type;
	$mid = $callback->message->message_id;

	$from_id = $callback->from->id;
	$name = $callback->from->first_name;
	$last = $callback->from->last_name;
}

#=================================================

mkdir("admin");

$kino_id = file_get_contents("admin/kino.txt");
$kino = bot('getchat', ['chat_id' => $kino_id])->result->username;
$reklama = str_replace(["%kino%", "%admin%"], [$kino, $user], file_get_contents("admin/rek.txt"));

#================================================

$admins = explode("\n", file_get_contents("admin/admins.txt"));
if (is_array($admins))
	$admin = array_merge($owners, $admins);
else
	$admin = $owners;

#=================================================

$user = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = $cid"));
$users = $user['users'];
$step = $user['step'];
$ban = $user['ban'];
$lastmsg = $user['lastmsg'];

#=================================================

if ($ban == 1)
	exit();

if (isset($message)) {
	if (!$connect) {
		sendMessage($cid, "⚠️ <b>Ma'lumotlar olishda xatolik!</b>\n\n<i>Iltimos tezroq adminga xabar bering.</i>");
		return false;
	}
}

mysqli_query($connect, "CREATE TABLE data(
`id` int(20) auto_increment primary key,
`file_name` varchar(256),
`file_id` varchar(256),
`film_name` varchar(256),
`film_date` varchar(256)
)");

mysqli_query($connect, "CREATE TABLE settings(
`id` int(20) auto_increment primary key,
`kino` varchar(256),
`kino2` varchar(256)
)");

mysqli_query($connect, "CREATE TABLE users(
`uid` int(20) auto_increment primary key,
`id` varchar(256),
`step` varchar(256),
`ban` varchar(256),
`lastmsg` varchar(256),
`sana` varchar(256)
)");


if ($Tc == "private") {
	$result = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = $cid");
	$rew = mysqli_fetch_assoc($result);
	if ($rew) {
		mysqli_query($connect, "UPDATE users SET sana = '$sana | $soat' WHERE id = $cid");
	} else {
		mysqli_query($connect, "INSERT INTO `users`(`id`,`step`,`sana`,`ban`) VALUES ('$cid','0','$sana | $soat','0')");
	}
}

if ($message) {
	$result = mysqli_query($connect, "SELECT * FROM `settings`");
	$rew = mysqli_fetch_assoc($result);
	if ($rew) {
	} else {
		mysqli_query($connect, "INSERT INTO `settings`(`kino`,`kino2`) VALUES ('0','0')");
	}
}

#=================================================

$panel = replyKeyboard([
	[['text' => "📊 Statistika"]],
	[['text' => "🎬 Kino qo'shish"], ['text' => "🗑️ Kino o'chirish"]],
	[['text' => "👨‍💼 Adminlar"], ['text' => "💬 Kanallar"]],
	[['text' => "🔴 Blocklash"], ['text' => "🟢 Blockdan olish"]],
	[['text' => "✍️ Post xabar"], ['text' => "📬 Forward xabar"]],
	[['text' => "🚪 Paneldan chiqish"]]
]);

$cancel = replyKeyboard([
	[['text' => "◀️ Orqaga"]]
]);

$kanallar_p = replyKeyboard([
	[['text' => "🔷 Kanal ulash"], ['text' => "🔶 Kanal uzish"]],
	[['text' => "💡 Kino kanal"], ['text' => "📈 Reklama"]],
	[['text' => "🟩 Majburish a'zolik"]],
	[['text' => "◀️ Orqaga"]]
]);


$removeKey = json_encode(['remove_keyboard' => true]);

#=================================================

if ($text == "/start" and joinchat($cid) == true) {
	$keyBot = json_encode([
		'inline_keyboard' => [
			[['text' => "🎞️ Kodlarni qidirish", 'url' => "https://t.me/$kino"]]
		]
	]);
	sendMessage($cid, "👋 <b>Assalomu aleykum $name!</b>\n\n<i>🍿 Botimiz orqali siz o‘zingizga kerakli kinoni yuklab ko‘rishingiz mumkin.</i>\n\n<b>📊 Bot buyruqlari:</b>\n/start - Botni yangilash ♻️\n/rand - Tasodifiy film 🍿\n/dev - Bot dasturchisi 👨‍💻\n/help - Bot qo‘llanmasi 📑\n\n<b>#️⃣ Kinoni kod orqali yuklashingiz ham mumkin. Marhamat, kino kodini yuboring:</b>", $keyBot);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'start' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	exit();
} else if ($data == "check") {
	deleteMessage($cid, $mid);
	$keyBot = json_encode([
		'inline_keyboard' => [
			[['text' => "🎞️ Kodlarni qidirish", 'url' => "https://t.me/$kino"]]
		]
	]);
	if (joinchat($cid) == true) {
		sendMessage($cid, "👋 <b>Assalomu aleykum $name!</b>\n\n<i>🍿 Botimiz orqali siz o‘zingizga kerakli kinoni yuklab ko‘rishingiz mumkin.</i>\n\n<b>📊 Bot buyruqlari:</b>\n/start - Botni yangilash ♻️\n/rand - Tasodifiy film 🍿\n/dev - Bot dasturchisi 👨‍💻\n/help - Bot qo‘llanmasi 📑\n\n<b>#️⃣ Kinoni kod orqali yuklashingiz ham mumkin. Marhamat, kino kodini yuboring:</b>", $keyBot);
		mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'start' WHERE `id` = $cid");
		mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	}
}


$botdel = $update->my_chat_member->new_chat_member;
$botdelid = $update->my_chat_member->from->id;
$userstatus = $botdel->status;

if ($botdel) {
	if ($userstatus == "kicked") {
		mysqli_query($connect, "UPDATE users SET sana = 'tark' WHERE id = $botdelid");
	}
}
#=================================================

if ($text == "/dev" and joinchat($cid) == true) {
	$keyBot = json_encode([
		'inline_keyboard' => [
			[['text' => "👨‍💻 Bot dasturchisi", 'url' => "https://t.me/BuilderNet"]],
			[['text' => "🔁 Boshqa botlar", 'url' => "https://t.me/UzBotsTG"]],
		]
	]);
	sendMessage($cid, "👨‍💻 <b>Botimiz dasturchisi: @BuilderNet</b>\n\n<i>🤖 Sizga ham shu kabi botlar kerak bo‘lsa bizga buyurtma berishingiz mumkin. Sifatli botlar tuzib beramiz.</i>\n\n<b>📊 Na’munalar:</b> @ULoyihalar", $keyBot);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'start' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	exit();
}

if ($text == "/help" and joinchat($cid) == true) {
	$keyBot = json_encode([
		'inline_keyboard' => [
			[['text' => "🔎 Kino kodlarini qidirish", 'url' => "https://t.me/$kino"]]
		]
	]);
	sendMessage($cid, "<b>📊 Botimiz buyruqlari:</b>\n/start - Botni yangilash ♻️\n/rand - Tasodifiy film 🍿\n/dev - Bot dasturchisi 👨‍💻\n/help - Bot buyruqlari 🔁\n\n<b>🤖 Ushbu bot orqali kinolarni osongina qidirib topishingiz va yuklab olishingiz mumkin. Kinoni yuklash uchun kino kodini yuborishingiz kerak. Barcha kino kodlari pastdagi kanalda jamlangan.</b>", $keyBot);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'start' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	exit();
} else if (($text == "/panel" or $text == "/a" or $text == "/admin" or $text == "/p" or $text == "◀️ Orqaga") and in_array($cid, $admin)) {
	sendMessage($cid, "<b>👨🏻‍💻 Boshqaruv paneliga xush kelibsiz.</b>\n\n<i>Nimani o'zgartiramiz?</i>", $panel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'panel' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	unlink("film.txt");
	exit();
} else if ($text == "🚪 Paneldan chiqish" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>🚪 Panelni tark etdingiz unga /panel yoki /admin xabarini yuborib kirishingiz mumkin.\n\nYangilash /start</b>", $removeKey);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'start' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	exit();
} else if ($text == "🎬 Kino qo'shish" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>🎬 Kino qo'shish uchun menga kino nomini yuboring:</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'addMovie' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = 'movie.add' WHERE `id` = $cid");
	exit();
}

if ($step == "movie.add") {
	file_put_contents("film.txt", $text);
	sendMessage($cid, "<b>🎬 Kinoni yuboring:</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `step` = 'movie-add' WHERE `id` = $cid");
} else if (isset($video) and $step == "movie-add") {
	if (in_array($cid, $admin)) {
		$file_id = $video->file_id;
		$file_name = $video->file_name;
		$file_size = $video->duration;
		$sana = date("d.m.Y");
		$result = mysqli_query($connect, "SELECT * FROM `data` WHERE `file_id` = '$file_id'");
		$row = mysqli_fetch_assoc($result);
		;
		if (!$row) {
			$cod = mysqli_query($connect, "SELECT * FROM `data`");
			$cod = mysqli_query($connect, "SELECT * FROM `settings` WHERE `id` = '1'");
			$codes = mysqli_fetch_assoc($cod)['kino'];
			$code = $codes + 1;
			$capti = file_get_contents("film.txt");
			$filmname = base64_encode($capti);
			if ($file_id != null and $file_name != null and $filmname != null and $code != null) {
				mysqli_query($connect, "INSERT INTO `data`(`file_name`,`file_id`,`film_name`,`film_date`) VALUES ('$file_name','$file_id','$filmname','$sana')");
				mysqli_query($connect, "UPDATE `settings` SET `kino` = '$code' WHERE `id` = '1'");
				sendMessage($cid, "<b>✅ Bazaga muvaffaqiyatli joylandi!\n\n🎞️ Kino nomi:</b> $capti\n\n<b>🍿 Kino kodi:</b> <code>$code</code>", json_encode([
					'inline_keyboard' => [
						[['text' => "🎞️ Kanalga yuborish", 'callback_data' => "channel=$code"]]
					]
				]));
				unlink("film.txt");
				exit();
			} else {
				sendMessage($cid, " <b>Serverda muammo!</b>");
				exit();
			}
		} else {
			sendMessage($cid, "$file_name <b>bizning bazada mavjud</b>\n\nQayta urinib ko'ring:");
			exit();
		}
	}
}

if (mb_stripos($data, "channel=") !== false) {
	$id = explode("=", $data)[1];
	sendMessage($cid, "<b>🎞️ Kino uchun rasm (thumbnail) yuboring:</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `step` = 'movie-send=$id' WHERE `id` = $cid");
	exit();
}

if (isset($message->photo) and mb_stripos($step, "movie-send=") !== false) {
	$id = explode("=", $step)[1];
	$res = mysqli_query($connect, "SELECT * FROM `data` WHERE `id` = '$id'");
	$row = mysqli_fetch_assoc($res);
	$fname = $row['film_name'];
	$fdate = $row['film_date'];
	$fname = base64_decode($fname);
	$code = $id;
	sendPhoto($cid, $photo, "
🎞 <b>Film nomi:</b> $fname
📀 <b>Film formati:</b> MP4
📅 <b>Film yuklangan:</b> $fdate

🍿 <b>Kino kodi: <code>$code</code>

📥 Yuklab olish:</b> @$userbot", json_encode([
			'inline_keyboard' => [
				[['text' => "✅ Kanalga yuborish", 'callback_data' => "ch=$code"]]
			]
		]));
	file_put_contents("film.txt", $photo);
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	exit();
}

if (mb_stripos($data, "ch=") !== false) {
	$id = explode("=", $data)[1];
	$photo = file_get_contents("film.txt");
	$res = mysqli_query($connect, "SELECT * FROM `data` WHERE `id` = '$id'");
	$row = mysqli_fetch_assoc($res);
	$fname = $row['film_name'];
	$fdate = $row['film_date'];
	$fname = base64_decode($fname);
	$code = $id;
	deleteMessage($cid, $mid);
	$mes = sendPhoto("@$kino", $photo, "
🍿 <b>Yangi film botga joylandi!</b>

🎞 <b>Filmning nomi:</b> $fname
📅 <b>Yuklangan sanasi:</b> $fdate

🔢 <b>Yuklash kodi: <code>$code</code>

‼️ Bot manzili:</b> @$userbot", json_encode([
			'inline_keyboard' => [
				[['text' => "👀 Kinoni ko‘rish 📥", 'url' => "https://t.me/$userbot?start=$code"]]
			]
		]))->result->message_id;
	sendMessage($cid, "✅ <b>@$kino kanaliga yuborildi!\n\n👀 <a href='https://t.me/$kino/$mes'>Ko‘rish</a></b>", $panel);
	exit();
}

if (mb_stripos($text, "/set ") !== false) {
	$ex = explode(" ", $text)[1];
	mysqli_query($connect, "UPDATE `settings` SET `kino` = '$ex' WHERE `id` = '1'");
}
if (mb_stripos($text, "/set2 ") !== false) {
	$ex = explode(" ", $text)[1];
	mysqli_query($connect, "UPDATE `settings` SET `kino2` = '$ex' WHERE `id` = '1'");
} else if ($text == "🗑️ Kino o'chirish" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>🗑️ Kino o'chirish uchun menga kino kodini yuboring:</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'deleteMovie' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = 'movie-remove' WHERE `id` = $cid");
	exit();
} else if (($step == "movie-remove" and $text != "🗑️ Kino o'chirish") and in_array($cid, $admin)) {
	$res = mysqli_query($connect, "SELECT * FROM `data` WHERE `id` = '$text'");
	$row = mysqli_fetch_assoc($res);
	$uz = mysqli_query($connect, "SELECT * FROM `settings` WHERE `id` = '1");
	$bek = mysqli_fetch_assoc($res);
	$ex = $bek['kino2'];
	$del = $ex + 1;
	if ($row) {
		mysqli_query($connect, "DELETE FROM `data` WHERE `id` = $text");
		sendMessage($cid, "🗑️ $text <b>raqamli kino olib tashlandi!</b>");
		mysqli_query($connect, "UPDATE `settings` SET `kino2` = '$del' WHERE `id` = '1'");
		mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
		exit();
	} else {
		sendMessage($cid, "📛 $text <b>mavjud emas!</b>\n\n🔄 Qayta urinib ko'ring:");
		exit();
	}
} else if ($text == "💡 Kino kanal" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>💡 Kino kanal havolasini yuboring!\n\nNa'muna: @ULoyihalar</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'movie_chan' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = 'movie_chan' WHERE `id` = $cid");
	exit();
} else if (($step == "movie_chan" and $text != "💡 Kino kanal") and in_array($cid, $admin)) {
	$nn_id = bot('getchat', ['chat_id' => $text])->result->id;
	sendMessage($cid, "<b>✅ $text (" . str_replace('-100', '', $nn_id) . ") ga o‘zgartirildi.</b>", $panel);
	file_put_contents("admin/kino.txt", $nn_id);
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
} else if ($text == "📈 Reklama" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>📈 Reklamani yuboring!\n\nNa'muna:</b> <pre>@%kino% kanali uchun maxsus joylandi!</pre>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'ads_set' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = 'ads_set' WHERE `id` = $cid");
	exit();
} else if (($step == "ads_set" and $text != "📈 Reklama") and in_array($cid, $admin)) {
	sendMessage($cid, "<b>✅ $text ga o'zgartirildi.</b>", $panel);
	file_put_contents("admin/rek.txt", $text);
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
} else if ($text == "💬 Kanallar" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>🔰 Kanallar bo'limi:\n🆔 Admin: $cid</b>", $kanallar_p);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'channels' WHERE `id` = $cid");
	exit();
} else if ($text == "🔷 Kanal ulash" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>Kanal ulash uchun kanal havolasini yuboring.\n\nNa'muna: @finecoders</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'channelsAdd' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = 'channel-add' WHERE `id` = $cid");
	exit();
} else if (($step == "channel-add" and $text != "🔷 Kanal ulash") and in_array($cid, $admin)) {
	sendMessage($cid, "<b>✅ Kanallar ulandi!</b>", $panel);
	if (file_get_contents("admin/kanal.txt")) {
		$text = "\n" . $text;
	} else {
		$text = $text;
	}
	file_put_contents("admin/kanal.txt", $text);
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	exit();
} else if ($text == "🔶 Kanal uzish" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>✅ Kanallar uzildi.</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'deleteChan' WHERE `id` = $cid");
	unlink("admin/kanal.txt");
	exit();
} else if ($text == "🟩 Majburish a'zolik" and in_array($cid, $admin)) {
	bot('sendMessage', [
		'chat_id' => $cid,
		'text' => "<b>🟩 Majburish a'zolik kanallari:</b>\n\n" . file_get_contents("admin/kanal.txt"),
		'parse_mode' => 'html',
		'reply_markup' => $cancel
	]);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'channels' WHERE `id` = $cid");
	exit();
} else if ($text == "🔴 Blocklash" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>Foydalanuvchi ID raqamini kiriting:</b>\n\n<i>M-n: $cid</i>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'addblock' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = 'blocklash' WHERE `id` = $cid");
	exit();
} else if (($step == "blocklash" and $text != "🔔 Blocklash") and in_array($cid, $admin)) {
	sendMessage($cid, "<b>✅ $text blocklandi!</b>", $panel);
	mysqli_query($connect, "UPDATE `users` SET `ban` = 1 WHERE `id` = $text");
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	exit();
} else if ($text == "🟢 Blockdan olish" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>Foydalanuvchi ID raqamini kiriting:</b>\n\n<i>M-n: $cid</i>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'deleteBlock' WHERE 	 = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = 'blockdanolish' WHERE `id` = $cid");
	exit();
} else if (($step == "blockdanolish" and $text != "🔕 Blockdan olish") and in_array($cid, $admin)) {
	sendMessage($cid, "<b>✅ $text blockdan olindi!</b>", $panel);
	mysqli_query($connect, "UPDATE `users` SET `ban` = 0 WHERE `id` = $text");
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	exit();
} else if ($text == "✍️ Post xabar" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>Xabaringizni yuboring:</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'post_msg' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = 'post_send' WHERE `id` = $cid");
	exit();
} else if (($step == "post_send" and $text != "✍️ Post xabar") and in_array($cid, $admin)) {
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	$msg = sendMessage($cid, "✅ <b>Xabar yuborish boshlandi!</b>", $panel)->result->message_id;
	$yuborildi = 0;
	$yuborilmadi = 0;
	$result = mysqli_query($connect, "SELECT * FROM `users`");
	while ($row = mysqli_fetch_assoc($result)) {
		$id = $row['id'];
		$ok = copyMessage($id, $cid, $mid)->ok;
		if ($ok == true)
			$yuborildi++;
		else
			$yuborilmadi++;
		if (!$ok) {
			mysqli_query($connect, "UPDATE users SET sana = 'tark' WHERE id = $id");
		}
		editMessageText($cid, $msg, "✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga");
	}
	deleteMessage($cid, $msg);
	sendMessage($cid, "💡 <b>Xabar yuborish tugatildi.\n\n</b>✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga\n\n<b>⏰ Soat: $soat | 📆 Sana: $sana</b>", $panel);
	mysqli_query($connect, "UPDATE users SET sana = 'tark' WHERE id = $botdelid");
} else if ($text == "📬 Forward xabar" and in_array($cid, $admin)) {
	sendMessage($cid, "<b>Xabaringizni yuboring:</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'post_msg' WHERE `id` = $cid");
	mysqli_query($connect, "UPDATE `users` SET `step` = 'forward_send' WHERE `id` = $cid");
	exit();
} else if (($step == "forward_send" and $text != "📬 Forward xabar") and in_array($cid, $admin)) {
	mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
	$msg = sendMessage($cid, "✅ <b>Xabar yuborish boshlandi!</b>", $panel)->result->message_id;
	$result = mysqli_query($connect, "SELECT * FROM `users`");
	$yuborildi = 0;
	$yuborilmadi = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$id = $row['id'];
		$ok = forwardMessage($cid, $id, $mid)->ok;
		if ($ok == true)
			$yuborildi++;
		else
			$yuborilmadi++;
		editMessageText($cid, $msg, "✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga");
	}
	if (!$ok) {
		mysqli_query($connect, "UPDATE users SET sana = 'tark' WHERE id = $id");
	}
	deleteMessage($cid, $msg);
	sendMessage($cid, "💡 <b>Xabar yuborish tugatildi.\n\n</b>✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga\n\n<b>⏰ Soat: $soat | 📆 Sana: $sana</b>", $panel);
} else if ($text == "📊 Statistika") {
	$res = mysqli_query($connect, "SELECT * FROM `users`");
	$us = mysqli_num_rows($res);
	$resp = mysqli_query($connect, "SELECT * FROM `users` WHERE `sana` = 'tark'");
	$tark = mysqli_num_rows($resp);
	$active = $us - $tark;
	$res = mysqli_query($connect, "SELECT * FROM `data`");
	$kin = mysqli_num_rows($res);
	$ping = sys_getloadavg()[2];
	$cod = mysqli_query($connect, "SELECT * FROM `settings` WHERE `id` = '1'");
	$roow = mysqli_fetch_assoc($cod);
	$code = $roow['kino'];
	$deleted = $roow['kino2'];
	sendMessage($cid, "
💡 <b>O'rtacha yuklanish:</b> <code>$ping</code>

• <b>Jami a’zolar:</b> $us ta
• <b>Tark etgan a’zolar:</b> $tark ta
• <b>Faol a’zolar:</b> $active ta
—————————————
• <b>Faol kinolar:</b> $kin ta
• <b>O‘chirilgan kinolar:</b> $deleted ta
• <b>Barcha kinolar:</b> $code ta");
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'stat' WHERE `id` = $cid");
	exit();
} else if (($text == "👨‍💼 Adminlar" or $data == "admins") and in_array($cid, $admin)) {
	if (isset($data))
		deleteMessage($cid, $mid);
	$keyBot = json_encode([
		'inline_keyboard' => [
			[['text' => "➕ Yangi admin qo'shish", 'callback_data' => "add-admin"]],
			[['text' => "📑 Ro'yxat", 'callback_data' => "list-admin"], ['text' => "🗑 O'chirish", 'callback_data' => "remove"]],
		]
	]);
	sendMessage($cid, "👇🏻 <b>Quyidagilardan birini tanlang:</b>", $keyBot);
	mysqli_query($connect, "UPDATE `users` SET `lastmsg` = 'admins' WHERE `id` = $cid");
	exit();
} else if ($data == "list-admin") {
	$admins = file_get_contents("admin/admins.txt");
	$keyBot = json_encode([
		'inline_keyboard' => [
			[['text' => "◀️ Orqaga", 'callback_data' => "admins"]],
		]
	]);
	editMessageText($cid, $mid, "<b>👮 Adminlar ro'yxati:</b>\n\n$admins", $keyBot);
} else if ($data == "add-admin") {
	deleteMessage($cid, $mid);
	sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `step` = 'add-admin' WHERE `id` = $cid");
} else if ($step == "add-admin" and $cid == $shahzoddev) {
	if (is_numeric($text) == "true") {
		if ($text != $shahzoddev) {
			file_put_contents("admin/admins.txt", "\n$text", 8);
			sendMessage($shahzoddev, "✅ <b>$text endi bot admini.</b>", $panel);
			mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
			exit();
		} else {
			sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
			exit();
		}
	} else {
		sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
		exit();
	}
} else if ($data == "remove") {
	deleteMessage($cid, $mid);
	sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>", $cancel);
	mysqli_query($connect, "UPDATE `users` SET `step` = 'remove-admin' WHERE `id` = $cid");
	exit();
} else if ($step == "remove-admin" and $cid == $shahzoddev) {
	if (is_numeric($text) == "true") {
		if ($text != $shahzoddev) {
			$files = file_get_contents("admin/admins.txt");
			$file = str_replace("{$text}", '', $files);
			file_put_contents("admin/admins.txt", $file);
			sendMessage($shahzoddev, "✅ <b>$text endi botda admin emas.</b>", $panel);
			mysqli_query($connect, "UPDATE `users` SET `step` = '0' WHERE `id` = $cid");
			exit();
		} else {
			sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
			exit();
		}
	} else {
		sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
		exit();
	}
} else if ((isset($text) and $lastmsg == "start") and $text != "/start") {
	$son = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `data`"));
	if (mb_stripos($text, "/start ") !== false) {
		$text = explode(" ", $text)[1];
	}
	if ($text == "/rand") {
		$text = rand(1, $son);
	}
	if (joinchat($cid) == true) {
		if (is_numeric($text) == true) {
			if (in_array($cid, $admin)) {
				$keyBot = json_encode([
					'inline_keyboard' => [
						[['text' => "🎞️ Kanalga yuborish", 'callback_data' => "channel=$text"]]
					]
				]);
			} else {
				$keyBot = json_encode([
					'inline_keyboard' => [
						[['text' => "🍿 Boshqa filmlar", 'url' => "https://t.me/$kino"], ['text' => "👨‍💻 Dasturchi", 'url' => "https://t.me/BuilderNet/45"]],
						[['text' => "🔄 Filmni ulashish", 'url' => "https://t.me/share/url/?url=https://t.me/$userbot?start=$text"]]
					]
				]);
			}
			$res = mysqli_query($connect, "SELECT * FROM `data` WHERE `id` = '$text'");
			$row = mysqli_fetch_assoc($res);
			$fname = base64_decode($row['film_name']);
			$f_id = $row['file_id'];
			$date = $row['film_date'];
			if (!$row) {
				sendMessage($cid, "📛 $text <b>kodli kino mavjud emas!</b>");
				exit();
			} else {
				sendVideo($cid, $f_id, "<b>🍿 Kino nomi:</b> $fname
<b>📆 Yuklangan sana:</b> $date

🔎 <b>Kinoning kodi:</b> <code>$text</code>

$reklama", $keyBot);
				exit();
			}
		} else {
			sendMessage($cid, "<b>📛 Faqat raqamlardan foydalaning!</b>");
			exit();
		}
	}
}/*else {
sendMessage($cid, "<b>☹︎ Sizni tushuna olib bo'lmadi!\n\nBotni yangilang: /start</b>");
}*/


?>