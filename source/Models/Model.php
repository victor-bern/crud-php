<?php


namespace Source\Models;


use Source\Database\Connect;

abstract class Model
{
    /**
     * @var object|null
     */
    protected $data;
    /**
     * @var string|null
     */
    protected $message;
    /**
     * @var \PDOException|null
     */
    protected $fail;
    /**
     * @var PDO|null
     */
    protected $pdo;

    /**
     * @return object|null
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * @return string|null
     */
    public function message(): ?string
    {
        return $this->message;
    }

    /**
     * @return \PDOException|null
     */
    public function fail(): ?\PDOException
    {
        return $this->fail;
    }

    public function __set($name, $value)
    {

        if (empty($this->data)) {
            $this->data = new \StdClass();
        }

        $this->data->$name = $value;
    }

    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    protected function create(string $entity, array $data): ?int
    {
        try {
            $this->pdo = new Connect();
            $keys = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            $stmt = $this->pdo->Connect()->prepare("INSERT INTO {$entity} ({$keys}) VALUES ({$values})");
            $stmt->execute($this->filter($data));

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }

        return $this->pdo->Connect()->lastInsertId();

    }

    protected function read(string $sql, string $params = null): ?\PDOStatement
    {
        try {
            $this->pdo = new Connect();
            $stmt = $this->pdo->Connect()->prepare($sql);
            if ($params) {
                parse_str($params, $params);
                foreach ($params as $key => $value) {
                    $type = (is_numeric($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
                    $stmt->bindValue(":{$key}", $value, $type);
                }
            }
            $stmt->execute();
            return $stmt;
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }

    }

    protected function update(string $entity, array $data, string $terms,
                              string $params): ?int
    {
        try {
            $this->pdo = (new Connect())->Connect();
            $dataSet = [];
            foreach ($data as $bind => $value) {
                $dataSet[] = "{$bind} = :{$bind}";
            }
            $dataSet = implode(", ", $dataSet);
            parse_str($params, $params);


            $stmt = $this->pdo->prepare("UPDATE {$entity} SET {$dataSet} WHERE {$terms}");
            $stmt->execute($this->filter(array_merge($data, $params)));
            return ($stmt->rowCount() ?? 1);

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }

    }

    protected function delete(string $terms, string $params): ?int
    {
        try {
            $this->pdo = new Connect();
            $stmt = $this->pdo->Connect()->prepare("DELETE FROM {$this->table} WHERE {$terms}");
            parse_str($params, $params);
            $stmt->execute($this->filter($params));

            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }

    }

    protected function safe(): ?array
    {

        $safe = (array)$this->data;
        foreach (static::$safe as $unset) {
            unset($safe[$unset]);
        }
        return $safe;
    }

    protected
    function filter(array $data)
    {
        $filter = [];

        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value,
                FILTER_SANITIZE_SPECIAL_CHARS));
        }
        return $filter;
    }
}