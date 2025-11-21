<?php 
namespace App;

use Illuminate\Support\Arr;

trait ValidateRole
{
    private $abilities = [
        'create' => 'create',
        'store' => 'create',
        'index' => 'read',
        'show' => 'read',
        'edit' => 'update',
        'update' => 'update',
        'destroy' => 'delete'
    ];

    private $exception = [
        'email/verify'
    ];

    /**
     * Override of callAction to perform the authorization before
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function callAction($method, $parameters)
    {
        if( $ability = $this->getAbility($method) ) {
            if(!in_array(request()->segment(1).'/'.request()->segment(2),$this->exception) && request()->segment(1) != 'api'){
                $this->authorize($ability);
            }
        }

        return parent::callAction($method, $parameters);
    }

    public function getAbility($method)
    {
        // $routeName = explode('.', \Request::route()->getName());
        $action = Arr::get($this->getAbilities(), $method);
        $url = explode('/',request()->path());

        $countUrl = count($url);
        if($countUrl > 2){
            $url = request()->segment(1).'/'.request()->segment(2);
        }else{
            $url = request()->path();
        }
        return $action ? $action . ' ' . $url : null;
    }

    private function getAbilities()
    {
        return $this->abilities;
    }

    public function setAbilities($abilities)
    {
        $this->abilities = $abilities;
    }
}