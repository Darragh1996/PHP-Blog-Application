const basePathFrontend = "/PHP-Blog-Application/frontend";
const basePathBackend = "http://localhost/PHP-Blog-Application/backend/api.php";

let loadContent = (page) => {
  $("#app").load(`${basePathFrontend}/pages/${page}.html`);
};

// let loadScript = (page) => {
//   let script = $(".temp-script");
//   script.src = `${basePathFrontend}/scripts/${page}.js`;
// };

let loadStyle = (page) => {
  let existingStyle = $("#temp-style");
  if (existingStyle.length) {
    existingStyle.remove();
  }
  $("<link>", {
    rel: "stylesheet",
    href: `${basePathFrontend}/styles/${page}.css`,
    id: "temp-style",
  }).appendTo("head");
};

let loadPage = (page) => {
  loadStyle(page);
  loadContent(page);
  // loadScript(page);
};

let handleRouteChange = () => {
  let pathname = window.location.pathname.replace(/^\/|\/$/g, "");
  let segments = pathname.split("/");

  // hide the nav if we're anywhere but the login or register page
  if (
    pathname == `PHP-Blog-Application/frontend` ||
    pathname == "PHP-Blog-Application/frontend/login" ||
    pathname == "PHP-Blog-Application/frontend/register"
  ) {
    $("#navButtons").hide();
  } else {
    $("#navButtons").show();
  }

  if (segments[1] === "frontend") {
    switch (segments[2]) {
      case "blogs":
        if (segments.length == 5) {
          loadPage("editBlog");
        } else if (segments.length == 4) {
          if (segments[3] == "add") {
            loadPage("addBlog");
          } else {
            loadPage("blogs");
          }
        } else {
          loadPage("blogsList");
        }
        break;
      case "login":
        loadPage("login");
        break;
      case "register":
        loadPage("register");
        break;
      default:
        console.log("Page not found");
        loadPage("login");
        break;
    }
  } else {
    console.log("Invalid path!");
  }
};

$(document).ready(() => {
  // let pathname = window.location.pathname.replace(/^\/|\/$/g, "");
  // console.log(pathname);

  $("#backButton").on("click", (e) => {
    e.preventDefault();
    history.back();
  });

  $("#logout").on("click", (e) => {
    e.preventDefault();

    $.ajax({
      url: `${basePathBackend}/users/logout`,
      type: "POST",
      contentType: "application/json",
      success: (res) => {
        history.pushState(null, null, `${basePathFrontend}/`);
        handleRouteChange();
      },
      error: (xhr, status, err) => {
        console.error("login failed: ", status, err);
      },
    });
  });

  let navigate = (e) => {
    e.preventDefault();
    const path = $(e.target).attr("href");
    history.pushState(null, null, path);
    handleRouteChange();
  };

  $(document).on("click", "a.link", navigate);

  $(window).on("popstate", handleRouteChange);
  $(window).on("hashchange", handleRouteChange);

  handleRouteChange();
});
