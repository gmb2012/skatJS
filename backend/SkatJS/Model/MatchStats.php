<?php
namespace SkatJS\Model;

class MatchStats extends Base {
    protected $gameCount;
    protected $scores;

    public function __construct(array $games) {
        $this->gameCount = count($games);
        $this->scores = new \stdClass();

        // calculate scores for players
        foreach($games as $game) {
            foreach($game->getScores() as $score) {
                if(!isset($this->scores->{$score->id})) {
                    $this->scores->{$score->id} = 0;
                }

                $this->scores->{$score->id} += $score->score;
            }
        }
    }
}