<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Mail\SendInvitationToJoinTeam;
use App\Repositories\Eloquent\InvitationRepository;
use App\Repositories\Eloquent\TeamRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    protected $invitation;
    protected $team;
    protected $user;

    public function __construct(InvitationRepository $invitation,TeamRepository $team,UserRepository $user)
    {
        $this->invitation = $invitation;
        $this->team = $team;
        $this->user = $user;
    }

    public function invite(Request $request,$teamId)
    {
        $team = $this->team->find($teamId);

        //return response()->json(['message' => $this->user->getUserByEmail($request->email)]);

        $validated = $this->validate($request,[
            'email' => ['required']
        ]);
        $user = auth()->user();
        if (!$user->isOwnerOfTeam($team))
        {
            return response()->json(['email' => 'شما ایجاد کننده گروه نیستید و اجازه دعوت به گروه را ندارید'],401);
        }

        if ($team->hasPendingInvite($request->email))
        {
            return response()->json(['email' => 'برای این کاربر ایمیل دعوت ارسال شده است'],422);
        }

        $recipient = $this->user->getUserByEmail($request->email);

        if (!$recipient)
        {
            $this->createInvitation(false,$team,$request->email);
            return response()->json(['message' => 'ایمیل به کاربر مورد نظر ارسال شد'],200);
        }
        if ($team->hasUser($recipient))
        {
            return response()->json(['email' => 'به نظر میرسد کاربر مورد نظر عضو تیم است.'],422);
        }
        $this->createInvitation(true,$team,$request->email);
        return response()->json(['message' => 'ایمیل به کاربر مورد نظر ارسال شد'],200);
    }

    public function resend($id)
    {
        $invitation = $this->invitation->find($id);
        if (!auth()->user()->isOwnerOfTeam($invitation->team))
        {
            return response()->json(['email' => 'شما ایجاد کننده گروه نیستید و اجازه دعوت به گروه را ندارید'],401);
        }
        $recipient = $this->user->getUserByEmail($invitation->recipient_email);

        Mail::to($invitation->recipient_email)->send(new SendInvitationToJoinTeam($invitation,!is_null($recipient)));
        return response()->json(['message' => 'ایمیل دعوت مجدد ارسال شد'],200);
    }

    public function respond(Request $request,$id)
    {
        $validated = $this->validate($request,[
            'token' => ['required'],
            'decision' => ['required']
        ]);

        $token = $request->token;
        $decision = $request->decision;
        $invitation = $this->invitation->find($id);

        if ($invitation->recipient_email !== auth()->user()->email)
        {
            return response()->json(['message' => 'این دعوتنامه برای شما نیست'],401);
        }

        if ($invitation->token !== $token)
        {
            return response()->json(['message' => 'توکن نامعتبر.'],401);
        }

        if ($decision !== 'deny')
        {
            $this->invitation->addUserToTeam($invitation->team,auth()->id());
        }

        $invitation->delete();
        return response()->json(['message' => 'موفقیت آمیز'],200);
    }

    public function destroy($id)
    {
        $invitation = $this->invitation->find($id);
        $this->authorize('delete',$invitation);
        $invitation->delete();
        return response()->json(['message' => 'موفقیت آمیز'],200);
    }

    protected function createInvitation(bool $user_exist,Team $team,string $email)
    {
        $invitation = $this->invitation->create([
            'recipient_email' => $email,
            'sender_id' => auth()->id(),
            'team_id' => $team->id,
            'token' => md5(uniqid(microtime()))
        ]);

        Mail::to($email)->send(new SendInvitationToJoinTeam($invitation,$user_exist));
    }
}
