$(document).ready(() => {
  let pathname = window.location.pathname.replace(/^\/|\/$/g, "");
  let segments = pathname.split("/");

  $("#addCommentForm").submit((e) => {
    e.preventDefault();

    let formData = {
      text: $("#commentContent").val(),
    };

    $.ajax({
      url: `${basePathBackend}/comments/${segments[3]}`,
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(formData),
      xhrFields: {
        withCredentials: true,
      },
      success: (res) => {
        history.pushState(
          null,
          null,
          `${basePathFrontend}/blogs/${segments[3]}`
        );
        handleRouteChange();
      },
      error: (xhr, status, err) => {
        console.error("comment creation failed: ", status, err);
      },
    });
  });

  $.ajax({
    // segments[3] corresponds to the blog id we're tyring to access
    url: `${basePathBackend}/blogs/${segments[3]}`,
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
