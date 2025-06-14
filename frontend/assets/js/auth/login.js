document.getElementById("login-form").addEventListener("submit", function (e) {
  e.preventDefault();

  const data = {
    email: document.getElementById("email").value,
    password: document.getElementById("password").value,
  };

  fetch("/bico/backend/auth/login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  })
    .then(res => res.json())
    .then(response => {
      if (response.success) {
        window.location.href = "home.html";
      } else {
        alert(response.message);
      }
    });
});