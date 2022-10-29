@extends('layout')

@section('content')

<article>
    <table id="users"></table>
    <dialog id="favDialog">
        <form method="dialog">
            <section>
                <input type="hidden" name="id" id="id">
                <article>
                    <label for="photo">Add photo</label>
                    <br>
                    <input type="file" name="photo" id="photo" />
                </article>
                <article>
                    <label for="birthDate">Add birth date</label>
                    <br>
                    <input type="date" name="birthDate" id="birthDate">
                </article>
            </section>
            <menu>
                <button id="cancel" type="reset">Cancel</button>
                <button id="confirm" type="submit">Confirm</button>
            </menu>
        </form>
    </dialog>
</article>

<script>
    
    $(document).ready(function(){
        let favDialog = document.getElementById('favDialog')
        let users = {!! $users !!}

        table = new DataTable('#users', {
            data: users,
            columns: [
                {
                    title: 'Photo',
                    data: 'photo',
                    render: function(data){
                        if(data && data != ''){
                            return `<img src="{{ asset('storage/`+data+`') }}" image-name="${data}" width="75">`
                        }else{
                            return ''
                        }
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
                    render: function(data,type,row){
                        return `<button class="edit"><i class="fas fa-edit"></i></button>`
                    }
                }
            ]
        })

        $('.edit').on('click',function(){
            let data = table.row( $(this).parents('tr') ).data();
            $('#id').val(data.id)
            $('#birthDate').val(data.birthDate)
            favDialog.showModal()
        })

        $('#cancel').on('click', function() {
            favDialog.close()
        });

        $('#confirm').on('click', function(){
            let headers = {
                "Content-Type": "multipart/form-data"
            }

            var formData = new FormData();
            var imagefile = document.querySelector('#photo');
            formData.append("id", $('#id').val());
            formData.append("birthDate", $('#birthDate').val());
            formData.append("photo", imagefile.files[0]);

            axios.post(window.location.href.replace('users','updateUser'), formData, headers).then(response => {
                let index = users.findIndex(item => {
                    return item.id = response.data.id
                })
                users[index].birthDate = response.data.birthDate
                users[index].photo = response.data.photo
                table.clear()
                table.rows.add(users)
                table.draw()

                d = new Date();
                let newSrc = `{{ asset('storage/${response.data.photo}') }}`+"?"+d.getTime()
                $("[image-name='"+users[index].photo+"']").attr('src', newSrc)

                favDialog.close()
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
        })
    })
</script>

<style>
    #favDialog{
        border-radius: 5px;
        padding: 15px;
    }
    #favDialog article{
        margin: 10px;
    }
</style>

@endsection