<!DOCTYPE html>
<html>
<head>
    <title>Word Puzzle Game</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Word Puzzle Game</h2>
    <div id="game-info">
        <p><strong>Random String:</strong> <span id="random-string"></span></p>
        <div id="remaining-letters"></div>
        <form id="word-form">
            <div class="form-group">
                <label for="word">Enter Word:</label>
                <input type="text" class="form-control" id="word" name="word" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <div id="words-list">
        <h3>Words</h3>
        <ul id="submitted-words"></ul>
    </div>
    <div id="score">
        <h3>Score: <span id="score-value">0</span></h3>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    const student = JSON.parse(localStorage.getItem('student'));
    const randomString = localStorage.getItem('randomString');
    let remainingLetters = randomString;
    let score = 0;

    $('#random-string').text(randomString);
    $('#remaining-letters').text('Remaining Letters: ' + remainingLetters);

    $('#word-form').on('submit', function(e) {
        e.preventDefault();
        const word = $('#word').val();

        // Check if the word is valid using the dictionary API
        $.ajax({
            url: 'https://api.dictionaryapi.dev/api/v2/entries/en/' + word,
            type: 'GET',
            success: function(response) {
                // If valid, submit the word to the backend
                $.ajax({
                    url: '/submit',
                    type: 'POST',
                    data: {
                        student_id: student.id,
                        random_string_id: randomString.id,
                        word: word,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#submitted-words').append('<li>' + word + ' (Score: ' + response.score + ')</li>');
                        score += response.score;
                        $('#score-value').text(score);
                        remainingLetters = response.remaining_letters;
                        $('#remaining-letters').text('Remaining Letters: ' + remainingLetters);
                        $('#word').val('');
                    },
                    error: function(response) {
                        alert('Submission failed: ' + response.responseJSON.error);
                    }
                });
            },
            error: function(response) {
                alert('Invalid word: ' + word);
            }
        });
    });
});
</script>
</body>
</html>
