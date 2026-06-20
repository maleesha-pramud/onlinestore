const RegexPatterns = {
    name: /^[a-zA-Z\s]+$/,
    mobile: /^07[01245678][0-9]{7}$/,
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    passwordMin5: /^.{5,}$/,
    passwordMin8: /^.{8,}$/,
    nic: /^(?:[0-9]{9}[vVxX]|[0-9]{12})$/
};

function showToast(message, type = 'info', title = '') {
    const toastElement = document.getElementById('liveToast');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');

    toastMessage.innerText = message;
    
    // Set default title if not provided
    if (title === '') {
        switch (type) {
            case 'success': title = 'Success'; break;
            case 'error': case 'danger': title = 'Error'; break;
            case 'warning': title = 'Warning'; break;
            case 'info': title = 'Info'; break;
            default: title = 'Notification';
        }
    }
    toastTitle.innerText = title;

    // Reset classes
    toastElement.classList.remove('toast-success', 'toast-error', 'toast-warning', 'toast-info');

    // Add type class and icon
    let iconClass = '';
    switch (type) {
        case 'success':
            toastElement.classList.add('toast-success');
            iconClass = 'fa-solid fa-circle-check';
            break;
        case 'error':
        case 'danger':
            toastElement.classList.add('toast-error');
            iconClass = 'fa-solid fa-circle-xmark';
            break;
        case 'warning':
            toastElement.classList.add('toast-warning');
            iconClass = 'fa-solid fa-triangle-exclamation';
            break;
        case 'info':
            toastElement.classList.add('toast-info');
            iconClass = 'fa-solid fa-circle-info';
            break;
        default:
            toastElement.classList.add('toast-info');
            iconClass = 'fa-solid fa-circle-info';
    }

    toastIcon.innerHTML = `<i class="${iconClass}"></i>`;

    const toast = new bootstrap.Toast(toastElement, {
        delay: 4000
    });
    toast.show();
}

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

    function showError(msg) {
        document.getElementById('errorMsg').innerHTML = msg;
        document.getElementById('errorMsgDiv').classList.remove('d-none');
    }

    document.getElementById('errorMsgDiv').classList.add('d-none');

    if (!fname) {
        showError('First name is required');
        return;
    } else if (fname.length > 20) {
        showError('First name is too long');
        return;
    } else if (!RegexPatterns.name.test(fname)) {
        showError('First name must contain only letters');
        return;
    }

    if (!lname) {
        showError('Last name is required');
        return;
    } else if (lname.length > 20) {
        showError('Last name is too long');
        return;
    } else if (!RegexPatterns.name.test(lname)) {
        showError('Last name must contain only letters');
        return;
    }

    if (!mobile) {
        showError('Mobile is required');
        return;
    } else if (!RegexPatterns.mobile.test(mobile)) {
        showError('Mobile is not valid');
        return;
    }

    if (!email) {
        showError('Email is required');
        return;
    } else if (!RegexPatterns.email.test(email)) {
        showError('Email is not valid');
        return;
    }

    if (!password) {
        showError('Password is required');
        return;
    } else if (!RegexPatterns.passwordMin5.test(password)) {
        showError('Password must be at least 5 characters long');
        return;
    }

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
                window.location.href = '/signin.php';
            } else {
                showError(response);
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

    function showError(msg) {
        document.getElementById('errorMsg2').innerHTML = msg;
        document.getElementById('errorMsgDiv2').classList.remove('d-none');
    }

    document.getElementById('errorMsgDiv2').classList.add('d-none');

    if (!email) {
        showError('Email is required');
        return;
    } else if (!RegexPatterns.email.test(email)) {
        showError('Email is not valid');
        return;
    }

    if (!password) {
        showError('Password is required');
        return;
    } else if (!RegexPatterns.passwordMin5.test(password)) {
        showError('Password must be at least 5 characters long');
        return;
    }

    var form = new FormData();
    form.append('email', email);
    form.append('password', password);
    form.append('rememberMe', rememberMe);

    PostRequest('./lib/signin-process.php', form, function (response, error) {
        if (error) {
            showError(error);
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
            window.location.href = '/';
        } else {
            showError(response.message);
        }
    });
}

