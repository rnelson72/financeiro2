<?php
class Controle {

  private $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo; 
  }
  
  public function listarTodosComSaldo() {
      $stmt = $this->pdo->query("SELECT c.*, 
        (SELECT COALESCE(SUM(valor),0) FROM lancamentos WHERE controle_id = c.id) AS saldo 
        FROM controle c WHERE c.ativo = 1 ORDER BY c.grupo_id, c.descricao");
      return $stmt->fetchAll();
  }
}
?>