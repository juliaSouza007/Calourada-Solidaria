# Sistema de Registro e Contagem de Doações para a prova Solidária da calourada do COLTEC

Este é um sistema desenvolvido em PHP, HTML e MySQL para registrar doações de itens em diferentes salas e visualizar os registros de doações. O sistema também permite que os administradores e alunos acompanhem a contagem das doações arrecadadas na prova de Arecadação Solidária.

## Funcionalidades

1. **Registro de Itens para Doação**
   - O usuário pode registrar itens para doação, especificando o nome, tipo de item, quantidade e, para alguns itens, o nome do alimento.
   - O sistema possui um limite de 15 unidades para os tipos de itens "alimento" e "higiene", que é verificado antes de permitir o registro.
   - Itens são registrados nas salas 101 a 106, e o usuário pode escolher a sala na qual deseja registrar o item.

2. **Consulta de Registros**
   - Os administradores e outros usuários podem consultar os registros de doações por sala.
   - A interface permite selecionar uma ou várias salas para visualizar os registros de itens, com a exibição da quantidade total de itens registrados por sala.

## Pré-requisitos

Antes de rodar o sistema, é necessário ter um ambiente de desenvolvimento com as seguintes ferramentas:

- **PHP 7.4 ou superior**
- **Servidor Apache ou similar** (se estiver usando o XAMPP, MAMP, ou similar)
- **MySQL ou MariaDB**
- **CSS para estilização** (arquivo de estilo está presente na pasta `css/`)

## Estrutura do Banco de Dados

O banco de dados utiliza tabelas com a seguinte estrutura para registrar doações nas salas:

- Tabelas de salas (101, 102, 103, 104, 105, 106) com a seguinte estrutura:
  - `id INT AUTO_INCREMENT PRIMARY KEY`
  - `nome VARCHAR(100) NOT NULL`
  - `tipo_item ENUM('roupa', 'higiene', 'alimento', 'brinquedo', 'sapato', 'livro') NOT NULL`
  - `quantidade INT NOT NULL`
  - `nome_item VARCHAR(100)`
  - `data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP`

## Como Rodar o Sistema

### Configuração do Banco de Dados

Antes de rodar o sistema, você deve configurar a conexão com o banco de dados no arquivo `conexao.php`. Substitua os valores de conexão (nome do banco de dados, usuário, e senha) pelos dados corretos de sua instância MySQL.

```php
public function __construct() {
    $dbname =  'a202x95xxxx@teiacoltec.org'; // Altere para o nome do seu banco
    $host = 'localhost';
    $user = 'a202x95xxxx@teiacoltec.org'; // Altere para o seu usuário
    $pass = 'xxxxx'; // Altere para a sua senha
    $charset = 'utf8mb4';
}
```
## Sucesso!!
![Calourada 2025](calourada2025.jpg)
