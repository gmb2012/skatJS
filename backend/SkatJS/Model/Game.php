<?php
namespace SkatJS\Model;


class Game extends MongoItem {
    protected $match;
    protected $date;
    protected $scores;
    protected $gameScore;
    protected $bockCount;
    protected $ramschCount;

    public function fromJson(\stdClass $json) {
        $this->setIfSet('id', $json);
        $this->setIfSet('match', $json);
        $this->setIfSet('scores', $json);
        $this->setIfSet('gameScore', $json);
        $this->setIfSet('bockCount', $json);
        $this->setIfSet('ramschCount', $json);

        $this->date = new \DateTime();
    }

    public function toJson() {
        $returnValue = parent::toJson();

        $returnValue->date = ($this->date->getTimestamp() * 1000);

        return $returnValue;
    }

    public function toMongo() {
        $returnValue = parent::toMongo();

        $returnValue['date'] = new \MongoDate($this->date->getTimestamp());
        $returnValue['match'] = new \MongoId($this->match);

        $returnValue['scores'] = array();
        foreach($this->scores as $score) {
            $mongoScore = array();
            $returnValue['scores'][] = array('id' => new \MongoId($score->id), 'score' => $score->score);
        }

        return $returnValue;
    }

    public function fromMongo(array $mongoData) {
        parent::fromMongo($mongoData);

        $this->date = new \DateTime(date('Y-M-d H:i:s', $this->date->sec));
        $this->match = (string) $this->match;

        $scores = array();
        foreach($this->scores as $mongoScore) {
            $score = new \stdClass();
            $score->id = (string) $mongoScore['id'];
            $score->score = $mongoScore['score'];
            $scores[] = $score;
        }

        $this->scores = $scores;
    }

    // GETTERS AND SETTERS
    /**
     * @param mixed $gameScore
     */
    public function setGameScore($gameScore)
    {
        $this->gameScore = $gameScore;
    }

    /**
     * @return mixed
     */
    public function getGameScore()
    {
        return $this->gameScore;
    }

    /**
     * @param mixed $bockCount
     */
    public function setBockCount($bockCount)
    {
        $this->bockCount = $bockCount;
    }

    /**
     * @return mixed
     */
    public function getBockCount()
    {
        return $this->bockCount;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
    }

    /**
     * @return mixed
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param mixed $ramschCount
     */
    public function setRamschCount($ramschCount)
    {
        $this->ramschCount = $ramschCount;
    }

    /**
     * @return mixed
     */
    public function getRamschCount()
    {
        return $this->ramschCount;
    }

    /**
     * @param mixed $scores
     */
    public function setScores($scores)
    {
        $this->scores = $scores;
    }

    /**
     * @return mixed
     */
    public function getScores()
    {
        return $this->scores;
    }
}