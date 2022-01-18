<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class BlogController extends BaseController
{
    function addData(Request $req)
    {
        $randomString = Str::random(10);

        $newImageName = time() . '-' . $randomString . '.' . $req->image->extension();

        $req->image->move(public_path('images'), $newImageName);


        $blog  = new Blog;
        $blog->title = $req->title;
        $blog->description = $req->description;

        $blog->image = $newImageName;


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
            foreach ($data as $blog) {
                $totalLikes = Like::where('blog_id', $blog->id)->where('like', '1')->get();
                $count = count($totalLikes);
                $blog['totalLikes'] = $count;
            }
            return $this->sendResponse($data, "data");
        }
        return $this->sendError("No data found for id = $id");
    }


    function update(Request $req, $id)
    {
        $blog = Blog::find($id);

        if ($blog) {
            $blog->title = $req->title;

            $blog->description = $req->description;


            if ($req->hasFile('image')) {
                $imageCheck = 'images/' . $blog->image;


                if (File::exists($imageCheck)) {
                    File::delete($imageCheck);
                }

                $randomString = Str::random(10);
                $newImageName = time() . '-' . $randomString . '.' . $req->image->extension();

                $req->image->move(public_path('images'), $newImageName);

                $blog->image = $newImageName;
            }

            $result = $blog->update();

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

        foreach ($data as $blog) {
            $totalLikes = Like::where('blog_id', $blog->id)->where('like', '1')->get();
            $count = count($totalLikes);
            $blog['totalLikes'] = $count;
        }

        // $data = $blog;

        return $this->sendResponse($data, "data");
    }

    public function like(Request $req)
    {
        $logged_in_user_id = Auth::id();


        $userCheck = Like::where('blog_id', $req->blog_id)->where('user_id', $logged_in_user_id)->first();


        if ($userCheck) {
            $userCheck->like = !$userCheck->like;

            $userCheck->save();
            return $this->sendResponse($userCheck, "Like updated");
        }
        $like = new Like();
        $like->blog_id = $req->blog_id;
        $like->user_id =  $logged_in_user_id;
        $like->like = true;

        $like->save();

        return $this->sendResponse($like, "Liked");
    }
}
