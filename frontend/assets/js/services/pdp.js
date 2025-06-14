document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const serviceId = params.get("id");
  
    if (!serviceId) {
      alert("Serviço não especificado.");
      window.location.href = "list-services.html";
      return;
    }
  
    fetch(`/bico/backend/services/get-service-by-id.php?id=${serviceId}`)
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          alert(data.message || "Erro ao carregar serviço.");
          return;
        }
  
        const s = data.service;
  
        document.querySelector(".hero-img").src = s.image;
        document.querySelector(".title-price h3").textContent = s.title;
        document.querySelector(".title-price .price").textContent = `R$ ${Number(s.price).toFixed(2)}`;
  
        document.querySelectorAll(".card")[0].querySelector("p").textContent = s.description;
        document.querySelectorAll(".card")[1].innerHTML = `
          <h3>Detalhes:</h3>
          <p>${s.location}</p>
          <p>${new Date(s.created_at).toLocaleDateString()}</p>
        `;
  
        document.querySelector(".top-text").textContent = s.publisher;
      })
      .catch(() => alert("Erro na comunicação com o servidor."));
  });
  