<?php
// save_post.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = htmlspecialchars($_POST['content']);
    $posts = json_decode(file_get_contents('posts.json'), true) ?? [];
    $posts[] = ['content' => $content, 'date' => date('Y-m-d H:i:s')];
    file_put_contents('posts.json', json_encode($posts));
    echo json_encode(['success' => true, 'posts' => $posts]);
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = htmlspecialchars($_POST['content']);
    $replyTo = isset($_POST['replyTo']) ? (int)$_POST['replyTo'] : null; // 返信先ID
    $posts = json_decode(file_get_contents('posts.json'), true) ?? [];
    
    $newPost = [
        'id' => count($posts) + 1,
        'content' => $content,
        'date' => date('Y-m-d H:i:s'),
        'replies' => []
    ];

    if ($replyTo) {
        foreach ($posts as &$post) {
            if ($post['id'] === $replyTo) {
                $post['replies'][] = $newPost;
            }
        }
    } else {
        $posts[] = $newPost;
    }

    file_put_contents('posts.json', json_encode($posts));
    echo json_encode(['success' => true, 'posts' => $posts]);
    exit;
}

// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $deleteId = (int)$data['id'];
    $posts = json_decode(file_get_contents('posts.json'), true) ?? [];

    $posts = array_filter($posts, function ($post) use ($deleteId) {
        return $post['id'] !== $deleteId;
    });

    file_put_contents('posts.json', json_encode(array_values($posts)));
    echo json_encode(['success' => true, 'posts' => $posts]);
    exit;
}
?>
<?php
$posts = file_get_contents('posts.txt');
$posts = nl2br(htmlspecialchars($posts, ENT_QUOTES, 'UTF-8'));
?>

<div id="posts">
  <?= $posts ?>
</div>