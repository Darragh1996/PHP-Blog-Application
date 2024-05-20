$(document).ready(() => {
  // handle submit
  $("#registerForm").submit((e) => {
    e.preventDefault();

    let formData = {
      username: $("#username").val(),
      email: $("#email").val(),
      password: $("#password").val(),
    };

    console.log(formData);

    $.ajax({
      url: "http://localhost:80/PHP-Blog-Application/backend/api.php/users",
      type: "POST",
      data: JSON.stringify(formData),
      contentType: "application/json",
      success: (res) => {
        console.log("register successful: ", res);
      },
      error: (xhr, status, err) => {
        console.error("register failed: ", status, err);
      },
    });
  });

  // handle input
  $("input").on("input", (e) => {
    if (e.target.id == "username") {
      let message = "";
      if (e.target.value.length <= 4) {
        message = "Username must be at least 5 characters in length.";
      } else if (e.target.value.length > 16) {
        message = "Username must be less than 16 characters in length.";
      }
      $("#usernameMessage").text(message);
    } else if (e.target.id == "password") {
      let message = "";
      if (e.target.value.length <= 7) {
        message = "Password must be at least 7 characters long.";
      } else if (
        !/^(?=.*[0-9])(?=.*[\W_]).+$/.test(e.target.value) // checks that the password has at least 1 number and 1 special character
      ) {
        message =
          "Password must have at least 1 number and 1 special character.";
      }
      $("#passwordMessage").text(message);
    } else if (e.target.id == "email") {
      let message = "";
      if (
        !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(e.target.value) // checks that the input entered resembles an email address
      ) {
        message = "Not a valid email address.";
      }
      $("#emailMessage").text(message);
    }
  });
});
