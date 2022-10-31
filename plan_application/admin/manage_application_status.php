<?php
?>
<div class="container-fluid">
    <form action="" id="update-status-form">
        <input type="hidden" name="application_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
        <div class="col-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="status" class="control-label">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm rounded-0">
                            <option value="0" <?php echo (isset($_GET['status']) && $_GET['status'] == 0 ) ? 'selected' : '' ?>>Pending</option>
                            <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] == 1 ) ? 'selected' : '' ?>>Confirmed</option>
                            <option value="2" <?php echo (isset($_GET['status']) && $_GET['status'] == 2 ) ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#update-status-form').submit(function(e){
            e.stopPropagation()
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal_secondary button').attr('disabled',true)
            $('#uni_modal_secondary button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'../Actions.php?a=update_application_status',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal_secondary button').attr('disabled',false)
                     $('#uni_modal_secondary button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                            $('#uni_modal_secondary').on('hide.bs.modal',function(){
                                $('#uni_modal_secondary .modal-body').html('')
                                uni_modal('Application Details',"view_application.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>",'mid-large')
                                $('#uni_modal').on('hide.bs.modal',function(){
                                    location.reload();
                                })
                            })
                            $('#uni_modal_secondary').modal('hide')
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal_secondary button').attr('disabled',false)
                     $('#uni_modal_secondary button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>