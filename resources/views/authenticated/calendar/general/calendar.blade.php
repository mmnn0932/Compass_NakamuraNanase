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
      <p class="mb-1">予約日：<span id="modalDate"></span></p>
      <p class="mb-3">時間：<span id="modalPart"></span></p>
      <p class="mb-3 modal-text">上記の予約をキャンセルしてもよろしいですか？</p>


  <div class="modal-actions text-right">
  <button type="button" class="btn btn-primary js-modal-close">閉じる</button>
  <button type="button" class="btn btn-danger js-cancel-submit">キャンセルする</button>
      </div>
    </div>
  </div>
</div>

  <script>
  $(function(){
    $(document).on('click', '.js-open-cancel', function(){
      const date = $(this).data('date');
      const part = String($(this).data('part') || '');
      const partLabel = part === '1' ? 'リモ1部' : part === '2' ? 'リモ2部' : part === '3' ? 'リモ3部' : 'リモ';

      $('#modalDate').text(date);
      $('#modalPart').text(partLabel);
      $('#delete_date').val(date);
      $('#delete_part').val(part);

      $('#cancelModal').fadeIn(150);
    });
    $(document).on('click', '.js-modal-close', function(){
      $('#cancelModal').fadeOut(120);
    });
    $(document).on('click', '.js-cancel-submit', function(){
      $('#cancelModal').fadeOut(120, function(){
        $('#deleteParts').trigger('submit');
      });
    });
  });

  </script>
  <script>
  document.addEventListener('DOMContentLoaded', function(){
    const modal = document.getElementById('cancelModal');
    document.querySelectorAll('.js-modal-close').forEach(function(btn){
      btn.setAttribute('type', 'button');
      btn.addEventListener('click', function(e){
        e.preventDefault();
        if (!modal) return;
        modal.style.display = 'none';
        modal.classList.remove('show');
      });
    });
  });
  </script>
