<!DOCTYPE html>
<html lang="zxx">
  <head>
    <title>KU Network</title>
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16" />
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="KU Network" name="description" />
    <meta content="" name="keywords" />
    <meta content="" name="author" />
    
    <!-- CSS Files
    ================================================== -->
    <link
      id="bootstrap"
      href="css/bootstrap.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link
      id="bootstrap-grid"
      href="css/bootstrap-grid.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link
      id="bootstrap-reboot"
      href="css/bootstrap-reboot.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link href="css/animate.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <!-- color scheme -->
    <link
      id="colors"
      href="css/colors/scheme-01.css"
      rel="stylesheet"
      type="text/css"
    />
    <link href="css/coloring.css" rel="stylesheet" type="text/css" />
  </head>

  <body>
    <div id="wrapper">
      <!-- header begin -->
         <?php include 'Assets/header.php'; ?>
   
      <!-- header close -->
      <!-- content begin -->
      <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <!-- section begin -->
        <section
          id="subheader"
          class="text-light"
          data-bgimage="url(images/background//bg.png) top"
        >
          <div class="center-y relative text-center">
            <div class="container">
              <div class="row">
                <div class="col-md-12 text-center">
                  <h1>Contact Us</h1>
                  <p>Anim pariatur cliche reprehenderit</p>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
        </section>
        <!-- section close -->

        <section aria-label="section">
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mb-sm-30">
                <h3>Do you have any question?</h3>

                <form
                  name="contactForm"
                  id="contact_form"
                  class="form-border"
                  method="post"
                  action="email.php"
                >
                  <div class="field-set">
                    <input
                      type="text"
                      name="name"
                      id="name"
                      class="form-control"
                      placeholder="Your Name"
                    />
                  </div>

                  <div class="field-set">
                    <input
                      type="text"
                      name="email"
                      id="email"
                      class="form-control"
                      placeholder="Your Email"
                    />
                  </div>

                  <div class="field-set">
                    <input
                      type="text"
                      name="phone"
                      id="phone"
                      class="form-control"
                      placeholder="Your Phone"
                    />
                  </div>

                  <div class="field-set">
                    <textarea
                      name="message"
                      id="message"
                      class="form-control"
                      placeholder="Your Message"
                    ></textarea>
                  </div>

                  <div class="spacer-half"></div>

                  <div id="submit">
                    <input
                      type="submit"
                      id="send_message"
                      value="Submit Form"
                      class="btn btn-main"
                    />
                  </div>
                  <div id="mail_success" class="success">
                    Your message has been sent successfully.
                  </div>
                  <div id="mail_fail" class="error">
                    Sorry, error occured this time sending your message.
                  </div>
                </form>
              </div>

              <div class="col-lg-4">
                <div class="padding40 box-rounded mb30" data-bgcolor="#e5e5e5">
                  <h3>US Office</h3>
                  <address class="s1">
                    <span
                      ><i class="id-color fa fa-map-marker fa-lg"></i>08 W 36th
                      St, New York, NY 10001</span
                    >
                    <span
                      ><i class="id-color fa fa-phone fa-lg"></i>+1 333
                      9296</span
                    >
                    <span
                      ><i class="id-color fa fa-envelope-o fa-lg"></i
                      ><a href="mailto:contact@example.com"
                        >contact@example.com</a
                      ></span
                    >
                    <span
                      ><i class="id-color fa fa-file-pdf-o fa-lg"></i
                      ><a href="#">Download Brochure</a></span
                    >
                  </address>
                </div>

                <div class="padding40 bg-color text-light box-rounded">
                  <h3>AU Office</h3>
                  <address class="s1">
                    <span
                      ><i class="fa fa-map-marker fa-lg"></i>100 Mainstreet
                      Center, Sydney</span
                    >
                    <span><i class="fa fa-phone fa-lg"></i>+61 333 9296</span>
                    <span
                      ><i class="fa fa-envelope-o fa-lg"></i
                      ><a href="mailto:contact@example.com"
                        >contact@example.com</a
                      ></span
                    >
                    <span
                      ><i class="fa fa-file-pdf-o fa-lg"></i
                      ><a href="#">Download Brochure</a></span
                    >
                  </address>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <!-- content close -->

      <a href="#" id="back-to-top"></a>

      <!-- footer begin -->
         <?php include 'Assets/footer.php'; ?>
     
      <!-- footer close -->
    </div>

    <!-- Javascript Files
    ================================================== -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/easing.js"></script>
    <script src="js/owl.carousel.js"></script>
    <script src="js/validation.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/enquire.min.js"></script>
    <script src="js/jquery.plugin.js"></script>
    <script src="js/jquery.countTo.js"></script>
    <script src="js/jquery.countdown.js"></script>
    <script src="js/jquery.lazy.min.js"></script>
    <script src="js/jquery.lazy.plugins.min.js"></script>
    <script src="js/designesia.js"></script>
  </body>
</html>
