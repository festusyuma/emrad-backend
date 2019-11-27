<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Services\RolesServices;
use Emrad\Services\UsersServices;
use Emrad\Facade\UsersServicesFacade;
use Emrad\Http\Resources\UsersResource;
use Emrad\Services\PermissionsServices;

class RetailerOrderController extends Controller
{
    /**
     * @var UsersServices $usersServices
     */
    public $usersServices;

    /**
     * @var RolesServices $rolesServices
     */
    public $rolesServices;

    /**
     * @var PermissionsServices $permissionsServices
     */
    public $permissionsServices;

    /**
     * @var OrderServices $orderServices
     */
    public $orderServices;
    
    /**
     * @var RetailerOrder $retailerOrder
     */
    private $retailerOrder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UsersServices $usersServices,
        RolesServices $rolesServices,
        PermissionsServices $permissionsServices
    ) {
        $this->usersServices = $usersServices;
        $this->permissionsServices = $permissionsServices;
        $this->rolesServices = $rolesServices;
    }

    public function setOrder($order_id)
    {
        $this->order = Order::find($order_id);
    }

    public function setUser($user_id)
    {
        $this->user = User::find($user_id);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function order(Request $request)
    {
        $validator = Validator::make(request()->all(), Rules::get('POST_ORDER'));

        if ($validator->fails()) {
            return $this->validationErrors($validator->getMessageBag()->all());
        }
        try {
            $order = $this->orderRepository->order($request);
            return $this->withData($order);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
