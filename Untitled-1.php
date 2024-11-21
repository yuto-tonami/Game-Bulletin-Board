<?php
// 簡単なお知らせ表示用サンプル
function getAnnouncements() {
    return [
        "新しいイベントが追加されました！",
        "サーバーメンテナンスのお知らせ。",
        "ランキング更新情報はこちら！"
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    if ($action === 'get_announcements') {
        echo json_encode(getAnnouncements());
    }
}
?>
