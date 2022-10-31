
<style>
    .card-panel{
        overflow:auto !important;
        height:68vh !important;
    }
    #system-logo{
        width:75px;
        height:75px;
        object-fit:scale-down;
        object-position:center center;
    }
    .cover-display{
        width:calc(50%);
        height:25vh;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<div class="card h-100 d-flex flex-column">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Maintenance</h3>
        <div class="card-tools align-middle">
            <!-- <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="create_new">Add New</button> -->
        </div>
    </div>
    <div class="card-body flex-grow-1">
        <div class="col-12 h-100">
            <div class="row h-100">
                <div class="col-md-6 h-100 d-flex flex-column">
                    <div class="w-100 d-flex border-bottom border-dark py-1 mb-1">
                        <div class="fs-5 col-auto flex-grow-1"><b>System Information</b></div>
                        <div class="col-auto flex-grow-0 d-flex justify-content-end">
                            <a href="javascript:void(0)" id="edit_system" class="btn btn-dark btn-sm bg-gradient rounded-2" title="Edt System Information"><span class="fa fa-edit"></span></a>
                        </div>
                    </div>
                    <div class="h-100 overflow-auto border rounded-1 border-dark card-panel px-2 py-1">
                        <dl>
                            <?php 
                            $system = $conn->query("SELECT * FROM `system_info` order by meta_field asc");
                            while($row=$system->fetchArray()):
                                if($row['meta_field'] == 'logo_path'):
                                    $logo = explode('?',$row['meta_value'])[0];
                            ?>  
                            <dt class="text-muted">Logo</dt>
                            <dd class="ps-3">
                            <img src="<?php echo isset($row['meta_value']) && is_file('./..'.$logo) ? './..'.$row['meta_value'] : "./../images/no-image-available.png" ?>" alt="" id="system-logo" class="border border-dark bg-dark bg-gradient rounded-circle">
                            </dd>
                            <?php 
                            elseif($row['meta_field'] == 'website_cover'):
                            $cover = explode('?',$row['meta_value'])[0];
                            ?>
                            <dt class="text-muted">Website Cover</dt>
                            <dd class="ps-3">
                            <img src="<?php echo isset($row['meta_value']) && is_file('./..'.$cover) ? './..'.$row['meta_value'] : "./../images/no-image-available.png" ?>" alt="" id="system-cover" class="border border-dark bg-dark bg-gradient cover-display">
                            </dd>
                            <?php else: ?>
                                <dt class="text-muted"><?php echo ucwords(str_replace('_',' ',$row['meta_field'])) ?></dt>
                                <dd class="ps-3">
                                    <?php echo $row['meta_value'] ?>
                                </dd>
                            <?php endif; ?>
                            <?php endwhile; ?>  
                        </dl>

                    </div>
                </div>
                <div class="col-md-6 h-100 d-flex flex-column">
                    <div class="w-100 d-flex border-bottom border-dark py-1 mb-1">
                        <div class="fs-5 col-auto flex-grow-1"><b>About Us Content</b></div>
                        <div class="col-auto flex-grow-0 d-flex justify-content-end">
                            <a href="javascript:void(0)" id="edit_about" class="btn btn-dark btn-sm bg-gradient rounded-2" title="Edt About Us Content"><span class="fa fa-edit"></span></a>
                        </div>
                    </div>
                    <div class="h-100 overflow-auto border rounded-1 border-dark card-panel px-2 py-1">
                            <?php echo is_file('./../about.html') ? file_get_contents('./../about.html') : 'No About Us Content Yet' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#edit_system').click(function(){
            uni_modal('Edit System Information',"manage_system.php?","mid-large")
        })
        $('#edit_about').click(function(){
            uni_modal('Manage About Us Content',"manage_about.php?","mid-large")
        })
       
        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:6 }
            ]
        })
    })
    function update_stat_cat($id,$status){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'../Actions.php?a=update_stat_cat',
            method:'POST',
            data:{id:$id,status:$status},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled',false)
                }
            }
        })
    }
    function delete_system($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'../Actions.php?a=delete_system',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled',false)
                }
            }
        })
    }
</script>