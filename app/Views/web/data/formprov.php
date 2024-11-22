<div class="modal-header">
     <h5 class="modal-title"><?= $title ?></h5>
     <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span class="font-weight-light" aria-hidden="true">&times;</span></button>
 </div>
 <div class="modal-body text-left">
     <form id="add_submit">
         <input type="hidden" name="action" value="<?= $action ?>" />
         <input type="hidden" name="id" value="<?php if (isset($detail['id'])) echo $detail['id']; ?>" />
         Suara sah:<br />
         <input type="text" name="suara_sah" value="<?php if (isset($detail['suara_sah'])) echo $detail['suara_sah']; ?>" class="form form-control form-50" size="40" />
         <br />
         <span id="report"></span>

         <input type="submit" name="submit" value="<?= $tombol ?>" class="btn btn-primary mt-3" />
     </form>
 </div>
 <script>
     utils.$document.ready(function() {
         $('.custom-file-input').on('change', function(e) {
             var $this = $(e.currentTarget);
             var fileName = $this.val().split('\\').pop();
             $this.next('.custom-file-label').addClass('selected').html(fileName);
         });
     });
     $(document).ready(function() {
         $('#add_submit').submit(function(e) {
             e.preventDefault();

             var form = $(this)[0]; 
             var formData = new FormData(form); 

             $.ajax({
                 type: 'POST',
                 url: "<?= site_url('suara24/dataProv/submitdata') ?>",
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 data: formData, 
                 processData: false,
                 contentType: false,
                 success: function(response) {
                     $('#add_submit input[type="text"]').val('');
                     $('#add_submit textarea').val('');
                     $('#add').modal('hide');
                     dataindex();
                     showToast("success", response.message);
                 },
                 error: function(xhr, status, error) {
                     var response = xhr.responseJSON;
                     showToastError('Error', response);
                 }
             });
         });
     });
     $('#add').on('hidden.bs.modal', function() {
         dataindex();
         $('#report_edit').html('');
     });
 </script>