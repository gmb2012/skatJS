<?php
namespace SkatJS\Model;

class Ranking extends Base {
    protected $ranking;

    public function __construct(array $players, array $games) {
        $this->ranking = array();

        // add all players
        foreach($players as $player) {
            $this->ranking[$player->getId()]         =  new \stdClass();
            $this->ranking[$player->getId()]->id     = $player->getId();
            $this->ranking[$player->getId()]->name   = $player->getName();
            $this->ranking[$player->getId()]->score  = 0;
        }

        // calculate scores for players
        foreach($games as $game) {
            foreach($game->getScores() as $score) {
                $this->ranking[$score->id]->score += $score->score;
            }
        }

        usort($this->ranking, array($this, 'compare'));
    }

    private function compare($a, $b) {
        if ($a->score === $b->score) {
            return 0;
        }

        return ($a->score < $b->score) ? 1 : -1;
    }

    public function toJson() {
        return parent::toJson()->ranking;
    }
}