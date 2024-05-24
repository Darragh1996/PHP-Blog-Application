$(document).ready(() => {
  let pathname = window.location.pathname.replace(/^\/|\/$/g, "");
  let segments = pathname.split("/");

  $.ajax({
    // segments[2] corresponds to the blog id we're tyring to access
    url: `http://localhost:80/PHP-Blog-Application/backend/api.php/blogs/${segments[2]}`,
    type: "GET",
    contentType: "application/json",
    success: (res) => {
      console.log("blogs retrieval succeeded: ", res);
      let blogs = res;
      for (let i = 0; i < blogs.length; i++) {
        createBlogPost(blogs[i]);
      }
    },
    error: (xhr, status, err) => {
      console.error("blogs retrieval failed: ", status, err);
    },
  });
});
