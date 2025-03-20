document.getElementById('likeButton').addEventListener('click', function () {
    const postId = document.querySelector('input[name="post_id"]').value;

    fetch('/like', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({post_id: postId})
    })

        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('likeCount').textContent = data.likes;
            } else {
                alert('Сталася помилка при додаванні лайка!');
            }
        })
        .catch(error => {
            console.error('Помилка:', error);
        });
});

document.addEventListener("DOMContentLoaded", function () {
    const commentForm = document.getElementById("commentForm");
    const commentTextarea = document.querySelector(".comment_textarea");

    if (commentForm) {
        commentForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            const formData = new FormData(commentForm);
            const postId = formData.get("post_id");
            const content = commentTextarea.value.trim();

            if (!content) {
                alert("Коментар не може бути порожнім.");
                return;
            }

            try {
                const response = await fetch("/comment", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    const lastCommentsList = document.querySelector("ul:last-of-type");

                    if (lastCommentsList) {
                        const newCommentElement = document.createElement("li");

                        newCommentElement.innerHTML = `<strong>${data.comment.name}:</strong> ${data.comment.content}`;
                        lastCommentsList.appendChild(newCommentElement);

                        commentTextarea.value = "";
                    } else {
                        alert("Не вдалося знайти список коментарів.");
                    }
                } else {
                    alert(data.message || "Не вдалося додати коментар.");
                }
            } catch (error) {
                console.error("Помилка:", error);
                alert("Сталася помилка. Спробуйте пізніше.");
            }
        });
    }
});

