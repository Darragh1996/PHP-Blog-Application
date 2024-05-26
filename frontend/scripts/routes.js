const basePathFrontend = "/PHP-Blog-Application/frontend";
const basePathBackend = "http://localhost/PHP-Blog-Application/backend/api.php";

let loadContent = (page) => {
  $("#app").load(`${basePathFrontend}/pages/${page}.html`);
};

// let loadScript = (page) => {
//   let existingScript = $("#temp-script");
//   if (existingScript.length) {
//     existingScript.remove();
//   }
//   $("<script>", {
//     src: `./scripts/${page}.js`,
//     id: "temp-script",
//     load: () => console.log(`${page}.js loaded`),
//   }).appendTo("head");
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
};

let handleRouteChange = () => {
  let pathname = window.location.pathname.replace(/^\/|\/$/g, "");
  let segments = pathname.split("/");
  console.log(segments);

  if (segments[1] === "frontend") {
    switch (segments[2]) {
      case "blogs":
        if (segments[3]) {
          loadPage("blogs");
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
  let pathname = window.location.pathname.replace(/^\/|\/$/g, "");
  console.log(pathname);

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