function forgotPassword() {
    var email = document.getElementById('email').value;

    if (!email) {
        showToast('Email is required', 'warning');
        return;
    } else if (!RegexPatterns.email.test(email)) {
        showToast('Email is not valid', 'warning');
        return;
    }

    var form = new FormData();
    form.append('email', email);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var response = req.responseText;
            if (response === 'Message has been sent') {
                showToast('Password reset link sent to your email!', 'success');
            } else {
                showToast(response, 'error');
            }
        }
    }

    req.open('POST', './lib/forgot-password-process.php', true);
    req.send(form);
}

function resetPassword() {
    var password = document.getElementById('password').value;
    var cPassword = document.getElementById('cPassword').value;
    var vcode = document.getElementById('vcode').value;

    if (!password) {
        showToast('Password is required', 'warning');
        return;
    } else if (!RegexPatterns.passwordMin8.test(password)) {
        showToast('Password must be at least 8 characters long', 'warning');
        return;
    }

    if (password !== cPassword) {
        showToast('Passwords do not match', 'warning');
        return;
    }

    if (!vcode) {
        showToast('Please resend a forgot password request', 'warning');
        return;
    }

    var form = new FormData();
    form.append('password', password);
    form.append('cPassword', cPassword);
    form.append('vcode', vcode);

    PostRequest('./lib/reset-password-process.php', form, function (response, error) {
        if (error) {
            showToast(error, 'error');
            return;
        }

        if (response.status) {
            window.location.href = '/signin.php';
        } else {
            showToast(response.message, 'error');
        }
    });
}

