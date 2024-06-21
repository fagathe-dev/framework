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

}