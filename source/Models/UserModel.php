<?php


namespace Source\Models;


class UserModel extends Model
{
    /**
     * @var array
     */
    protected static array $safe = ["id", "created_at", "updated_at"];
    /**
     * @var string
     */
    protected $table = "users";


    public function bootstrap(string $name, string $email, string $password,
                              string $img = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->img = $img;
        return $this;
    }

    public function findById(int $id, string $column = "*"): ?UserModel
    {
        $load = $this->read("SELECT * FROM {$this->table} WHERE id 
        = :id", "id={$id}");

        if ($this->fail() || !$load->rowCount()) {
            $this->message = "Usuário não encontrado com este id";
            return null;
        }
        return $load->fetchObject(__CLASS__);
    }

    public function findByEmail(string $email, string $column = "*")
    {
        $email = $this->read("SELECT {$column} FROM {$this->table} WHERE email = :email", "email={$email}");
        if ($this->fail() || !$email->rowCount()) {
            $this->message = "Usuário não encontrado com este id";
            return null;
        }
        return $email->fetchObject(__CLASS__);
    }

    public function all(): ?array
    {
        $all = $this->read("SELECT * FROM users");
        if ($this->fail() || !$all->rowCount()) {
            $this->message = "Houve um erro";
            return null;
        }
        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function save()
    {
        if (!$this->required()) {
            return null;
        }
        if (!$this->id) {
            if ($this->findByEmail($this->email)) {
                $this->fail = new \PDOException();
                $this->message = "Email ja existente no banco";
                return null;
            }


            $userID = $this->create($this->table, $this->safe());
            if ($this->fail()) {
                $this->fail = new \PDOException();
                $this->message = "Houve um problema ao criar usuário";
                return null;
            }
            $this->message = "Usuário criado com sucesso";
        }
        if ($this->id) {
            $userID = $this->id;

            $email = $this->read("SELECT id FROM users WHERE email = :email AND id != :id", "email={$this->email}&id={$userID}");

            if ($email->rowCount()) {
                $this->fail = new \PDOException();
                $this->message = "O e-mail informado já está cadastrado";
                return null;
            }

            $this->update($this->table, $this->safe(), "id = :id", "id={$userID}");
            if ($this->fail()) {
                $this->message = "Erro ao atualizar, verifique os dados";
            }
            $this->message = "Dados atualizados com sucesso";
        }
        $this->data = $this->read("SELECT * FROM users WHERE id = :id", "id={$userID}");
        return $this;
    }

    public function destroy(): ?UserModel
    {
        $this->delete("id = :id", "id={$this->id}");
        if ($this->fail()) {
            $this->message = "Ocorreu um erro!";
        }
        $this->data = null;
        $this->message = "Usuário removido com sucesso";
        return $this;
    }

    public function required(): bool
    {
        if (empty($this->name || $this->email || $this->password)) {
            $this->fail = new \PDOException();
            $this->message = "Informe todos os campos!";
            return false;
        }

        if (!filter_var($this->data->email, FILTER_VALIDATE_EMAIL)) {
            $this->fail = new \PDOException();
            $this->message = "Email informado não é válido";
            return false;
        }
        return true;
    }

}