<?php  

namespace App\Services;

use App\Exceptions\GeneralExceptions;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService
{	
	private User $userModel;

    public function __construct()
    {
        $this->userModel = new User;
    }

	public function tryLoginOrFail($dataUser)
	{
		if(!Auth::attempt($dataUser))
			throw new GeneralExceptions('Datos incorrectos',401);   
	}

	public function generateToken($dataUser)
	{
		$user = $this->userModel->findForUsername($dataUser['username']);

		$permissions = $this->userModel->getPermissions($user);

		$token = $user->createToken("api_token",$permissions)->plainTextToken;

		return $token;

	}

	public function tryChangePassword($data)
	{	
		$user = Auth::user();

		if (!Hash::check($data['oldPassword'], $user->password)) 
			throw new GeneralExceptions('Contraseña actual incorrecta',422);   

		if(!$data['newPassword'] == $data['confirmPassword'])
			throw new GeneralExceptions('La nueva contraseña no coincide con la confirmación',422);   

		$user->password = Hash::make($data['newPassword']);
    	$user->save();

	}


}
