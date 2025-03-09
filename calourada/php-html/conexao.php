<?php 
Class Conexao {
    private $pdo;

    /* Conexao com o banco de dados */
    public function __construct() {
        $dbname =  'a202x95xxxx@teiacoltec.org'; //alterar de acordo com o nome do seu db
        $host = 'localhost';
        $user = 'a202x95xxxx@teiacoltec.org'; //alterar de acordo com seu user
        $pass = 'xxxxx'; //alterar de acordo com a sua senha
        $charset = 'utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $pass, $options);
        } catch (PDOException $e) {
            echo "ERRO com banco de dados: ".$e->getMessage();
            exit();
        } catch (Exception $e) {
            echo "ERRO generico: ".$e->getMessage();
            exit();
        }
    }

    public function getPdo() {
        return $this->pdo;
    }
}
?>