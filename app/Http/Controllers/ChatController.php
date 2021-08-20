<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Message;
use App\Models\PrivateMessage;

class ChatController extends Controller
{
    public function index() {
        //Fetching Private Messages
        $username = auth()->user()->username;
        $msgs = [];
        foreach(PrivateMessage::all() as $msg) {
            if( $msg->sender == $username || $msg->receiver == $username ) {
                array_push($msgs, $msg);
            }
        }

        return Inertia::render('Chat', [
            'users' => User::all('id','name','username', 'status'),
            'currentUser' => auth()->user(),
            'messages' => Message::all(),
            'privateMessages' => $msgs
        ]);
    }


    public function createMessage(Request $req) {
        $req->validate([
            'message' => 'required|max:1000',
        ]);

        return Message::create([
            'user_id' => auth()->user()->id,
            'author' => auth()->user()->username,
            'message' => $req->message
        ]);
    }

    public function updateUserStatus(Request $req) {
        $req->validate([
            'status' => ['required', 'max:20'],
            'user_id'
        ]);

        if($req->status == 'online') {
            $user = User::find(auth()->user()->id);
            $user->status = 'online';
            return $user->save();
        }
        else if($req->status == 'offline') {
            $user = User::find($req->user_id);
            $user->status = 'offline';
            $user->save();            
        }
    }


    //Per aggiornare la lista utenti quando avviene una registrazione
    public function getUserChatList() {
        return User::all('id','name','username', 'status');
    }

    
    public function postPrivateMessages(Request $req) {
        $req->validate([
            'message' => ['required', 'max:1000'],
            'receiver' => ['required', 'max:30']
        ]);

        return PrivateMessage::create([
            'sender' => auth()->user()->username,
            'user_id' => auth()->user()->id,
            'message' => $req->message,
            'receiver' => $req->receiver
        ]);

    }

}
