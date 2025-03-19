document.getElementById("registerForm").addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    document.getElementById("error-message").style.display = 'none';

    fetch('/register/store', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/home';
            } else {
                document.getElementById("error-message").style.display = 'block';
                document.getElementById("error-message").innerText = data.errorMessage;

                document.getElementById("name").value = data.name;
                document.getElementById("email").value = data.email;
            }
        })
        .catch(error => console.error('Error:', error));
});
