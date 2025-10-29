<?php
namespace App;
use Aura\SqlQuery\Mysql\Delete;
use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{
    private $pdo;
    private $queryFactory;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=MySQL-8.0;dbname=app2;", "root", "");
        $this->queryFactory = new QueryFactory('mysql');
    }

    public function getAll($table)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table);

        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert($data, $table)
    {
        $insert = $this->queryFactory->newInsert();
        $insert
            ->into($table)                   // INTO this table
            ->cols($data);

        $stmt = $this->pdo->prepare($insert->getStatement());
        $stmt->execute($insert->getBindValues());
    }

    public function update($data, $id, $table)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)                  // update this table
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);
        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());
    }

    public function getOne($table, $id)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($table, $id)
    {
        $delete = $this->queryFactory->newDelete();
        $delete
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $stmt = $this->pdo->prepare($delete->getStatement());
        $stmt->execute($delete->getBindValues());
    }
}