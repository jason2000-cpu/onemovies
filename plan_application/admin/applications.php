
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Application List</h3>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="15%">
                <col width="25%">
                <col width="30%">
                <col width="15%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Date Created</th>
                    <th class="text-center">Code</th>
                    <th class="text-center">Applicant</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT * FROM `application_list` order by strftime('%s',date_created) desc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetchArray()):
                ?>
                <tr>
                    <td class="text-center"><?php echo $i++; ?></td>
                    <td class=""><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                    
                    <td class=""><?php echo ($row['application_code']) ?></td>
                    <td class=""><?php echo ucwords($row['fullname']) ?></td>
                    <td class=" text-center">
                    <?php if($row['status'] == 0): ?>
                        <span class="badge bg-secondary  rounded-pill"><small>Pending</small></span>
                    <?php elseif($row['status'] == 1): ?>
                        <span class="badge bg-primary  rounded-pill"><small>Confirmed</small></span>
                    <?php else: ?>
                        <span class="badge bg-danger  rounded-pill"><small>Cancelled</small></span>
                    <?php endif; ?>
                    </td>
                    <th class="text-center ">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item view_data" data-id = '<?php echo $row['application_id'] ?>' href="javascript:void(0)">View</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['application_id'] ?>' data-name = '<?php echo $row['application_code'] ?>' href="javascript:void(0)">Delete</a></li>
                            </ul>
                        </div>
                    </th>
                </tr>
                <?php endwhile; ?>
               
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $('.view_data').click(function(){
            uni_modal('Application Details',"view_application.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.delete_data').click(function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from list?",'delete_data',[$(this).attr('data-id')])
        })
        $('table td,table th').addClass('align-middle px-2 py-1')
        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:5 }
            ]
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'../Actions.php?a=delete_application',
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