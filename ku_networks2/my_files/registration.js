document.getElementById('send_code').addEventListener('click', function() {
    var email = document.getElementById('email').value;

    if(email == ''){
        alert('Please enter your email address first!');
        return;
    }

   
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'send_verification_code.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200){
            if(xhr.responseText == 'sent'){
                alert('Verification code sent to your email!');
            } else {
                alert('Failed to send code. Please try again.');
            }
        }
    };
    xhr.send('email=' + encodeURIComponent(email));
});

// Check if verification code matches
document.getElementById('verification_code').addEventListener('blur', function() {
    var enteredCode = this.value;
    var email = document.getElementById('email').value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'verify_code.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200){
            if(xhr.responseText == 'verified'){
                document.getElementById('verify_tick').style.display = 'inline';
            } else {
                document.getElementById('verify_tick').style.display = 'none';
                alert('Incorrect verification code.');
            }
        }
    };
    xhr.send('email=' + encodeURIComponent(email) + '&code=' + encodeURIComponent(enteredCode));
});
