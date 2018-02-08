<?php


class LocationBeverage extends Dietary {

  protected $table = "location_beverage";

  public function fetchBeverages($location) {
    $beverage_list = $this->loadTable('BeverageList');

    $sql = "SELECT bl.id, lb.public_id, bl.name FROM {$this->tableName()} lb INNER JOIN {$beverage_list->tableName()} bl ON bl.id = lb.beverage_id WHERE lb.location_id = :location";
    $params[':location'] = $location;

    return $this->fetchAll($sql, $params);
  }
}
