fetch("/bico/backend/auth/check_session.php")
  .then(res => res.json())
  .then(data => {
    if (!data.loggedIn) {
      window.location.href = "login.html";
    } else {
      loadServices();
    }
  });

function loadServices() {
  fetch('/bico/backend/services/list-services.php')
    .then(res => res.json())
    .then(response => {
      if (!response.success) {
        alert(response.message || 'Erro ao carregar serviços');
        return;
      }
      renderServices(response.services);
    });
}

function renderServices(services) {
  const container = document.getElementById('listContainer');
  container.innerHTML = '';

  if (services.length === 0) {
  container.innerHTML = '<p>Você não tem serviços cadastrados.</p>';
  return;
  }

  const carousel = document.createElement('div');
  carousel.classList.add('carousel');

  services.forEach(service => {
  const card = document.createElement('div');
  card.classList.add('card');
  card.innerHTML = `
    <img class="card-image" src="${service.image}" alt="${service.title}">
    <div class="card-content">
    <div class="edit">
      <span><a href="edit-service.html?id=${service.id}"><i class="fas fa-pen"></i></a></span>
      <span><i class="fa fa-trash" onclick="deleteService(${service.id})"></i></span>
    </div><br>
      <p class="title">${service.title}</p>
      <p class="description">${service.description}</p>
      <p class="price">R$ ${Number(service.price).toFixed(2)}</p>
      <div class="details">
        <span>${service.location}</span>
        <span>${formatDate(service.created_at)}</span>
      </div>
    </div>
  `;

  carousel.appendChild(card);
  });
  container.appendChild(carousel);
}

// Formata a data (exemplo simples)
function formatDate(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  });
}

function deleteService(serviceId) {
  if (!confirm("Tem certeza que deseja excluir este serviço?")) return;

  fetch('http://localhost/bico/backend/services/delete-service.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: serviceId })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Serviço deletado com sucesso!");
      // recarrega ou atualiza a lista
      loadServices(); // função que você já deve ter
    } else {
      alert("Erro: " + data.message);
    }
  })
  .catch(error => {
    
    console.error('Erro ao deletar serviço:', error);
  });
}
