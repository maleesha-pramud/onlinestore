function changeView() {
    document.getElementById('signinBox').classList.toggle('d-none');
    document.getElementById('signupBox').classList.toggle('d-none');
}

function signUp() {
    var fname = document.getElementById('fname').value;
    var lname = document.getElementById('lname').value;
    var mobile = document.getElementById('mobile').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    var form = new FormData();
    form.append('fname', fname);
    form.append('lname', lname);
    form.append('mobile', mobile);
    form.append('email', email);
    form.append('password', password);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            if (response == 'success') {
                window.location.reload();
            } else {
                document.getElementById('errorMsg').innerHTML = response;
                document.getElementById('errorMsgDiv').classList.remove('d-none');
            }
        }
    }
    req.open('POST', './lib/signup-process.php', true);
    req.send(form);
}

function signIn() {
    var email = document.getElementById('email2').value;
    var password = document.getElementById('password2').value;
    var rememberMe = document.getElementById('remember-me').checked;

    var form = new FormData();
    form.append('email', email);
    form.append('password', password);
    form.append('rememberMe', rememberMe);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            if (response == 'success') {
                if (rememberMe) {
                    var d = new Date();
                    d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000)); // Set cookie to expire in 30 days
                    var expires = "expires=" + d.toUTCString();
                    document.cookie = "email=" + email + ";" + expires;
                    document.cookie = "password=" + password + ";" + expires;
                }
                window.location.href = '/onlinestore/';
            } else {
                document.getElementById('errorMsg2').innerHTML = response;
                document.getElementById('errorMsgDiv2').classList.remove('d-none');
            }
        }
    }

    req.open('POST', './lib/signin-process.php', true);
    req.send(form);
}

function forgotPassword() {
    var email = document.getElementById('email').value;

    var form = new FormData();
    form.append('email', email);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            alert(response);
        }
    }

    req.open('POST', './lib/forgot-password-process.php', true);
    req.send(form);
}

function resetPassword() {
    var password = document.getElementById('password').value;
    var cPassword = document.getElementById('cPassword').value;
    var vcode = document.getElementById('vcode').value;

    var form = new FormData();
    form.append('password', password);
    form.append('cPassword', cPassword);
    form.append('vcode', vcode);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            alert(response);
        }
    }

    req.open('POST', './lib/reset-password-process.php', true);
    req.send(form);
}

function addListing() {
    var title = document.getElementById('title').value;
    var description = document.getElementById('description').value;
    var address = document.getElementById('address').value;
    var guests = document.getElementById('guests').value;
    var bedrooms = document.getElementById('bedrooms').value;
    var beds = document.getElementById('beds').value;
    var bathrooms = document.getElementById('bathrooms').value;
    var images = document.getElementById('images').files;
    var amenities = document.getElementsByName('amenities');

    var form = new FormData();
    form.append('title', title);
    form.append('description', description);
    form.append('address', address);
    form.append('guests', guests);
    form.append('bedrooms', bedrooms);
    form.append('beds', beds);
    form.append('bathrooms', bathrooms);
    for (var i = 0; i < images.length; i++) {
        form.append('images[]', images[i]);
    }
    for (var i = 0; i < amenities.length; i++) {
        if (amenities[i].checked) {
            form.append('amenities[]', amenities[i].id);
        }
    }

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            alert(response);
            if (response == 'success') {
                window.location.href = '/onlinestore/';
            }
        }
    }

    req.open('POST', '/onlinestore/lib/add-listing-process.php', true);
    req.send(form);
}

function checkAvailability(listingId) {
    var checkIn = document.getElementById('checkIn').value;
    var checkOut = document.getElementById('checkOut').value;

    var form = new FormData();
    form.append('listingId', listingId);
    form.append('checkIn', checkIn);
    form.append('checkOut', checkOut);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            alert(response);
        }
    }

    req.open('POST', '/onlinestore/lib/check-availability-process.php', true);
    req.send(form);
}