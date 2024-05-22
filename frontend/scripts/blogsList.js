$(document).ready(() => {
  $.ajax({
    url: "http://localhost:80/PHP-Blog-Application/backend/api.php/blogs",
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

let createBlogPost = (blogData) => {
  let blog = $("<div></div>");
  blog.addClass("blogPost");
  let blogHeading = $("<div></div>");
  blogHeading.addClass("blogHeading");
  let blogTitle = $("<h3></h3>").text(blogData.title);
  let blogAuthor = $("<p></p>").text("Author: " + blogData.username);
  blogHeading.append(blogTitle, blogAuthor);
  let blogDate = $("<p></p>").text("Posted: " + blogData.date);
  blogDate.addClass("blogDate");
  blog.append(blogHeading, blogDate);
  $("#blogListContainer").append(blog);
};
