<?php
namespace App\Model;

use App\Entity\User;
use Fagathe\Framework\Database\AbstractModel;

class UserModel extends AbstractModel
{

    protected $table = "user";

    protected $class = User::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function findOneByUsername(string $username): ?User
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username OR email = :username LIMIT 1;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        if ($stmt->rowCount() === 1) {
            return $this->class !== "" ? new $this->class($stmt->fetch()) : $stmt->fetch();
        }
        return null;
    }

}