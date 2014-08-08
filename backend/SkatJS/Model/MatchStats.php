<?php
/**
 * Created by PhpStorm.
 * User: bzapadlo
 * Date: 08/08/14
 * Time: 14:10
 */

namespace SkatJS\Model;


class MatchStats {
    private $gameCount;
    private $scores;

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

    public function toJson() {
        $returnValue = new \stdClass();

        foreach(get_object_vars($this) as $varName => $value) {
            $returnValue->{$varName} = $value;
        }

        return $returnValue;
    }
} 