$(document).ready(() => {
  $("#loginForm").submit((e) => {
    e.preventDefault();

    let formData = {
      username: $("#username").val(),
      password: $("#password").val(),
    };

    console.log(formData);

    $.ajax({
      url: "http://localhost:80/PHP-Blog-Application/backend/api.php/users/login",
      type: "POST",
      data: JSON.stringify(formData),
      contentType: "application/json",
      success: (res) => {
        console.log("login successful: ", res);
      },
      error: (xhr, status, err) => {
        console.error("login failed: ", status, err);
      },
    });
  });
});
