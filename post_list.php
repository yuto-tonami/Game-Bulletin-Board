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