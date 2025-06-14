fetch("/bico/backend/auth/check_session.php")
  .then(res => res.json())
  .then(data => {
    if (!data.loggedIn) {
      window.location.href = "login.html";
    } else {
      loadOtherServices();
    }
  });

function loadOtherServices() {
  fetch('/bico/backend/services/list-others.php')
    .then(res => res.json())
    .then(response => {
      if (!response.success) {
        alert(response.message || 'Erro ao carregar serviços de outros usuários');
        return;
      }
      renderOtherServices(response.services);
    });
}

function renderOtherServices(services) {
  const container = document.getElementById('othersContainer');
  container.innerHTML = '';

  if (services.length === 0) {
  container.innerHTML = '<p>Nenhuma publicação de outros usuários disponível.</p>';
  return;
  }

  const carousel = document.createElement('div');
  carousel.classList.add('carousel');

  services.forEach(service => {
  const link = document.createElement('a');
  link.href = `pdp.html?id=${service.id}`;
  link.classList.add('card-link');

  const card = document.createElement('div');
card.classList.add('card');

card.innerHTML = `
  <img class="card-image" src="${service.image}" alt="${service.title}">
  <div class="card-content">
    <p class="title">${service.title}</p>
    <p class="description">${service.description}</p>
    <p class="price">R$ ${Number(service.price).toFixed(2)}</p>
    <div class="details">
      <span>${service.location}</span>
      <span>${formatDate(service.created_at)}</span>
    </div>
  </div>
`;

link.appendChild(card);
carousel.appendChild(link);
});

container.appendChild(carousel);
}


function formatDate(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString();
}