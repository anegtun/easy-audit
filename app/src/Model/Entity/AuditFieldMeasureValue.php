<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class AuditFieldMeasureValue extends Entity {

    public function calculateDifference() {
        return $this->expected - $this->actual;
    }

    public function isInThreshold() {
        return abs($this->calculateDifference()) <= $this->threshold;
    }

}