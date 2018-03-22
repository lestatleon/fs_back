<?php
namespace App\Http\Controllers\Api;

use App\Invitation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getList(Request $request) 
    {
        $invitations = Invitation::get();
        return response()->json($invitations);
    }

    public function get(Request $request, $id) 
    {
        $invitation = Invitation::where('id', $id)->first();
        return response()->json($invitation);
    }

    public function create(Request $request) 
    {
        $name = $request->get('name');
        $parent_id = $request->get('parent_id', null);
        $category = new Category();
        $category->name = $name;
        $category->parent_id = $parent_id;
        $category->save();

        return response()->json($category)->setStatusCode(201);
    }

    public function update(Request $request, $id) 
    {
        $category = Category::where('id', $id)->first();
        $name = $request->get('name', $category->name);
        $parent_id = $request->get('parent_id', $category->parent_id);
        $category->name = $name;
        $category->parent_id = $parent_id;
        $category->save();

        return response()->json($category)->setStatusCode(201);
    }

}
