<?php

namespace App\Http\Controllers\Todo;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Todo;
use Illuminate\Http\Request;

class ApiTodoController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $todos = Todo::where('user_id','=',$user_id)->get();

        return response()->json([
          'status'  => true,
          'message' => 'List of my Todos.',
          'todos'      => $todos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
          'title' => 'required|string|max:255',
          'description' => 'required|string|max:255',
        ]);

        $todo = new Todo();
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->user_id = Auth::user()->id;
        $todo->save();

        return response()->json([
          'status'  => true,
          'message' => 'New Todo Added Successfully.',
          'id'      => $todo->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = Todo::where('id','=',$id)->first();

        return response()->json([
          'status'  => true,
          'message' => 'Selected Todo details.',
          'todo'      => $todo,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
          'title' => 'required|string|max:255',
          'description' => 'required|string|max:255',
        ]);

        Todo::where('id','=',$id)->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
          'status'  => true,
          'message' => 'Todo detail updated.',
          'todo'      => Todo::find($id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Todo::where('id','=',$id)->delete();

        return response()->json([
          'status'  => $status,
          'message' => 'Todo deleted.',
        ]);
    }
}
