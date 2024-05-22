$(document).ready(() => {
  $.ajax({
    url: "http://localhost:80/PHP-Blog-Application/backend/api.php/blogs",
    type: "GET",
    contentType: "application/json",
    success: (res) => {
      console.log("blogs retrieval succeeded: ", res);
    },
    error: (xhr, status, err) => {
      console.error("blogs retrieval failed: ", status, err);
    },
  });
});
