<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class AuditMeasureValue extends Entity {

    public function hasDifference() {
        return $this->expected !== null && $this->actual !== null;
    }

    public function calculateDifference() {
        return $this->hasDifference() ? ($this->expected - $this->actual) : null;
    }

    public function isInThreshold() {
        return abs($this->calculateDifference()) <= $this->threshold;
    }

}