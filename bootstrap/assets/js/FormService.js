var FormService = {
  submitMessage: function () {
    event.preventDefault();

    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var subject = document.getElementById("message").value;
    var message = document.getElementById("message").value;

    var data = {
      name: name,
      email: email,
      subject: subject,
      message: message,
      user_id: localStorage.getItem("user_token"),
    };

    if (!name || !email || !subject || !message) {
      toastr.error('Please fill in all the fields.');
      return;
  }

    $.ajax({
      url: "rest/forms",
      method: "POST",
      data: JSON.stringify(data),
      contentType: "application/json",
      dataType: "json",
      beforeSend: function (xhr) {
        xhr.setRequestHeader(
          "Authorization",
          localStorage.getItem("user_token")
        );
      },
      success: function (result) {
        toastr.success("Message was sent successfully!");
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error("Error! Message was not sent!");
      },
    });
  },
};
