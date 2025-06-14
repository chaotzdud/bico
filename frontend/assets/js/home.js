fetch("/bico/backend/auth/check_session.php")
  .then(res => res.json())
  .then(data => {
    if (!data.loggedIn) {
      window.location.href = "login.html";
    }
  });

