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
