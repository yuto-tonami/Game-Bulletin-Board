// script.js
document.addEventListener("DOMContentLoaded", () => {
    const postsSection = document.getElementById("posts");
    const form = document.getElementById("post-form");
    const contentInput = document.getElementById("content");

    // 投稿を表示する関数
    const displayPosts = (posts) => {
        postsSection.innerHTML = '';
        posts.forEach(post => {
            const postElement = createPostElement(post);
            postsSection.appendChild(postElement);
        });
    };

    // 投稿HTMLを生成
    const createPostElement = (post) => {
        const postElement = document.createElement("div");
        postElement.classList.add("post");
        postElement.innerHTML = `
            <p>${post.content}</p>
            <small>${post.date}</small>
            <button class="delete-btn" data-id="${post.id}">削除</button>
            <div class="reply-form">
                <textarea rows="3" placeholder="返信を書く"></textarea>
                <button class="reply-btn" data-id="${post.id}">返信する</button>
            </div>
        `;
        post.replies.forEach(reply => {
            const replyElement = document.createElement("div");
            replyElement.classList.add("reply");
            replyElement.innerHTML = `<p>${reply.content}</p><small>${reply.date}</small>`;
            postElement.appendChild(replyElement);
        });
        return postElement;
    };

    // 初期投稿をロード
    fetch("posts.json")
        .then(response => response.json())
        .then(posts => displayPosts(posts))
        .catch(() => console.error("投稿の読み込みに失敗しました"));

    // 新規投稿
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const content = contentInput.value;

        fetch("save_post.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `content=${encodeURIComponent(content)}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayPosts(data.posts);
                    contentInput.value = '';
                }
            })
            .catch(() => console.error("投稿の送信に失敗しました"));
    });

    // 返信
    postsSection.addEventListener("click", (e) => {
        if (e.target.classList.contains("reply-btn")) {
            const replyTo = e.target.getAttribute("data-id");
            const textarea = e.target.previousElementSibling;
            const content = textarea.value;

            fetch("save_post.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `content=${encodeURIComponent(content)}&replyTo=${replyTo}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayPosts(data.posts);
                    }
                })
                .catch(() => console.error("返信の送信に失敗しました"));
        }
    });

    // 削除
    postsSection.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-btn")) {
            const deleteId = e.target.getAttribute("data-id");

            fetch("save_post.php", {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${deleteId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayPosts(data.posts);
                    }
                })
                .catch(() => console.error("削除に失敗しました"));
        }
    });
});
