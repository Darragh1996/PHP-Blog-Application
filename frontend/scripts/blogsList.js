$(document).ready(() => {
  $.ajax({
    url: "http://localhost/PHP-Blog-Application/backend/api.php/blogs",
    type: "GET",
    contentType: "application/json",
    xhrFields: {
      withCredentials: true,
    },
    success: (res) => {
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
