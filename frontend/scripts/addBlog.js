$(document).ready(() => {
  $("#addForm").submit((e) => {
    e.preventDefault();

    let formData = {
      title: $("#title").val(),
      text: $("#blogContent").val(),
    };

    $.ajax({
      url: `${basePathBackend}/blogs`,
      type: "POST",
      data: JSON.stringify(formData),
      contentType: "application/json",
      success: (res) => {
        console.log("added successfully");
        history.pushState(null, null, `${basePathFrontend}/blogs`);
        handleRouteChange();
      },
      error: (xhr, status, err) => {
        console.error("add failed: ", status, err);
      },
    });
  });
});
