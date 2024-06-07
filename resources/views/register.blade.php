<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Register</h2>
    <form id="registration-form">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#registration-form').on('submit', function(e) {
        e.preventDefault();
        alert("Form will be submtted");
        const name = $('#name').val();
        const email = $('#email').val();

        $.ajax({
            url: "{{route('register')}}",
            type: 'POST',
            data: {
                name: name,
                email: email,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                localStorage.setItem('student', JSON.stringify(response.student));
                localStorage.setItem('randomString', response.random_string);
                window.location.href = '/game';
            },
            error: function(response) {
                alert('Registration failed: ' + response.responseJSON.errors);
            }
        });
    });
});
</script>
</body>
</html>
