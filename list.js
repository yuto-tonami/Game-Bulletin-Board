document.addEventListener("DOMContentLoaded", () => {
    const postList = document.getElementById("post-list");

    // 投稿を表示する関数
    const displayPostList = (posts) => {
        postList.innerHTML = '';
        posts.forEach(post => {
            const postElement = document.createElement("div");
            postElement.classList.add("post-summary");
            postElement.innerHTML = `
                <a href="post_detail.php?id=${post.id}">${post.content.slice(0, 30)}...</a>
                <small>${post.date}</small>
            `;
            postList.appendChild(postElement);
        });
    };

    // 投稿一覧を取得
    fetch("post_list.php")
        .then(response => response.json())
        .then(posts => displayPostList(posts))
        .catch(() => console.error("投稿一覧の読み込みに失敗しました"));
});

let starRating = document.getElementById("star-rating-template").content;
document.body.appendChild(starRating);

const summaries = document.querySelectorAll('summary');

summaries.forEach((summary) => {
  summary.addEventListener('click', closeOpenedDetails);
});

function closeOpenedDetails() {
  summaries.forEach((summary) => {
    let detail = summary.parentNode;
      if (detail != this.parentNode) {
        detail.removeAttribute('open');
      }
    });
}