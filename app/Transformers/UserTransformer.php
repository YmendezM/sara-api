<?php
namespace App\Transformers;
use App\Models\Tatuco\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
	public function transform(User $user)
	{
		return [
			'cedula' => $user->id,
			'nombre' => $user->name,
			'correo' => $user->email
		];
	}
}