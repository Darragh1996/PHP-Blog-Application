$(document).ready(() => {
  let pathname = window.location.pathname.replace(/^\/|\/$/g, "");
  let segments = pathname.split("/");

  $.ajax({
    // segments[2] corresponds to the blog id we're tyring to access
    url: `http://localhost:80/PHP-Blog-Application/backend/api.php/blogs/${segments[2]}`,
    type: "GET",
    contentType: "application/json",
    success: (res) => {
      let blogData = res;
      console.log(blogData);
      $("#blogTitle").text(blogData.title);
      $("#authorUsername").text(blogData.username);
      $("#blogContents").text(blogData.text);
    },
    error: (xhr, status, err) => {
      console.error("blogs retrieval failed: ", status, err);
    },
  });
});
