<?php
class Controle {

  private $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo; 
  }
  
  public function listarTodosComSaldo() {
      $stmt = $this->pdo->query("SELECT * FROM vw_controle 
                                ORDER BY  
                                CASE WHEN ativo = 1 AND grupo_id IS NULL THEN 0
                                    WHEN ativo = 1 AND grupo_id IS NOT NULL THEN 1
                                    ELSE 2
                                END,
                                grupo, descricao");
       -- Ordem: desagrupados ativos, agrupados ativos, desativados
  return $stmt->fetchAll();
  }
}
?>