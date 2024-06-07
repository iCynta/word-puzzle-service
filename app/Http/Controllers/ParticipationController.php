<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participation;
use App\Models\Word;
use Illuminate\Support\Facades\DB;

class ParticipationController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'random_string_id' => 'required|exists:random_strings,id',
            'word' => 'required|string',
        ]);

        $participation = Participation::where('student_id', $request->student_id)
            ->where('random_string_id', $request->random_string_id)
            ->firstOrFail();

        $puzzleString = $participation->randomString->random_string;
        $submittedWord = $request->word;

        // Check if the word is valid and can be formed from the puzzle string
        if (!$this->isValidWord($submittedWord, $puzzleString)) {
            return response()->json(['error' => 'Invalid word or word cannot be formed from the puzzle string'], 400);
        }

        // Check if the word has already been submitted
        if (Word::where('participation_id', $participation->id)->where('word', $submittedWord)->exists()) {
            return response()->json(['error' => 'Word has already been submitted'], 400);
        }

        // Calculate score
        $score = strlen($submittedWord);

        // Store the word
        Word::create([
            'participation_id' => $participation->id,
            'word' => $submittedWord,
            'score' => $score,
        ]);

        // Update participation score
        $participation->increment('score', $score);

        // Update remaining letters
        $remainingLetters = $this->getRemainingLetters($puzzleString, $submittedWord);

        return response()->json(['message' => 'Submission received', 'score' => $score, 'remaining_letters' => $remainingLetters]);
    }

    public function leaderboard()
    {
        $highScores = DB::table('words')
            ->join('participations', 'words.participation_id', '=', 'participations.id')
            ->join('students', 'participations.student_id', '=', 'students.id')
            ->select('students.name', 'students.email', 'words.word', 'words.score')
            ->orderBy('words.score', 'desc')
            ->take(10)
            ->get();

        return response()->json($highScores);
    }

    private function isValidWord($word, $puzzleString)
    {
        // Check if word is a valid English word (use a dictionary API or local dictionary)
        // For simplicity, let's assume all submitted words are valid

        // Check if word can be formed from the puzzle string
        $puzzleLetters = count_chars($puzzleString, 1);
        $wordLetters = count_chars($word, 1);

        foreach ($wordLetters as $letter => $count) {
            if (!isset($puzzleLetters[$letter]) || $puzzleLetters[$letter] < $count) {
                return false;
            }
        }

        return true;
    }

    private function getRemainingLetters($puzzleString, $submittedWord)
    {
        $remainingLetters = $puzzleString;

        foreach (count_chars($submittedWord, 1) as $letter => $count) {
            $remainingLetters = preg_replace("/" . chr($letter) . "/", "", $remainingLetters, $count);
        }

        return $remainingLetters;
    }
}
