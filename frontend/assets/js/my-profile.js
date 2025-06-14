async function loadUserData() {
try {
    const res = await fetch('/bico/backend/auth/get-user-data.php');
    const data = await res.json();

    if (data.success) {
    const user = data.user;
    document.querySelector('.profile-form').innerHTML = `
        <p><strong>Nome:</strong> ${user.fname}</p>
        <p><strong>Sobrenome:</strong> ${user.lname}</p>
        <p><strong>CPF:</strong> ${user.cpf}</p>
        <p><strong>Data de nascimento:</strong> ${formatDate(user.dbirth)}</p>
    `;
    } else {
    alert(data.message || "Erro ao carregar dados.");
    window.location.href = 'login.html';
    }
} catch (error) {
    console.error('Erro:', error);
    alert("Erro ao carregar dados do perfil.");
}
}

document.querySelector(".exit").addEventListener("click", function () {
    fetch("/bico/backend/auth/logout.php", {
      method: "POST"
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            console.log("Logout successful");
        }
    });
  });

loadUserData();

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString();
  }
