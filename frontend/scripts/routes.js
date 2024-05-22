$(document).ready(() => {
  let pathname = window.location.pathname.replace(/^\/|\/$/g, "");
  console.log(pathname);

  let loadContent = (page) => {
    $("#pageContent").load("./html/" + page + ".html");
  };

  let handleRouteChange = () => {
    let pathname = window.location.pathname.replace(/^\/|\/$/g, "");

    switch (pathname) {
      case "frontend":
        loadContent("blogs");
        break;
      case "frontend/login":
        loadContent("login");
        break;
      case "frontend/register":
        loadContent("register");
        break;
      default:
        console.log("something went wrong");
        break;
    }
  };

  $(window).on("hashchange", handleRouteChange);

  handleRouteChange();
});
