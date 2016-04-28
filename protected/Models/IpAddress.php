<?php

class IpAddress extends AppData {
  protected $table = 'ip_address';



/*
 * -----------------------------------------------------------------------------
 * LOOK FOR THE IP address
 * -----------------------------------------------------------------------------
 * Check the users' current IP address with the stored values.
 *
 */
  public function fetchByIp($ip) {
    $sql = "SELECT * FROM {$this->tableName()} WHERE ip = :ip_address LIMIT 1";
    $params[":ip_address"] = $ip;
    return $this->fetchOne($sql, $params);
  }
}
