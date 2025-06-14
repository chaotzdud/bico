document.getElementById("register-form").addEventListener("submit", function (e) {
  e.preventDefault();

  const data = {
    fname: document.getElementById("fname").value,
    lname: document.getElementById("lname").value,
    dbirth: document.getElementById("dbirth").value,
    cpf: document.getElementById("cpf").value,
    email: document.getElementById("email").value,
    password: document.getElementById("password").value
  };

  fetch("/bico/backend/auth/register.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  })
    .then(res => res.json())
    .then(response => {
      if (response.success) {
        alert("Cadastro realizado com sucesso!");
        window.location.href = "login.html";
      } else {
        alert(response.message);
      }
    });
});