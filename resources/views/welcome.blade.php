@extends('layout')

@section('content')
<article>
    <table id="users"></table>
</article>
<script>
    $(document).ready(function(){
        let table
        axios.get('https://jsonplaceholder.typicode.com/users').then(response => {
            table = new DataTable('#users', {
                data: response.data,
                order: [[0, 'asc']],
                columns: [
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
                        title: 'Actions',
                        render: function(){
                            return `<button class="save"><i class="fas fa-save"></i></button>`
                        }
                    }
                ]
            })
            $('#users tbody').on( 'click', 'button', function () {
                var data = table.row( $(this).parents('tr') ).data();
                axios.post(window.location.href+'storeUser', { data }).then(response => {
                    Swal.fire({
                        title: 'Success!',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    })
                }).catch(error => {
                    Swal.fire({
                        title: 'Oh!',
                        text: 'Something went wrong',
                        icon: 'error',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }) 
                })
            } );
        })
    })

</script>
@endsection
