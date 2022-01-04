<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;



class BlogController extends BaseController
{
    function addData(Request $req)
    {

        $blog  = new Blog;
        $blog->title = $req->title;
        $blog->description = $req->description;
        $blog->likes = $req->likes;
        $blog->image = $req->image;
        dd($req);

        if ($image = $req->file('image')) {
            $destinationPath = 'image/';
            $blogImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $blogImage);
            $input['image'] = "$blogImage";
        }

        $result = $blog->save();

        if ($result) {
            return $this->sendResponse($blog, "New Blog created successfully.");
        } else {
            return $this->sendError("Error while saving the data");
        }
    }


    function getData($id = null)
    {

        $data = $id ? Blog::find($id) : Blog::all();

        if ($data) {
            // return $data;
            return $this->sendResponse($data, "data");
        }
        return $this->sendError("No data found for id = $id");
    }


    function update($id, Request $req)
    {
        $blog = Blog::find($id);

        if ($blog) {
            $blog->title = $req->title;
            $blog->description = $req->description;
            $blog->likes = $req->likes;

            $result = $blog->save();

            if ($result) {

                return $this->sendResponse($blog, "Data updated for id = $id");
            }

            return $this->sendError("Update failed for id = $id");
        } else {
            return $this->sendError("Data not found for id = $id");
        }
    }


    function delete($id)
    {

        $blog = Blog::find($id);

        if ($blog) {
            $blog->delete();
            return $this->sendResponse($blog, "Blog deleted Successfully");
        } else {
            return $this->sendError("Data cannot be deleted for id = $id");
        }
    }


    function publishBlog($id)
    {
        $blog = Blog::find($id);
        if ($blog) {
            $blog->published = !$blog->published;

            $blog->save();
            if ($blog->published === false) {

                return $this->sendResponse($blog, "Blog unpublished");
            } else {
                return $this->sendResponse($blog, "Blog published Successfully");
            }
        } else {

            return $this->sendError("Data cannot be found for id = $id");
        }
    }

    function getPublishedBlog()
    {
        $data = Blog::where('published', '1')->get();

        return $this->sendResponse($data, "data");
    }

    public function like(Request $req){
       
        $userCheck = Like::where('blog_id', $req->blog_id)->where('user_id', $req->user_id)->first();
        
        
        if($userCheck){
            $userCheck->like = !$userCheck->like;

            $userCheck->save();
            return $this->sendResponse($userCheck,"Like updated");

        }
        $like = new Like();
        $like->blog_id = $req->blog_id;
        $like->user_id = $req->user_id;
        $like->like = 1;

        $like->save();

        return $this->sendResponse($like, "Liked");
    }
}
