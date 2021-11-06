<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mallabi | ملعبي</title>
  </head>
  <body>
    <div id="cowpay-otp-container"></div>
  </body>
  <script src="https://cowpay.me/js/plugins/OTPPaymentPlugin.js"></script>

  <script>
    document.cookie =
      "AC-C=ac-c;expires=Fri, 31 Dec 9999 23:59:59 GMT;path=/;SameSite=Lax";
    COWPAYOTPDIALOG.init();
    COWPAYOTPDIALOG.load({{$cowpayReferenceId}}); // the key from the charge request response
  </script>
  <script>
    window.addEventListener(
      "message",
      function (e) {
        if (e.data && e.data.message_source === "cowpay") {
          let paymentStatus = e.data.payment_status,
            cowpayReferenceId = e.data.cowpay_reference_id,
            gatewayReferenceId = e.data.payment_gateway_reference_id;
          // take an action based on the values
          window.close();
        }
      },
      false
    );
  </script>
</html>
