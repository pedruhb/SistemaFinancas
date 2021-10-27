function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function getCsrfToken() {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; csrf-token=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

$(document).ready(function() {
    $("[name='csrf-token']").val(getCsrfToken());
});