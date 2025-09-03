<x-sidebar>
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">

      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
        {!! $calendar->render() !!}
      </div>
    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
    </div>
  </div>
</div>
</x-sidebar>

<div id="cancelModal" class="modal-mask" style="display:none;">
  <div class="modal-wrapper">
    <div class="modal-card">
      <p class="mb-1"><strong>日付：</strong><span id="modalDate"></span></p>
      <p class="mb-3"><strong>部：</strong><span id="modalPart"></span></p>
      <h5 class="mb-3">上記の予約をキャンセルしてもよろしいですか？</h5>

      <div class="text-right">
        <button type="button" class="btn btn-secondary js-modal-close">閉じる</button>
        <button type="button" class="btn btn-danger js-cancel-submit">キャンセルする</button>
      </div>
    </div>
  </div>
</div>

<style>
  .modal-mask{
    position: fixed; inset: 0;
    background: rgba(0,0,0,.4);
    display: none;
    z-index: 1050;
  }
  .modal-wrapper{
    min-height: 100vh; display: flex; align-items: center; justify-content: center;
  }
  .modal-card{
    background: #fff; width: 90%; max-width: 420px;
    padding: 20px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,.15);
  }
</style>

<script>
$(document).on('click', '.js-open-cancel', function(){
  const date = $(this).data('date');
  const part = $(this).data('part');
  const partLabel = part == 1 ? 'リモ1部' : (part == 2 ? 'リモ2部' : 'リモ3部');

  // モーダル表示用テキスト
  $('#modalDate').text(date);
  $('#modalPart').text(partLabel);

  // フォームに値をセット
  $('#delete_date').val(date);
  $('#delete_part').val(part);

  // モーダルを開く
  $('#cancelModal').fadeIn(150);
});

$(document).on('click', '.js-modal-close', function(){
  $('#cancelModal').fadeOut(120);
});

// 背景クリックで閉じる（カード内クリックは閉じない）
$(document).on('click', '#cancelModal', function(e){
  if(e.target.id === 'cancelModal'){ $('#cancelModal').fadeOut(120); }
});

// 「キャンセルする」クリック → フォーム送信
$(document).on('click', '.js-cancel-submit', function(){
  $('#cancelModal').fadeOut(120, function(){
    $('#deleteParts').trigger('submit');
  });
});
</script>

<style>
  .past-day {
    background: #eeeeee;
  }
</style>
