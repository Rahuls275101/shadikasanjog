<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Plan Payment</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>

<script>

Swal.fire({
    icon: "info",
    title: "Confirm Plan Purchase",
    html: `
        <div style="text-align:left">
            <p><b>Plan:</b> <?= esc($membership->membership_name) ?></p>
            <p><b>Renewal Type:</b> <?= esc($renewalType) ?></p>
            <p><b>Duration:</b> <?= esc($durationMonths) ?> Month(s)</p>
            <p><b>Price:</b> ₹<?= number_format($membership->membership_price, 2) ?></p>
        </div>
    `,
    showCancelButton: true,
    confirmButtonText: "Proceed to Payment",
    cancelButtonText: "Cancel",
    confirmButtonColor: "#F37254",
    allowOutsideClick: false
}).then((result) => {

    if (result.isConfirmed) {

        var options = <?= json_encode($data) ?>;

        options.handler = function (response) {
            document.getElementById("razorpay_payment_id").value = response.razorpay_payment_id;
            document.getElementById("razorpay_signature").value = response.razorpay_signature;
            document.razorpayform.submit();
        };

        options.modal = {
            ondismiss: function () {
                window.location.href = "<?= base_url('plans') ?>";
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();

    } else {
        window.location.href = "<?= base_url('plans') ?>";
    }

});

</script>

<form name="razorpayform" action="<?= base_url('plan-razorpay-callback') ?>" method="POST" style="display:none;">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    <input type="hidden" name="razorpay_order_id" value="<?= esc($razorpayOrderId) ?>">
</form>

</body>
</html>
