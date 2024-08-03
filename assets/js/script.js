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
    var category = document.getElementById('category').value;
    var description = document.getElementById('description').value;
    var address = document.getElementById('address').value;
    var guests = document.getElementById('guests').value;
    var bedrooms = document.getElementById('bedrooms').value;
    var beds = document.getElementById('beds').value;
    var bathrooms = document.getElementById('bathrooms').value;
    var images = document.getElementById('images').files;
    var amenities = document.getElementsByName('amenities');
    var basePrice = document.getElementById('basePrice').value;

    // Validate the values before appending to FormData
    if (!title) {
        alert('Please enter a title');
    } else if (!category) {
        alert('Please select a category');
    } else if (!description) {
        alert('Please enter a description');
    } else if (!address) {
        alert('Please enter an address');
    } else if (!guests) {
        alert('Please enter the number of guests');
    } else if (!bedrooms) {
        alert('Please enter the number of bedrooms');
    } else if (!beds) {
        alert('Please enter the number of beds');
    } else if (!bathrooms) {
        alert('Please enter the number of bathrooms');
    } else if (images.length === 0) {
        alert('Please select at least one image');
    } else if (!basePrice) {
        alert('Please enter the base price');
    } else {
        var form = new FormData();
        form.append('title', title);
        form.append('category', category);
        form.append('description', description);
        form.append('address', address);
        form.append('guests', guests);
        form.append('bedrooms', bedrooms);
        form.append('beds', beds);
        form.append('bathrooms', bathrooms);
        form.append('basePrice', basePrice);
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
}

function addCategory() {
    var name = document.getElementById('name').value;

    var form = new FormData();
    form.append('name', name);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            alert(response);
            // if (response == 'success') {
            //     window.location.href = '/onlinestore/';
            // }
        }
    }

    req.open('POST', '/onlinestore/lib/add-category-process.php', true);
    req.send(form);
}

function editCategory() {
    var id = document.getElementById('id').value;
    var name = document.getElementById('name').value;

    var form = new FormData();
    form.append('id', id);
    form.append('name', name);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            alert(response);
            // if (response == 'success') {
            //     window.location.href = '/onlinestore/';
            // }
        }
    }

    req.open('POST', '/onlinestore/lib/edit-category-process.php', true);
    req.send(form);
}

function checkAvailability(listingId) {
    var checkIn = document.getElementById('checkIn').value;
    var checkOut = document.getElementById('checkOut').value;

    if (!checkIn) {
        alert('Please Enter the Check In date')
        return;
    } else if (!checkOut) {
        alert('Please Enter the Check Out date')
        return;
    }

    var form = new FormData();
    form.append('listingId', listingId);
    form.append('checkIn', checkIn);
    form.append('checkOut', checkOut);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            if (response === 'success') {
                window.location.href = '/onlinestore/booking.php?id=' + listingId
            } else {
                alert(response);
            }
        }
    }

    req.open('POST', '/onlinestore/lib/check-availability-process.php', true);
    req.send(form);
}