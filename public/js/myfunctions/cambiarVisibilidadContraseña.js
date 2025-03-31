function changeVisibilityPassword() {
    const type = $('#contrasena').attr('type');
    const input = $('#contrasena');
    const icon = $('#icon-eye-password');
    if (type == 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye');
        icon.addClass('fa-eye-slash');
    } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash');
        icon.addClass('fa-eye');
    }
}