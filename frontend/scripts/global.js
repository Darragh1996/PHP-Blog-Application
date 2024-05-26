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
  blog.append(blogHeading, blogDate);

  // button container
  if (blogData.is_owner) {
    let buttonsContainer = $("<div></div>");
    buttonsContainer.addClass("buttonsContainer");
    let editButton = $("<button>Edit</button>");
    let deleteButton = $("<button>Delete</button>");

    editButton.on("click", (e) => {
      e.stopPropagation();
      history.pushState(
        null,
        null,
        `${basePathFrontend}/blogs/${blogData.id}/edit`
      );
      handleRouteChange();
    });

    deleteButton.on("click", (e) => {
      e.stopPropagation();
      deleteBlog(blogData.id);
    });

    buttonsContainer.append(editButton, deleteButton);
    blog.append(buttonsContainer);
  }

  // attach on click handler
  blog.on("click", () => {
    // window.location.href = "./blogs.html/" + blogData.id;
    history.pushState(null, null, `${basePathFrontend}/blogs/${blogData.id}`);
    handleRouteChange();
  });

  $("#blogListContainer").append(blog);
};

const deleteBlog = (blogID) => {
  $.ajax({
    url: `${basePathBackend}/blogs/${blogID}`,
    type: "DELETE",
    contentType: "application/json",
    xhrFields: {
      withCredentials: true,
    },
    success: (res) => {
      console.log("Blog was successfully deleted.");
      // trigger a reload of the page to remove the deleted blog
      history.pushState(null, null, `${basePathFrontend}/blogs`);
      handleRouteChange();
    },
    error: (xhr, status, err) => {
      console.error("blogs retrieval failed: ", status, err);
    },
  });
};

const createComment = (commentData) => {
  // create the html structure of a blog post
  let comment = $("<div></div>");
  comment.addClass("comment");
  let commentHeading = $("<div></div>");
  commentHeading.addClass("commentHeading");
  let commentAuthor = $("<p></p>").text("Author: " + commentData.username);
  let commentDate = $("<p></p>").text("Posted: " + commentData.date);
  commentDate.addClass("commentDate");
  commentHeading.append(commentAuthor, commentDate);
  let commentText = $("<p></p>").text(commentData.text);
  comment.append(commentHeading, commentText);

  // button container
  // if (blogData.is_owner) {
  //   let buttonsContainer = $("<div></div>");
  //   buttonsContainer.addClass("buttonsContainer");
  //   let editButton = $("<button>Edit</button>");
  //   let deleteButton = $("<button>Delete</button>");

  //   editButton.on("click", (e) => {
  //     e.stopPropagation();
  //     history.pushState(
  //       null,
  //       null,
  //       `${basePathFrontend}/blogs/${blogData.id}/edit`
  //     );
  //     handleRouteChange();
  //   });

  //   deleteButton.on("click", (e) => {
  //     e.stopPropagation();
  //     deleteBlog(blogData.id);
  //   });

  //   buttonsContainer.append(editButton, deleteButton);
  //   blog.append(buttonsContainer);
  // }

  $("#commentsContainer").append(comment);
};
