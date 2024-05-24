// this script file is to avoid functions being declared
// more than once when going back and forth between pages

const createBlogPost = (blogData) => {
  // create the html structure of a blog post
  let blog = $("<div></div>");
  blog.addClass("blogPost");
  let blogHeading = $("<div></div>");
  blogHeading.addClass("blogHeading");
  let blogTitle = $("<h3></h3>").text(blogData.title);
  let blogAuthor = $("<p></p>").text("Author: " + blogData.username);
  blogHeading.append(blogTitle, blogAuthor);
  let blogDate = $("<p></p>").text("Posted: " + blogData.date);
  blogDate.addClass("blogDate");
  // button container
  let buttonsContainer = $("<div></div>");
  buttonsContainer.addClass("buttonsContainer");
  let editButton = $("<button>Edit</button>");
  let deleteButton = $("<button>Delete</button>");
  buttonsContainer.append(editButton, deleteButton);

  blog.append(blogHeading, blogDate, buttonsContainer);

  // attach on click handler
  blog.on("click", () => {
    // window.location.href = "./blogs.html/" + blogData.id;
    history.pushState(null, null, "/frontend/blogs/" + blogData.id);
    handleRouteChange();
  });

  $("#blogListContainer").append(blog);
};
