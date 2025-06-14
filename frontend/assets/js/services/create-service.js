fetch("/bico/backend/auth/check_session.php")
  .then(res => res.json())
  .then(data => {
    if (!data.loggedIn) {
      window.location.href = "login.html";
    }
  });

document.getElementById("service-form").addEventListener("submit", function (e) {
    e.preventDefault();

    const form = document.getElementById("service-form");
    const formData = new FormData(form); // Envia todos os campos + imagem

    fetch("/bico/backend/services/create-service.php", {
        method: "POST",
        body: formData, // sem Content-Type manual
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            alert("Serviço publicado com sucesso!");
            window.location.href = "list-services.html";
        } else {
            alert(response.message);
        }
    });
});
