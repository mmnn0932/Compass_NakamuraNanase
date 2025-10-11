<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;
use App\Http\Requests\Admin\MainCategoryRequest;
use App\Http\Requests\Admin\SubCategoryRequest;
use App\Http\Requests\CommentFormRequest;
use App\Http\Requests\BulletinBoard\PostEditRequest;

class PostsController extends Controller
{
    public function show(Request $request)
{
    // サイドバー用（メインカテゴリごとにサブカテゴリを持たせる）
    $categories = MainCategory::with('subCategories')->get();

    // 基本クエリ（一覧で使う共通の見た目：ユーザーと件数を抱えた状態）
    $base = Post::with('user')
        ->withCount(['postComments as comments_count', 'likes as likes_count']);

    // ①サブカテゴリID指定（サイドバークリック時）
    if ($request->filled('sub_category_id')) {
        $subId = (int) $request->input('sub_category_id');
        $posts = (clone $base)
            ->whereHas('subCategories', fn($q) => $q->where('sub_categories.id', $subId))
            ->get();

    // ②キーワード：サブカテゴリ名と完全一致 → そのサブカテゴリの投稿だけ
    } elseif ($request->filled('keyword')) {
        $kw = trim($request->input('keyword'));

        $exactSub = \App\Models\Categories\SubCategory::where('sub_category', $kw)->first();
        if ($exactSub) {
            $posts = (clone $base)
                ->whereHas('subCategories', fn($q) => $q->where('sub_categories.id', $exactSub->id))
                ->get();
        } else {
            // 一致しなければ通常のタイトル/本文検索
            $posts = (clone $base)
                ->where(function ($q) use ($kw) {
                    $q->where('post_title', 'like', "%{$kw}%")
                      ->orWhere('post', 'like', "%{$kw}%");
                })
                ->get();
        }

    // ③「いいねした投稿」「自分の投稿」など他のボタン
    } elseif ($request->has('like_posts')) {
        $likes = auth()->user()->likePostId()->pluck('like_post_id');
        $posts = (clone $base)->whereIn('id', $likes)->get();

    } elseif ($request->has('my_posts')) {
        $posts = (clone $base)->where('user_id', auth()->id())->get();

    } else {
        // デフォルト：全件
        $posts = (clone $base)->get();
    }

    return view('authenticated.bulletinboard.posts', compact('posts', 'categories'));
}

    public function postInput(){
        $main_categories = MainCategory::with('subCategories')->get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        $data = $request->validated();
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $data['post_title'],
            'post'       => $data['post_body'],
        ]);
        $post->subCategories()->attach($data['post_category_id']);
        return redirect()->route('post.show');
    }

    public function postEdit(PostEditRequest $request){
        $data = $request->validated();
        Post::where('id', $data['post_id'])->update([
        'post_title' => $data['post_title'],
        'post'       => $data['post_body'],
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(MainCategoryRequest $request){
    MainCategory::create([
        'main_category' => $request->validated()['main_category_name'],
    ]);
    return redirect()->route('post.input');
    }

    public function subCategoryCreate(SubCategoryRequest $request)
    {
        $data = $request->validated();
        SubCategory::create([
            'main_category_id' => $data['main_category_id'],
            'sub_category'     => $data['sub_category_name'],
        ]);
        return redirect()->route('post.input');
    }

    public function commentCreate(CommentFormRequest $request){
        $data = $request->validated();
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $data['comment']
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }

    public function postDetail($id)
{
    $post = Post::with(['user', 'postComments'])->findOrFail($id);
    return view('authenticated.bulletinboard.post_detail', compact('post'));
}

}
