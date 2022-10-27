@extends('layout')

@section('content')

<article>
    <table id="users"></table>
</article>

<script>
    $(document).ready(function(){
        table = new DataTable('#users', {
            data: {!! $users !!},
            columns: [
                {
                    title: 'Photo',
                    data: 'photo',
                    render: function(data){
                        return `<img src="${data}">`
                    }
                },
                { 
                    title: 'Name',
                    data: 'name' 
                },
                { 
                    title: 'User',
                    data: 'username' 
                },
                { 
                    title: 'Email',
                    data: 'email' 
                },
                { 
                    title: 'Company',
                    data: 'company',
                    render: function(data,type,row){
                        return data.name
                    }
                },
                {
                    title: 'Birth date',
                    data: 'birthDate'
                },
                {
                    title: 'Actions',
                    render: function(){
                        return `<button class="edit"><i class="fas fa-edit"></i></button>`
                    }
                }
            ]
        })

        $('#users tbody').on( 'click', 'button', function () {
            if(this.className == 'edit'){
                var selected = table.row( $(this).parents('tr') ).column(1);
                console.log(selected)
                selected.visible(false)
            }
        } );
    })
</script>

@endsection