<?php
// 文字コードをUTF-8に固定
mb_language("Japanese");
mb_internal_encoding("UTF-8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // データの受け取り
    $name    = htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8');
    $email   = htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8');
    $tel     = htmlspecialchars($_POST['tel'] ?? '', ENT_QUOTES, 'UTF-8');
    $type    = htmlspecialchars($_POST['type'] ?? '', ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8');

    // 送信先
    $to = "info@izanami-legalteam.com"; 
    
    // 1. 件名をUTF-8のまま正しくエンコード
    $subject = "=?UTF-8?B?" . base64_encode("【公式サイト】お問い合わせ（$name 様）") . "?=";

    // 2. 本文を作成
    $body  = "公式サイトのフォームよりお問い合わせがありました。\n";
    $body .= "--------------------------------------------------\n\n";
    $body .= "【お名前】\n$name\n\n";
    $body .= "【メールアドレス】\n$email\n\n";
    $body .= "【電話番号】\n$tel\n\n";
    $body .= "【ご相談内容】\n$type\n\n";
    $body .= "【メッセージ】\n$message\n\n";
    $body .= "--------------------------------------------------\n";

    // 3. ヘッダーをUTF-8として厳格に組み立て（改行は \r\n）
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";
    $headers .= "From: " . "=?UTF-8?B?" . base64_encode("公式サイトフォーム") . "?= <$to>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // 4. mb_send_mail ではなく mail 関数を使用して自動変換を回避
    if (mail($to, $subject, $body, $headers)) {
        header("Location: thanks.html");
        exit;
    } else {
        echo "<script>alert('送信に失敗しました。'); history.back();</script>";
        exit;
    }
} else {
    header("Location: index.html");
    exit;
}