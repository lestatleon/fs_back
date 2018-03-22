<?php
namespace App\Http\Controllers\Api;

use App\Invitation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function getList(Request $request) 
    {
        $invitations = Invitation::
            join('status', 'status.id', '=', 'invitations.status_id')
            ->get();
        return response()->json($invitations);
    }

    public function create(Request $request) 
    {
        $email = $request->get("email");
        $message = $request->get("message");

        $exist = Invitation::where('email', $email)->count();
        if ( 0 === $exist ) {
            $invitation = new Invitation();
            $invitation->email = $email;
            $invitation->message = $message;
            $key = hash('md5', $email.date('Y-m-d H:m:s'));
            $invitation->key = $key;
            $invitation->save();

            $urlInvitation = env("FRONT_URL") . '/guest/' .  $key;
            $invitation->url = $urlInvitation;
        } else {
            $invitation = [ 'error' => 'invitation already sent' ];
        }
        
        return response()->json($invitation)->setStatusCode(201);
    }

    public function update(Request $request, $id) 
    {
        $invitation = Invitation::where('key', $id)->first();

        $status = $request->get('status', $invitation->status_id);

        if ( $invitation->status_id == 1 ) {
          $date = date('Y-m-d H:m:s');
        } else {
          $date = $invitation->viewed_on;
        }

        Invitation::where('key', $id)
            ->update(['status_id' => $status, 'viewed_on' => $date]);

        return response()->json($invitation)->setStatusCode(201);
    }

    public function get(Request $request, $id) 
    {
        $invitation = Invitation::select('email', 'message', 'status_id', 'key')
        ->where('key', $id)->first();

        return response()->json($invitation)->setStatusCode(200);
    }

}
