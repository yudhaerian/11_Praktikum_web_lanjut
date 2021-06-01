<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Auth;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();
        $todos = Todo::with('user')
                ->where('user_id',$user->id)
                ->get();
        $cek = $user;

        // return $this->apiSuccess($todos);
        return response()->json([
            "success" => true,
            "data" => $cek
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // $request->validate();

        $user=auth()->user();
        $todo = new Todo($request->all());
        // $todo = Todo::create($request->all());
        $todo->user_id = $user->id;
        $todo->save();
        // return Todo::create($request->all());

        return response()->json($todo);

        // return $this->apiSuccess($todo->load('user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //
        return $this->apiSuccess($todo->load('user'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        //
        // $request->validate();
        $todo->todo = $request->todo;
        $todo->label = $request->label;
        $todo->done = $request->done;
        $todo->save();

        return $this->apiSuccess($todo->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        //
        if (auth()->user()->id==$todo->user_id){
            $todo->delete();
            return $this->apiSuccess($todo);
        }

        return $this->apiError(
            'Unauthorized',
            Response::HTTP_UNAUTHORIZED
        );
    }
}
