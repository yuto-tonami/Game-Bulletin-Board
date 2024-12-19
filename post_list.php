<?php
$posts = json_decode(file_get_contents('posts.json'), true) ?? [];
$postId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$post = null;

// 指定されたIDの投稿を探す
foreach ($posts as $p) {
    if ($p['id'] === $postId) {
        $post = $p;
        break;
    }
}
?>
        <?php if ($post): ?>
            <article>
                <h2>投稿内容</h2>
                <p><?= htmlspecialchars($post['content']) ?></p>
                <small>投稿日: <?= htmlspecialchars($post['date']) ?></small>
                <?php if (!empty($post['replies'])): ?>
                    <section>
                        <h3>返信一覧</h3>
                        <?php foreach ($post['replies'] as $reply): ?>
                            <div class="reply">
                                <p><?= htmlspecialchars($reply['content']) ?></p>
                                <small><?= htmlspecialchars($reply['date']) ?></small>
                            </div>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>
            </article>
        <?php else: ?>
            <p>指定された投稿が見つかりません。</p>
        <?php endif; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  $time = date('Y-m-d H:i:s');
  $post = $time . ' - ' . $name . ': ' . $comment . "\n";
  file_put_contents('posts.txt', $post, FILE_APPEND);
  header('Location: ' . $_SERVER['REQUEST_URI']);
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
        