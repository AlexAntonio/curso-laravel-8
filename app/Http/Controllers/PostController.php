<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(){
        //$posts= Post::all(); ou $posts= Post::get(); lista todos registros

        //$posts= Post::orderBy('id', 'DESC')->paginate(); ordena DESC
        
        $posts= Post::latest()->paginate(); //ordena DESC também
        //ver similar ao var_dump: dd($posts);
        return view('admin.posts.index', ['posts' => $posts]);
    }

    public function create(){
        return view('admin.posts.create');
    }

    public function store(StoreUpdatePost $request){

        $data= $request->all();

        // 1 dos modos p/ pegar image
        //$request->file('image');

        if($request->image->isValid()){
            $nameFile= Str::of($request->title)->slug('-').'.'.$request->image->getClientOriginalExtension();
            $image= $request->image->storeAs('posts', $nameFile);
            $data['image']= $image;
        }

        Post::create($data);
        return redirect()->route('posts.index')->with('message', 'Post criado com sucesso!');
    }

    public function show($id){
        //Maneira 1
        //$post= Post::where('id', $id)->first();

        //Maneira 2
        $post= Post::find($id);

        if(!$post){
            return redirect()->route('posts.index');
        }else{
            return view('admin.posts.show', ['post' => $post]);
        }
    }

    public function edit($id){
        $post= Post::find($id);

        if(!$post){
            return redirect()->route('posts.index');
        }else{
            return view('admin.posts.edit', ['post' => $post]);
        }
    }

    public function update(StoreUpdatePost $request, $id){
        $post= Post::find($id);

        if(!$post){
            return redirect()->route('posts.index');
        }else{

            $data= $request->all();

            /*if($request->image->isValid()){
                if(Storage::exists($post->image)){
                    Storage::delete($post->image);
                }

                $nameFile= Str::of($request->title)->slug('-').'.'.$request->image->getClientOriginalExtension();
                $image= $request->image->storeAs('posts', $nameFile);
                $data['image']= $image;
            }*/

            if(isset($request->image)){
                
                $namefile = Str::of($request->title)->slug('-') . '.' . $request->image->extension();
                
                if ($request->image->isValid()) {

                    if (Storage::exists($post->image)){
                        Storage::delete($post->image);
                    }
                        
                    $image = $request->image->storeAs('posts', $namefile);
                    $data['image']= $image;
                }
            } else {

                if (Storage::exists($post->image)) {
                    
                    $namefile = "posts/" . Str::of($request->title)->slug('-') . '.' . explode('.',$post->image)[1];
                    
                    if($namefile != $post->image) {
                        Storage::copy($post->image, $namefile);
                        Storage::delete($post->image);
                    }
                    $image = $namefile;
                    $data['image']= $image;
                }
            }

            $post->update($data);
            return redirect()->route('posts.index')->with('message', 'Post atualizado com sucesso!');
        }
    }

    public function destroy($id){
        $post= Post::find($id);

        if(!$post){
            return redirect()->route('posts.index');
        }else{
            if(Storage::exists($post->image)){
                Storage::delete($post->image);
            }
            
            $post->delete();
            return redirect()->route('posts.index')->with('message', 'Post removido com sucesso!');
        }
    }

    public function search(Request $request){

        $filters= $request->except('_token');

        $posts= Post::where('title', '=', $request->search)
                        ->orWhere('content', 'LIKE', "%{$request->search}%")
                        ->paginate(); //debug

                        //->toSql(); //debug
                        //dd($posts);//debug
        return view('admin.posts.index', ['posts' => $posts, 'filters' => $filters]);
    }
}
