<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();        //return collection
        return view('dashboard.categories.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category();
        return view('dashboard.categories.create' , compact('category','parents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clean_data = $request->validate(Category::rules());
        //request merge (i can add value to the request)
        $request->merge([
            'slug' => Str::slug($request->post('name'))
        ]);

        $data = $request->except('image');
        $data['image'] = $this->uploadimage($request);

        //mass assignment
        $category = Category::create( $data);

        //prg (post redirect get)
        return Redirect()->route('dashboard.categories.index')
            ->with('success', 'Category created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $category = Category::findOrfail($id);
        } catch (Exception $e) {
            return redirect()->route('dashboard.categories.index')
                ->with('info', 'Record not found!');
        };

        // SELECT * FROM categories WHERE id <> $id
        // AND (parent_id IS NULL OR parent_id <> $id)
        $parents = Category::where('id', '<>', $id)
            ->where(function($query) use ($id) {
                $query->whereNull('parent_id')
                    ->orWhere('parent_id', '<>', $id);
            })
            ->get();

        return view('dashboard.categories.edit' , compact('category' , 'parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        //$request->validate(Category::rules($id));
        $category = Category::findOrfail($id);

        $old_image = $category->image;

        $data = $request->except('image');
        $new_image = $this->uploadimage($request);
        if($new_image) {
            $data['image'] = $new_image;
        };

        $category->update($data);


        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        };

        //prg (post redirect get)
        return Redirect()->route('dashboard.categories.index')
            ->with('success', 'Category Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        if($category->image){
            Storage::disk('public')->delete($category->image);
        }
       //Category::where('id', '=', $id)->delete();
//        Category::destroy($id);

        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category deleted!');
    }

    protected function uploadimage(Request $request)
    {
        if(!$request->hasFile('image')){
            return ;
        }
            $file =$request->file('image');
            $path = $file->store('uploads',[
                'disk' => 'public'
            ]);
            return $path;
    }
}
