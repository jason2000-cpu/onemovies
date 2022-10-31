<div class="container-fluid">
    <form action="" id="fee-form">
        <div class="form-group">
            <label for="about" class="control-label">About Us Content</label>
            <textarea name="about" id="about" cols="30" rows="4" required class="form-control rounded-0 summernote" data-height="50vh"><?php echo is_file('./../about.html') ? file_get_contents('./../about.html') : '' ?></textarea>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#fee-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'../Actions.php?a=save_about',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     
                    $('html, body,.modal').animate({scrollTop:0},'fast')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        $('#uni_modal').on('hide.bs.modal',function(){
                            location.reload()
                        })
                        if("<?php echo isset($fee_id) ?>" != 1)
                        _this.get(0).reset();
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     
                    $('html, body,.modal').animate({scrollTop:0},'fast')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>