$(document).ready(() => {
  // handle submit
  $("#loginForm").submit((e) => {
    e.preventDefault();

    let formData = {
      username: $("#username").val(),
      password: $("#password").val(),
    };

    $.ajax({
      url: `${basePathBackend}/users/login`,
      type: "POST",
      data: JSON.stringify(formData),
      contentType: "application/json",
      success: (res) => {
        history.pushState(null, null, "/PHP-Blog-Application/frontend/blogs");
        handleRouteChange();
      },
      error: (xhr, status, err) => {
        console.error("login failed: ", status, err);
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
      } else if (!/^(?=.*[0-9])(?=.*[\W_]).+$/.test(e.target.value)) {
        // checks that the password has at least 1 number and 1 special character
        message =
          "Password must have at least 1 number and 1 special character.";
      }
      $("#passwordMessage").text(message);
    }
  });

  // display pop-up message (if there is one)
  let popUpMessage = sessionStorage.getItem("message");
  if (popUpMessage) {
    $("#popUpMessage").text(popUpMessage);
    $("#popUpMessage").removeClass("hidden");
    $("#popUpMessage").addClass("show");

    setTimeout(function () {
      $("#popUpMessage").removeClass("show");
      setTimeout(function () {
        $("#popUpMessage").addClass("hidden");
      }, 500); // Match the transition duration
    }, 3000); // Display duration
    sessionStorage.removeItem("message");
  }
});
