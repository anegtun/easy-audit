<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class AuditMeasureValue extends Entity {

    public function hasDifference() {
        return (empty($this->expected) && $this->expected !== 0) - (empty($this->actual) && $this->actual !== 0);
    }

    public function calculateDifference() {
        return $this->hasDifference() ? ($this->expected - $this->actual) : null;
    }

    public function isInThreshold() {
        return abs($this->calculateDifference()) <= $this->threshold;
    }

}