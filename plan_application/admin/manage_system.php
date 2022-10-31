<style>
    #logo-display{
        width:75px;
        height:75px;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<?php
require_once("../DBConnection.php");
$qry = $conn->query("SELECT * FROM `system_info` ");
while($row = $qry->fetchArray()){
    $meta[$row['meta_field']] = $row['meta_value'];
}
?>
<div class="container-fluid">
    <form action="" id="system-form">
        <div class="form-group">
            <label for="company_name" class="control-label">Company Name</label>
            <input type="text" name="company_name" autofocus id="company_name" required class="form-control form-control-sm rounded-0" value="<?php echo isset($meta['company_name']) ? $meta['company_name'] : '' ?>">
        </div>
        <div class="form-group">
            <label for="company_contact" class="control-label">Company Contact #</label>
            <input type="text" name="company_contact"  id="company_contact" required class="form-control form-control-sm rounded-0" value="<?php echo isset($meta['company_contact']) ? $meta['company_contact'] : '' ?>">
        </div>
        <div class="form-group">
            <label for="company_address" class="control-label">Company Address</label>
            <textarea rows = "3" type="text" name="company_address" id="company_address" required class="form-control form-control-sm rounded-0"><?php echo isset($meta['company_address']) ? $meta['company_address'] : '' ?></textarea>
        </div>
        <div class="form-group">
            <label for="logo" class="control-label">Logo</label>
            <input type="file" name="logo" id="logo" class="form-control form-control-sm rounded-0" accept="image/png, image/jpeg, image/jpg, image/ico" onchange="displayImg(this,$(this))">
        </div>
        <div class="form-group text-center">
            <img src="<?php echo isset($meta['logo_path']) && is_file('./..'.(explode('?',$meta['logo_path'])[0])) ? './..'.$meta['logo_path'] : "./../images/no-image-available.png" ?>" alt="" id="logo-display" class="border border-dark bg-dark bg-gradient rounded-circle">
        </div>
        <div class="form-group">
            <label for="website_cover" class="control-label">Website Banner Image</label>
            <input type="file" name="website_cover" id="website_cover" class="form-control form-control-sm rounded-0" accept="image/png, image/jpeg, image/jpg" onchange="displayImg2(this,$(this))">
        </div>
        <div class="form-group text-center mt-2">
            <img src="<?php echo isset($meta['website_cover']) && is_file('./..'.(explode('?',$meta['website_cover'])[0])) ? './..'.$meta['website_cover'] : "./../images/no-image-available.png" ?>" alt="" id="cover-display" class="border border-dark bg-dark bg-gradient cover-display">
        </div>
    </form>
</div>

<script>
    function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#logo-display').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
	        	$('#logo-display').attr('src', '');
        }
	}
    function displayImg2(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cover-display').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
	        	$('#logo-display').attr('src', '');
        }
	}
    $(function(){
        $('#system-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'../Actions.php?a=save_system',
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
                        if("<?php echo isset($system_id) ?>" != 1)
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