function checkAvailability(listingId) {
    var checkIn = document.getElementById('checkIn').value;
    var checkOut = document.getElementById('checkOut').value;
    var guestsInput = document.getElementById('guests');
    var guests = guestsInput.value;
    var maxGuests = parseInt(guestsInput.getAttribute('max'));

    if (!checkIn) {
        showToast('Please select a check-in date', 'warning');
        return;
    } else if (!checkOut) {
        showToast('Please select a check-out date', 'warning');
        return;
    } else if (!guests || guests < 1) {
        showToast('Please enter a valid number of guests (at least 1)', 'warning');
        return;
    } else if (guests > maxGuests) {
        showToast('Maximum capacity for this property is ' + maxGuests + ' guests', 'warning');
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
                window.location.href = '/booking.php?id=' + listingId + '&checkIn=' + checkIn + '&checkOut=' + checkOut + '&guests=' + guests;
            } else {
                showToast(response, 'error');
            }
        }
    }

    req.open('POST', '/lib/check-availability-process.php', true);
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
        showToast("First name is required.", 'warning');
        return;
    } else if (fname.length > 20) {
        showToast("First name is too long.", 'warning');
        return;
    } else if (!RegexPatterns.name.test(fname)) {
        showToast("First name must contain only letters.", 'warning');
        return;
    }

    if (!lname) {
        showToast("Last name is required.", 'warning');
        return;
    } else if (lname.length > 20) {
        showToast("Last name is too long.", 'warning');
        return;
    } else if (!RegexPatterns.name.test(lname)) {
        showToast("Last name must contain only letters.", 'warning');
        return;
    }

    if (!nic) {
        showToast("NIC is required.", 'warning');
        return;
    } else if (!RegexPatterns.nic.test(nic)) {
        showToast("NIC is not valid.", 'warning');
        return;
    }

    if (!contact) {
        showToast("Contact number is required.", 'warning');
        return;
    } else if (!RegexPatterns.mobile.test(contact)) {
        showToast("Contact number must be a valid mobile number (e.g. 0771234567).", 'warning');
        return;
    }

    if (!guests || !/^\d+$/.test(guests)) {
        showToast("Guests count must be a valid number.", 'warning');
        return;
    }

    if (!email) {
        showToast("Email address is required.", 'warning');
        return;
    } else if (!RegexPatterns.email.test(email)) {
        showToast("Valid email address is required.", 'warning');
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

    PostRequest('/lib/checkout-process.php', formData, function (response, error) {
        if (error) {
            showToast(error, 'error');
            console.log(error);
            return;
        }
        var payment = {
            "sandbox": true,
            "merchant_id": response.merchant_id,
            "return_url": window.location.origin,
            "cancel_url": window.location.origin,
            "notify_url": window.location.origin + "/lib/payment-notify-process.php",
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
    PostRequest('/lib/booking-process.php', formData, function (response, error) {
        if (error) {
            showToast(error, 'error');
            console.log(error);
            return;
        }

        if (response.status) {
            window.location.href = '/';
        } else {
            showToast(response.message, 'error');
        }
    })
}


// Listing Management
function addListing(userId) {
    var title = document.getElementById('title').value;
    var category = document.getElementById('category').value;
    const description = tinymce.get('description').getContent();
    var address = document.getElementById('address').value;
    var guests = document.getElementById('guests').value;
    var bedrooms = document.getElementById('bedrooms').value;
    var beds = document.getElementById('beds').value;
    var bathrooms = document.getElementById('bathrooms').value;
    var images = document.getElementById('images').files;
    var amenities = document.getElementsByName('amenities[]');
    var basePrice = document.getElementById('basePrice').value;

    // Validate the values before appending to FormData
    if (!title) {
        showToast('Please enter a title', 'warning');
    } else if (!category) {
        showToast('Please select a category', 'warning');
    } else if (!description) {
        showToast('Please enter a description', 'warning');
    } else if (!address) {
        showToast('Please enter an address', 'warning');
    } else if (!guests) {
        showToast('Please enter the number of guests', 'warning');
    } else if (!bedrooms) {
        showToast('Please enter the number of bedrooms', 'warning');
    } else if (!beds) {
        showToast('Please enter the number of beds', 'warning');
    } else if (!bathrooms) {
        showToast('Please enter the number of bathrooms', 'warning');
    } else if (images.length === 0) {
        showToast('Please select at least one image', 'warning');
    } else if (!basePrice) {
        showToast('Please enter the base price', 'warning');
    } else {
        var form = new FormData();
        form.append('userId', userId);
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
                form.append('amenities[]', amenities[i].value);
            }
        }

        PostRequest('/lib/add-listing-process.php', form, function (response, error) {
            if (error) {
                showToast(error, 'error');
                return;
            }

            if (response.status) {
                window.location.href = '/';
            } else {
                showToast(response.message, 'error');
                console.log(response);
            }
        });
    }
}
function editListing(propertyId) {
    var title = document.getElementById('title').value;
    var category = document.getElementById('category').value;
    const description = tinymce.get('description').getContent();
    var address = document.getElementById('address').value;
    var guests = document.getElementById('guests').value;
    var bedrooms = document.getElementById('bedrooms').value;
    var beds = document.getElementById('beds').value;
    var bathrooms = document.getElementById('bathrooms').value;
    var amenities = document.getElementsByName('amenities[]');
    var basePrice = document.getElementById('basePrice').value;
    var keptImages = document.getElementById('kept-images').value;
    var newImages = document.getElementById('images').files;

    // Validate the values before appending to FormData
    if (!title) {
        showToast('Please enter a title', 'warning');
    } else if (!category) {
        showToast('Please select a category', 'warning');
    } else if (!description) {
        showToast('Please enter a description', 'warning');
    } else if (!address) {
        showToast('Please enter an address', 'warning');
    } else if (!guests) {
        showToast('Please enter the number of guests', 'warning');
    } else if (!bedrooms) {
        showToast('Please enter the number of bedrooms', 'warning');
    } else if (!beds) {
        showToast('Please enter the number of beds', 'warning');
    } else if (!bathrooms) {
        showToast('Please enter the number of bathrooms', 'warning');
    } else if (!basePrice) {
        showToast('Please enter the base price', 'warning');
    } else if (!keptImages && newImages.length === 0) {
        showToast('Please select at least one image', 'warning');
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
        form.append('propertyId', propertyId);
        form.append('keptImages', keptImages);
        
        for (var i = 0; i < newImages.length; i++) {
            form.append('newImages[]', newImages[i]);
        }
        
        for (var i = 0; i < amenities.length; i++) {
            if (amenities[i].checked) {
                form.append('amenities[]', amenities[i].value);
            }
        }

        PostRequest('/lib/edit-listing-process.php', form, function (response, error) {
            if (error) {
                showToast(error, 'error');
                return;
            }

            if (response.status) {
                window.location.href = '/listing/list.php';
            } else {
                showToast(response.message, 'error');
                console.log(response);
            }
        });
    }
}

function removeExistingImage(btn) {
    const wrapper = btn.closest('.current-image-wrapper');
    const imageName = wrapper.getAttribute('data-image-name');
    const keptImagesInput = document.getElementById('kept-images');
    
    let images = keptImagesInput.value.split(',');
    images = images.filter(img => img !== imageName && img !== "");
    
    keptImagesInput.value = images.join(',');
    wrapper.remove();
}
function deleteListing(id) {
    var form = new FormData();
    form.append('id', id);

    GetRequest('/lib/delete-listing-process.php', form, function (response, error) {
        if (error) {
            showToast(error, 'error');
            console.log(error);
            return;
        }

        if (response.status) {
            location.reload();
        } else {
            showToast(response.message, 'error');
        }
    });
}

// Category Management
function addCategory() {
    var name = document.getElementById('name').value;
    var imageInput = document.getElementById('imageInput');
    var image = null;

    if (imageInput.files.length > 0) {
        image = imageInput.files[0];
    } else {
        showToast('Please select an image', 'warning');
        return;
    }

    var form = new FormData();
    form.append('name', name);
    form.append('image', image);

    PostRequest('/lib/add-category-process.php', form, function (response, error) {
        if (response) {
            if (response.status) {
                window.location.href = '/admin/category/list.php';
            } else {
                showToast(response.message, 'error');
            }
        } else {
            showToast(error, 'error');
        }
    });
}
function editCategory() {
    var id = document.getElementById('id').value;
    var name = document.getElementById('name').value;
    var imageInput = document.getElementById('imageInput');

    var form = new FormData();
    form.append('id', id);
    form.append('name', name);

    // Check if a new image has been selected
    if (imageInput.files.length > 0) {
        form.append('image', imageInput.files[0]);
    }

    PostRequest('/lib/edit-category-process.php', form, function (response, error) {
        if (response) {
            if (response.status) {
                window.location.href = '/admin/category/list.php';
            } else {
                showToast(response.message, 'error');
            }
        } else {
            showToast(error, 'error');
        }
    });
}
function deleteCategory(id) {
    var form = new FormData();
    form.append('id', id);

    GetRequest('/lib/delete-category-process.php', form, function (response, error) {
        if (error) {
            showToast(error, 'error');
            console.log(error);
            return;
        }

        if (response.status) {
            location.reload();
        } else {
            showToast(response.message, 'error');
        }
    });
}

function updateBooking() {
    var id = document.getElementById('editBookingId').value;
    var fname = document.getElementById('editFirstName').value;
    var lname = document.getElementById('editLastName').value;
    var nic = document.getElementById('editNIC').value;
    var contact = document.getElementById('editContact').value;
    var specialRequests = document.getElementById('editSpecialRequests').value;

    if (!fname || !lname || !nic || !contact) {
        showToast('Please fill all required fields', 'warning');
        return;
    }

    var form = new FormData();
    form.append('id', id);
    form.append('fname', fname);
    form.append('lname', lname);
    form.append('nic', nic);
    form.append('contact', contact);
    form.append('specialRequests', specialRequests);

    PostRequest('/lib/edit-booking-process.php', form, function (response, error) {
        if (error) {
            showToast(error, 'error');
            return;
        }

        if (response.status) {
            showToast('Booking updated successfully', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(response.message, 'error');
        }
    });
}

function deleteBooking(id) {
    if (!confirm('Are you sure you want to cancel this booking?')) {
        return;
    }

    var form = new FormData();
    form.append('id', id);

    PostRequest('/lib/delete-booking-process.php', form, function (response, error) {
        if (error) {
            showToast(error, 'error');
            return;
        }

        if (response.status) {
            showToast('Booking cancelled successfully', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(response.message, 'error');
        }
    });
}

function addReview(propertyId) {
    var rating = document.querySelector('input[name="rating"]:checked');
    var comment = document.getElementById('reviewComment').value;

    if (!rating) {
        showToast('Please select a rating', 'warning');
        return;
    }

    if (!comment) {
        showToast('Please enter a comment', 'warning');
        return;
    }

    var form = new FormData();
    form.append('propertyId', propertyId);
    form.append('rating', rating.value);
    form.append('comment', comment);

    PostRequest('/lib/add-review-process.php', form, function (response, error) {
        if (error) {
            showToast(error, 'error');
            return;
        }

        if (response.status) {
            showToast(response.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(response.message, 'error');
        }
    });
}

// Favorites Functionality (LocalStorage Only)
function toggleFavorite(propertyId, element) {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    const index = favorites.indexOf(propertyId);

    if (index === -1) {
        favorites.push(propertyId);
        showToast('Added to favorites', 'success');
        updateFavoriteUI(element, true);
    } else {
        favorites.splice(index, 1);
        showToast('Removed from favorites', 'info');
        updateFavoriteUI(element, false);
    }

    localStorage.setItem('favorites', JSON.stringify(favorites));
}

// Cart Functionality (Database)
function addToCart(propertyId) {
    var checkIn = document.getElementById('checkIn').value;
    var checkOut = document.getElementById('checkOut').value;
    var guestsInput = document.getElementById('guests');
    var guests = guestsInput.value;
    var maxGuests = parseInt(guestsInput.getAttribute('max'));

    if (!checkIn) {
        showToast('Please select a check-in date', 'warning');
        return;
    } else if (!checkOut) {
        showToast('Please select a check-out date', 'warning');
        return;
    } else if (!guests || guests < 1) {
        showToast('Please enter a valid number of guests (at least 1)', 'warning');
        return;
    } else if (guests > maxGuests) {
        showToast('Maximum capacity for this property is ' + maxGuests + ' guests', 'warning');
        return;
    }

    var form = new FormData();
    form.append('propertyId', propertyId);
    form.append('checkIn', checkIn);
    form.append('checkOut', checkOut);
    form.append('guests', guests);

    PostRequest('/lib/add-to-cart-process.php', form, function (response, error) {
        if (error) {
            showToast(error, 'error');
            return;
        }

        if (response.status) {
            showToast(response.message, 'success');
        } else {
            showToast(response.message, 'error');
        }
    });
}

function removeFromCart(cartId) {
    var form = new FormData();
    form.append('id', cartId);

    PostRequest('/lib/remove-from-cart-process.php', form, function (response, error) {
        if (error) {
            showToast(error, 'error');
            return;
        }

        if (response.status) {
            showToast(response.message, 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast(response.message, 'error');
        }
    });
}

function updateFavoriteUI(element, isFavorite) {
    const icon = element.querySelector('i');
    if (icon) {
        if (icon.classList.contains('bi')) {
            // Bootstrap Icons
            icon.classList.toggle('bi-heart', !isFavorite);
            icon.classList.toggle('bi-heart-fill', isFavorite);
            icon.classList.toggle('text-danger', isFavorite);
        } else {
            // FontAwesome
            icon.classList.toggle('fa-regular', !isFavorite);
            icon.classList.toggle('fa-solid', isFavorite);
            icon.classList.toggle('text-danger', isFavorite);
        }
    }
}

function initFavorites() {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    document.querySelectorAll('[data-favorite-id]').forEach(btn => {
        const id = parseInt(btn.getAttribute('data-favorite-id'));
        if (favorites.includes(id)) {
            updateFavoriteUI(btn, true);
        }
    });
}

// Share Functionality
function shareLink(title, text, url) {
    if (navigator.share) {
        navigator.share({
            title: title,
            text: text,
            url: url || window.location.href
        }).then(() => {
            console.log('Successful share');
        }).catch((error) => {
            console.log('Error sharing', error);
        });
    } else {
        // Fallback: Copy to clipboard
        const shareUrl = url || window.location.href;
        navigator.clipboard.writeText(shareUrl).then(() => {
            showToast('Link copied to clipboard!', 'success');
        }).catch(err => {
            showToast('Failed to copy link', 'error');
            console.error('Could not copy text: ', err);
        });
    }
}

document.addEventListener('DOMContentLoaded', initFavorites);