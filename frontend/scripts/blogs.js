$(document).ready(() => {
  let pathname = window.location.pathname.replace(/^\/|\/$/g, "");
  let segments = pathname.split("/");

  $.ajax({
    // segments[3] corresponds to the blog id we're tyring to access
    url: `http://localhost/PHP-Blog-Application/backend/api.php/blogs/${segments[3]}`,
    type: "GET",
    contentType: "application/json",
    success: (res) => {
      let blogData = res;
      $("#blogTitle").text(blogData.title);
      $("#authorUsername").text(blogData.username);
      $("#blogDate").text(blogData.date);
      if (blogData.edit_date) {
        $("#blogEditDate").text(`Edited on: ${blogData.edit_date}`);
      }
      $("#blogContents").text(blogData.text);
    },
    error: (xhr, status, err) => {
      console.error("blogs retrieval failed: ", status, err);
    },
  });
});
