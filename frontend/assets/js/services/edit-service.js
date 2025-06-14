// Função para pegar parâmetro da URL
function getQueryParam(param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
}

const serviceId = getQueryParam('id');

if (!serviceId) {
  alert('ID do serviço não informado');
  window.location.href = 'home.html';
}

const form = document.getElementById('edit-service-form');

fetch(`/bico/backend/services/get-service.php?id=${serviceId}`)
  .then(res => res.json())
  .then(data => {
    if (!data.success) {
      alert(data.message || 'Erro ao carregar serviço');
      window.location.href = 'home.html';
      return;
    }
    const service = data.service;
    form.title.value = service.title;
    form.description.value = service.description;
    form.price.value = service.price;
    form.location.value = service.location;
  });

form.addEventListener('submit', (e) => {
  e.preventDefault();

  const data = {
    id: parseInt(serviceId),
    title: form.title.value.trim(),
    description: form.description.value.trim(),
    price: parseFloat(form.price.value.trim()),
    location: form.location.value.trim()
  };

  fetch('/bico/backend/services/edit-service.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  }).then(res => res.json())
    .then(resp => {
      if (resp.success) {
        alert('Serviço atualizado com sucesso!');
        window.location.href = 'list-services.html';
      } else {
        alert(resp.message || 'Erro ao atualizar o serviço');
      }
    });
});
