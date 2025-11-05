<x-sidebar>
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p class="post-user"><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p class="post-title"><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="meta-row">
        <div class="post-tags">
          @if($post->subCategories->isNotEmpty())
      @foreach($post->subCategories as $sub)
        <span class="tag-chip">{{ $sub->sub_category }}</span>
      @endforeach
    @endif
    </div>
        <div class="post_status">
          <span class="status-item">
            <i class="fa fa-comment" aria-hidden="true"></i>
        <span>{{ $post->comments_count }}</span>
      </span>
          <span class="status-item">
            @if(Auth::user()->is_Like($post->id))
        <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
        @else
          <i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i>
        @endif
        <span class="like_counts{{ $post->id }}">{{ $post->likes_count }}</span>
      </span>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="other_area w-25">
    <div class="side-panel m-4">
      <div class="mb-3"><a href="{{ route('post.input') }}" class="btn btn-post w-100">投稿</a></div>

      <form id="postSearchRequest" action="{{ route('post.show') }}" method="get" class="search-group mb-3">
      <input type="text" class="search-input" name="keyword" placeholder="キーワードを検索" value="{{ request('keyword') }}">
      <button type="submit" class="btn btn-search">検索</button>
    </form>
      <div class="mb-4 btn-row">
      <button type="submit" name="like_posts" class="btn btn-like" form="postSearchRequest">いいねした投稿</button>
      <button type="submit" name="my_posts"   class="btn btn-mine" form="postSearchRequest">自分の投稿</button>
      </div>
      <p class="side-title">カテゴリー検索</p>
    <ul class="cat-list">
      @foreach($categories as $main)
      <li class="cat-item">
      <button type="button" class="cat-toggle" data-target="sub-{{ $main->id }}">
        <span>{{ $main->main_category }}</span>
        <i class="fa-solid fa-chevron-down arrow" aria-hidden="true"></i>
      </button>
        @if($main->subCategories->isNotEmpty())
        <ul id="sub-{{ $main->id }}" class="subcat-list" style="display:none;">
      @foreach($main->subCategories as $sub)
      <li class="subcat-item">
        <button
          type="submit"
          name="sub_category_id"
          value="{{ $sub->id }}"
          class="subcat-btn" form="postSearchRequest">
          {{ $sub->sub_category }}
        </button>
      </li>
      @endforeach
      </ul>
      @endif
      </li>
        @endforeach
      </ul>
    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
</x-sidebar>
<script>
$(function () {
  $(document).on('click', '.cat-toggle', function () {
    const $btn = $(this);
    const target = '#' + $btn.data('target');
    $(target).stop(true, true).slideToggle(160);
    $btn.toggleClass('is-open');
  });
});
</script>
