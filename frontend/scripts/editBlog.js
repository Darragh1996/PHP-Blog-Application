$(document).ready(() => {
  let pathname = window.location.pathname.replace(/^\/|\/$/g, "");
  let segments = pathname.split("/");

  $("#editForm").submit((e) => {
    e.preventDefault();

    let formData = {
      title: $("#title").val(),
      text: $("#blogContent").val(),
    };

    $.ajax({
      url: `${basePathBackend}/blogs/${segments[3]}`,
      type: "PATCH",
      data: JSON.stringify(formData),
      contentType: "application/json",
      success: (res) => {
        console.log("update successful");
        history.pushState(null, null, `${basePathFrontend}/blogs`);
        handleRouteChange();
      },
      error: (xhr, status, err) => {
        console.error("update failed: ", status, err);
      },
    });
  });

  $.ajax({
    url: `${basePathBackend}/blogs/${segments[3]}`,
    type: "GET",
    contentType: "application/json",
    xhrFields: {
      withCredentials: true,
    },
    success: (res) => {
      let blog = res;
      console.log(blog);
      $("#title").val(blog.title);
      $("#blogContent").val(blog.text);
    },
    error: (xhr, status, err) => {
      console.error("blogs retrieval failed: ", status, err);
    },
  });
});
