<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\DesignRepository;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $comments;
    protected $designs;

    public function __construct(DesignRepository $designs,CommentRepository $comments)
    {
        $this->comments = $comments;
        $this->designs = $designs;
    }

    public function store(Request $request,$id)
    {
        $this->validate($request,[
            'body' => ['required','min:20']
        ]);

        $comment = $this->designs->addComment($id,[
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return new CommentResource($comment);
    }

    public function update(Request $request,$id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('update',$comment);

        $this->validate($request,[
            'body' => ['required']
        ]);

        $comment = $this->comments->update($id,[
            'body' => $request->body
        ]);

        return new CommentResource($comment);
    }

    public function destroy($id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('delete',$comment);

        $this->comments->delete($id);
        return response()->json(['message' => 'نظر شما با موفقیت حذف شد'],200);
    }
}
