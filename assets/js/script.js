function PostRequest(path, formData, callback) {
    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4) {
            if (req.status === 200) {
                var response = JSON.parse(req.responseText);
                if (response.status) {
                    callback(response);
                } else {
                    console.log(response);
                    callback(null, response.message);
                }
            } else {
                callback(null, 'Request failed with status: ' + req.status);
            }
        }
    };

    req.open('POST', path, true);
    req.send(formData);
}

function GetRequest(path, formData, callback) {
    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4) {
            if (req.status === 200) {
                var response = JSON.parse(req.responseText);
                if (response.status) {
                    callback(response);
                } else {
                    console.log(response);
                    callback(null, response.message);
                }
            } else {
                callback(null, new Error('Request failed with status: ' + req.status));
            }
        }
    };

    // Convert formData to query string
    var queryString = new URLSearchParams(formData).toString();
    var urlWithParams = path + '?' + queryString;

    req.open('GET', urlWithParams);
    req.send();
}

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

    PostRequest('./lib/signin-process.php', form, function (response, error) {
        if (error) {
            document.getElementById('errorMsg2').innerHTML = error;
            document.getElementById('errorMsgDiv2').classList.remove('d-none');
            return;
        }

        if (response.status) {
            if (rememberMe) {
                var d = new Date();
                d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000)); // Set cookie to expire in 30 days
                var expires = "expires=" + d.toUTCString();
                document.cookie = "email=" + email + ";" + expires;
                document.cookie = "password=" + password + ";" + expires;
            }
            window.location.href = '/onlinestore/';
        } else {
            document.getElementById('errorMsg2').innerHTML = response.message;
            document.getElementById('errorMsgDiv2').classList.remove('d-none');
        }
    });
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

    PostRequest('./lib/reset-password-process.php', form, function (response, error) {
        if (error) {
            alert(error);
            return;
        }

        if (response.status) {
            window.location.href = '/onlinestore/signin.php';
        } else {
            alert(response.message);
        }
    });
}

function checkAvailability(listingId, price) {
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
                window.location.href = '/onlinestore/booking.php?id=' + listingId + '&checkIn=' + checkIn + '&checkOut=' + checkOut;
            } else {
                alert(response);
            }
        }
    }

    req.open('POST', '/onlinestore/lib/check-availability-process.php', true);
    req.send(form);
}

function inputImage() {
    var input = document.getElementById('imageInput');
    input.click();
}

function checkoutPayment(checkIn, checkOut, propertyId, totalPrice) {
    var fname = document.getElementById('firstName').value;
    var lname = document.getElementById('lastName').value;
    var nic = document.getElementById('nic').value;
    var contact = document.getElementById('contact').value;
    var guests = document.getElementById('guests').value;
    var email = document.getElementById('email').value;
    var specialRequests = document.getElementById('specialRequests').value;

    if (!fname) {
        alert("First name is required.");
        return;
    } else if (lname === "") {
        alert("Last name is required.");
        return;
    } else if (nic === "") {
        alert("NIC is required.");
        return;
    } else if (contact === "" || !/^\d+$/.test(contact)) {
        alert("Valid contact number is required.");
        return;
    } else if (guests === "" || !/^\d+$/.test(guests)) {
        alert("Guests count must be a valid number.");
        return;
    } else if (email === "" || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Valid email address is required.");
        return;
    }

    var formData = new FormData();
    formData.append('checkIn', checkIn);
    formData.append('checkOut', checkOut);
    formData.append('firstName', fname);
    formData.append('lastName', lname);
    formData.append('nic', nic);
    formData.append('contact', contact);
    formData.append('guests', guests);
    formData.append('email', email);
    formData.append('specialRequests', specialRequests);
    formData.append('propertyId', propertyId);
    formData.append('totalPrice', totalPrice);

    PostRequest('/onlinestore/lib/checkout-process.php', formData, function (response, error) {
        if (error) {
            alert('Error: ' + error.message);
            console.log(error);
            return;
        }
        var payment = {
            "sandbox": true,
            "merchant_id": "1227844", // Replace your Merchant ID
            "return_url": undefined, // Important
            "cancel_url": undefined, // Important
            "notify_url": "http://sample.com/notify",
            "order_id": response.order_id,
            "items": response.items,
            "amount": response.amount,
            "currency": "LKR",
            "hash": response.hash,
            "first_name": response.first_name,
            "last_name": response.last_name,
            "email": response.email,
            "phone": response.phone,
            "address": response.address,
            "city": response.city,
            "country": response.country,
            "delivery_address": response.delivery_address,
            "delivery_city": response.delivery_city,
            "delivery_country": response.delivery_country,
            "custom_1": "",
            "custom_2": ""
        };

        payhere.startPayment(payment);

        payhere.onCompleted = function onCompleted(orderId) {
            formData.append('orderId', orderId);
            console.log("Payment completed. OrderID:" + orderId);
            booking(formData);
            // Note: validate the payment and show success or failure page to the customer
        };

        payhere.onDismissed = function onDismissed() {
            // Note: Prompt user to pay again or show an error page
            console.log("Payment dismissed");
        };

        payhere.onError = function onError(error) {
            // Note: show an error page
            console.log("Error:" + error);
        };
    });
}

function booking(formData) {
    PostRequest('/onlinestore/lib/booking-process.php', formData, function (response, error) {
        if (error) {
            alert('Error: ' + error.message);
            console.log(error);
            return;
        }

        alert(response.message);
    })
}


// Listing Management
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

        PostRequest('./lib/add-listing-process.php', form, function (response, error) {
            if (error) {
                alert(error);
                return;
            }

            if (response.status) {
                window.location.href = '/onlinestore/';
            } else {
                alert(response.message);
                console.log(response);
            }
        });
    }
}


// Category Management
function addCategory() {
    var name = document.getElementById('name').value;
    var image = document.getElementById('imageInput').files[0];

    var form = new FormData();
    form.append('name', name);
    form.append('image', image);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            if (response == 'success') {
                window.location.href = '/onlinestore/admin/category/list.php';
            }else {
                alert(response);
            }
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
function deleteCategory(id) {
    var form = new FormData();
    form.append('id', id);

    GetRequest('/onlinestore/lib/delete-category-process.php', form, function (response, error) {
        if (error) {
            alert(error);
            console.log(error);
            return;
        }

        if(response.status) {
            location.reload();
        }
        alert(response.message);
    });
}