$(document).ready(function () {
    $("main#spapp > section").height($(document).height() - 60);
  
    var app = $.spapp({ pageNotFound: "error_404" }); // initialize
    app.route({
      view: "shop",
      load: "shop.html",
    });
    app.route({
      view: "about",
      load: "about.html",
    });
  
    app.route({
      view: "home",
      load: "home.html",
    });
  
    app.route({
      view: "contact",
      load: "contact.html",
    });
  

});