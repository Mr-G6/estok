$(".delete-inventory").on('click', function(e){
    e.preventDefault();
    var id = $(this).attr('data-inventory-id');
    bootbox.confirm("Are you sure you want to delete this Inventory!", function(result){
        if(result){
            $.ajax({
                'type' : 'DELETE',
                url : '/inventory',
                data : {
                    id : id,
                    _token : $("meta[name=_token]").attr('content')

                },
                success : function(res){
                    if(res.deleted){
                        window.location = '/';
                    }
                }
            })
        }
    });
});