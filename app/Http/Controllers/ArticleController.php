<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    // VULNERABILITY: SQL Injection via raw query with string concatenation
    public function search(Request $request)
    {
        $keyword = $request->input('q');
        // Dangerous: user input directly injected into SQL
        $articles = DB::select("SELECT * FROM posts WHERE title LIKE '%" . $keyword . "%' OR content LIKE '%" . $keyword . "%'");
        return response()->json($articles);
    }

    // VULNERABILITY: SQL Injection in filter
    public function filterByCategory(Request $request)
    {
        $category = $request->input('category');
        $sort     = $request->input('sort', 'created_at');
        // Dangerous: both category and sort are user-controlled
        $query = "SELECT * FROM posts WHERE category = '$category' ORDER BY $sort DESC";
        $posts = DB::select($query);
        return response()->json($posts);
    }

    // VULNERABILITY: IDOR — no ownership check
    public function deletePost(Request $request, $id)
    {
        // Any authenticated user can delete any post by guessing the ID
        DB::delete("DELETE FROM posts WHERE id = $id");
        return response()->json(['message' => 'Post deleted']);
    }

    // VULNERABILITY: Mass Assignment via create()
    public function store(Request $request)
    {
        $post = Post::create($request->all()); // passes all input including 'is_admin', 'user_id'
        return response()->json($post);
    }
}
