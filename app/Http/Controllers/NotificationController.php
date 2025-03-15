<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ManualNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('destinataire')->latest()->get();
        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::all();
        return view('notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'destinataire_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $notification = Notification::create($request->all());

        // Envoyer l’e-mail
        Mail::to($notification->destinataire->email)->send(new ManualNotification($notification));

        return redirect()->route('notifications.index')->with('success', 'Notification envoyée avec succès.');
    }
}
