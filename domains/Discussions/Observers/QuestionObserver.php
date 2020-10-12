<?php


namespace Domains\Discussions\Observers;


use Domains\Discussions\Models\Question;
use Illuminate\Support\Str;

class QuestionObserver
{
    public function creating(Question $question)
    {
        $this->calculateSlug($question);
    }

    public function updating(Question $question)
    {
        $this->calculateSlug($question);
    }

    private function calculateSlug(Question $question)
    {
        $question->slug = Str::slug($question->title);
    }
}
