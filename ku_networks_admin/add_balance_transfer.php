<?php
include 'db.php';
include 'check_login.php';

$users = mysqli_query($conn, "SELECT * FROM users");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_id = $_POST['sender_id'];
    $amount = floatval($_POST['amount']);
    $receiver_id = $_POST['receiver_id'] ?? null;

    if (!$receiver_id) {
        echo "Transfer failed: Receiver not selected.";
        exit;
    }

    $code = strtoupper(bin2hex(random_bytes(5)));


    $check_referral = mysqli_query($conn, "SELECT id FROM referal_teams WHERE user_id = '$sender_id' AND referral_userid = '$receiver_id'");
    if (mysqli_num_rows($check_referral) == 0) {
        echo "Transfer failed: You can only transfer to someone you referred.";
        exit;
    }

    mysqli_query($conn, "INSERT INTO balance_transfers (sender_id, receiver_id, amount, code, status)
                         VALUES ('$sender_id', '$receiver_id', '$amount', '$code', 'pending')");

    echo "Transfer request created. Share this code with the recipient: <b>$code</b>";
}
?>

<h2>Add Balance Transfer</h2>
<form method="POST" id="transferForm">
    <label>Your Username (Sender):</label>
    <select name="sender_id" required>
        <?php
        mysqli_data_seek($users, 0);
        while ($row = mysqli_fetch_assoc($users)) {
            echo "<option value='{$row['id']}'>{$row['name']} ({$row['user_id']})</option>";
        }
        ?>
    </select><br><br>

    <label>Recipient Username or Email:</label>
    <input type="text" name="receiver_input" required>
    <button type="button" onclick="findReceiver()">Find</button><br><br>

    <!-- ✅ Move this inside the form -->
    <div id="receiver_container">
        <div id="receiver_dropdown"></div>
    </div>

    <label>Amount:</label>
    <input type="number" step="0.01" name="amount" required><br><br>

    <input type="submit" value="Generate Transfer Code">
</form>

<script>
function findReceiver() {
    const input = document.querySelector('input[name="receiver_input"]').value;
    fetch(`find_receiver.php?query=${encodeURIComponent(input)}`)
        .then(res => res.json())
        .then(data => {
            const dropdown = document.getElementById("receiver_dropdown");
            if (data.length > 0) {
                let selectHTML = '<label>Choose Receiver:</label><select name="receiver_id" required>';
                data.forEach(user => {
                    selectHTML += `<option value="${user.id}">${user.name} (${user.user_id})</option>`;
                });
                selectHTML += '</select><br><br>';
                dropdown.innerHTML = selectHTML;
            } else {
                dropdown.innerHTML = "<span style='color:red;'>No user found or not a valid referral.</span>";
            }
        });
}

document.getElementById("transferForm").addEventListener("submit", function (e) {
    const receiverSelect = document.querySelector('select[name="receiver_id"]');
    if (!receiverSelect) {
        alert("Please select a receiver from the dropdown after searching.");
        e.preventDefault(); 
    }
});
</script>