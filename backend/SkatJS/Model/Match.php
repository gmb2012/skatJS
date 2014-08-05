<?php
namespace SkatJS\Model;

// collection of games
class Match extends MongoItem {
    protected $date;
    protected $players;

    public function fromJson(\stdClass $json) {
        $this->setIfSet('id', $json);
        $this->setIfSet('players', $json);
        $this->date = new \DateTime();
    }

    public function toMongo() {
        $returnValue = parent::toMongo();

        $returnValue['date'] = new \MongoDate($this->date->getTimestamp());

        $returnValue['players'] = array();
        foreach($this->players as $id) {
            $returnValue['players'][] = new \MongoId($id);
        }

        return $returnValue;
    }

    public function toJson() {
        $returnValue = parent::toJson();

        $returnValue->date = ($this->date->getTimestamp() * 1000);

        return $returnValue;
    }

    public function fromMongo(array $mongoData) {
        parent::fromMongo($mongoData);

        $this->date = new \DateTime(date('Y-M-d H:i:s', $this->date->sec));

        $players = array();
        foreach($this->players as $id) {
            $players[] = (string) $id;
        }

        $this->players = $players;
    }

    // GETTERS AND SETTERS
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
     * @param mixed $players
     */
    public function setPlayers($players)
    {
        $this->players = $players;
    }

    /**
     * @return mixed
     */
    public function getPlayers()
    {
        return $this->players;
    }


} 